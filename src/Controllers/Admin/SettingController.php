<?php

namespace Azuriom\Plugin\Review\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the review settings page.
     */
    public function show()
    {
        return view('review::admin.settings', [
            'perPage' => setting('review.per-page', 9),
            'displayImported' => setting('review.display-imported', true),
            'averageImported' => setting('review.average-imported', true),
        ]);
    }

    /**
     * Update the settings.
     */
    public function save(Request $request)
    {
        $validated = $this->validate($request, [
            'per-page' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        Setting::updateSettings([
            'review.per-page' => $validated['per-page'],
            'review.display-imported' => $request->boolean('display-imported'),
            'review.average-imported' => $request->boolean('average-imported'),
        ]);

        ActionLog::log('review.settings.updated');

        return to_route('review.admin.settings')
            ->with('success', trans('messages.status.success'));
    }
}
