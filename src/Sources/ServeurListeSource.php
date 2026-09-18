<?php

namespace Azuriom\Plugin\Review\Sources;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ServeurListeSource extends ReviewSource
{
    /**
     * The maximum number of pages fetched on a single import.
     */
    private const MAX_PAGES = 50;

    public function domain(): string
    {
        return 'serveurliste.com';
    }

    public function name(): string
    {
        return 'ServeurListe';
    }

    public function fetch(string $token): array
    {
        $reviews = [];
        $page = 1;

        do {
            $response = Http::acceptJson()
                ->timeout(15)
                ->get('https://www.serveurliste.com/api/recent-comments', [
                    'api_token' => $token,
                    'limit' => 100,
                    'page' => $page,
                ]);

            if ($response->failed()) {
                throw new RuntimeException($response->json('message') ?? 'HTTP '.$response->status());
            }

            foreach ($response->json('data') ?? [] as $comment) {
                $reviews[] = new ExternalReview(
                    id: (string) $comment['id'],
                    content: $comment['description'] ?? '',
                    authorName: $comment['author']['pseudo'] ?? null,
                    rating: isset($comment['rating']) ? (int) $comment['rating'] : null,
                    createdAt: isset($comment['timestamp'])
                        ? Carbon::createFromTimestamp($comment['timestamp'])
                        : null,
                );
            }

            $lastPage = (int) ($response->json('meta.last_page') ?? 1);
        } while ($page++ < min($lastPage, self::MAX_PAGES));

        return $reviews;
    }
}
