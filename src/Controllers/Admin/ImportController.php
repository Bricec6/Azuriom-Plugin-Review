<?php

namespace Azuriom\Plugin\Review\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Setting;
use Azuriom\Plugin\Review\Models\Review;
use Azuriom\Plugin\Review\Services\ReviewImporter;
use Azuriom\Plugin\Review\Sources\SourceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class ImportController extends Controller
{
    public function __construct(private readonly SourceManager $sources)
    {
        //
    }

    /**
     * Display the import sources page.
     */
    public function show()
    {
        return view('review::admin.imports', [
            'sources' => $this->sources->all(),
            'importedCounts' => Review::imported()
                ->selectRaw('source, count(*) as total')
                ->groupBy('source')
                ->pluck('total', 'source'),
        ]);
    }

    /**
     * Update the import sources.
     */
    public function save(Request $request)
    {
        $settings = [];
        $inputs = $request->input('sources', []);

        foreach ($this->sources->all() as $source) {
            if (! $source->isSupported()) {
                continue;
            }

            $input = $inputs[$source->domain()] ?? [];
            $token = trim((string) ($input['token'] ?? ''));

            $settings[$source->settingKey('token')] = $token !== '' ? Str::limit($token, 255, '') : null;
            $settings[$source->settingKey('enabled')] = isset($input['enabled']);
        }

        Setting::updateSettings($settings);

        ActionLog::log('review.settings.updated');

        return to_route('review.admin.imports')
            ->with('success', trans('messages.status.success'));
    }

    /**
     * Check that the reviews of a listing can be retrieved with the given token.
     */
    public function test(Request $request, string $domain)
    {
        $source = $this->sources->get($domain);

        abort_if($source === null || ! $source->isSupported(), 404);

        $token = trim((string) $request->input('token')) ?: $source->token();

        if ($token === null) {
            return response()->json([
                'success' => false,
                'message' => trans('review::admin.sources.missing-token'),
            ]);
        }

        try {
            $reviews = $source->fetch($token);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => Str::limit($e->getMessage(), 200),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => trans('review::admin.sources.test-success', ['count' => count($reviews)]),
        ]);
    }

    /**
     * Import right now the reviews of a listing.
     */
    public function sync(string $domain, ReviewImporter $importer)
    {
        $source = $this->sources->get($domain);

        abort_if($source === null || ! $source->isSupported(), 404);

        try {
            $count = $importer->import($source);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => Str::limit($e->getMessage(), 200),
            ]);
        }

        ActionLog::log('review.sources.synced', null, [
            'source' => $source->domain(),
            'count' => $count,
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('review::admin.sources.sync-success', ['count' => $count]),
        ]);
    }
}
