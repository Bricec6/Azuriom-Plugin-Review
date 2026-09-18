<?php

namespace Azuriom\Plugin\Review\Services;

use Azuriom\Models\Setting;
use Azuriom\Plugin\Review\Models\Review;
use Azuriom\Plugin\Review\Sources\ReviewSource;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ReviewImporter
{
    /**
     * Import the reviews of the given source and return the number of
     * reviews currently published on it.
     *
     * @throws \Throwable
     */
    public function import(ReviewSource $source): int
    {
        try {
            $count = $this->sync($source);

            Setting::updateSettings([
                $source->settingKey('synced_at') => now()->toDateTimeString(),
                $source->settingKey('error') => null,
            ]);

            return $count;
        } catch (Throwable $e) {
            Setting::updateSettings([
                $source->settingKey('error') => Str::limit($e->getMessage(), 200),
            ]);

            throw $e;
        }
    }

    /**
     * @throws \Throwable
     */
    private function sync(ReviewSource $source): int
    {
        $token = $source->token();

        if ($token === null) {
            throw new RuntimeException(trans('review::admin.sources.missing-token'));
        }

        $externalReviews = $source->fetch($token);
        $listingUrl = $source->listingUrl();
        $ids = [];

        Review::withoutEvents(function () use ($source, $externalReviews, $listingUrl, &$ids) {
            foreach ($externalReviews as $external) {
                if (trim($external->content) === '') {
                    continue;
                }

                $ids[] = $external->id;

                Review::updateOrCreate([
                    'source' => $source->domain(),
                    'source_id' => $external->id,
                ], [
                    'type' => Review::TYPE_IMPORTED,
                    'author_name' => $external->authorName,
                    'rating' => $external->rating,
                    'content' => $external->content,
                    'source_url' => $listingUrl,
                    'created_at' => $external->createdAt ?? now(),
                ]);
            }

            Review::imported()
                ->where('source', $source->domain())
                ->whereNotIn('source_id', $ids)
                ->delete();
        });

        return count($ids);
    }
}
