<?php

namespace Azuriom\Plugin\Review\Sources;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class TopServeursSource extends ReviewSource
{
    public function domain(): string
    {
        return 'top-serveurs.net';
    }

    public function name(): string
    {
        return 'Top-Serveurs';
    }

    public function fetch(string $token): array
    {
        $response = Http::acceptJson()
            ->timeout(15)
            ->get('https://api.top-serveurs.net/v1/servers/'.rawurlencode($token).'/advices');

        if ($response->json('success') !== true) {
            throw new RuntimeException($response->json('message') ?? 'HTTP '.$response->status());
        }

        $reviews = [];

        foreach ($response->json('advices') ?? [] as $advice) {
            $content = $this->value($advice, ['comment', 'message', 'description', 'content', 'advice']);

            if ($content === null) {
                continue;
            }

            $date = $this->value($advice, ['created_at', 'date', 'datetime', 'posted_at']);
            $rating = $this->value($advice, ['rating', 'note', 'stars', 'score']);

            $reviews[] = new ExternalReview(
                id: (string) ($this->value($advice, ['id', 'advice_id']) ?? md5($content)),
                content: $content,
                authorName: $this->value($advice, ['playername', 'username', 'pseudo', 'author', 'name']),
                rating: is_numeric($rating) ? max(1, min(5, (int) round($rating))) : null,
                createdAt: $date !== null ? Carbon::parse($date) : null,
            );
        }

        return $reviews;
    }

    /**
     * The advice format is not documented, so the first matching key is used.
     */
    private function value(array $advice, array $keys)
    {
        foreach ($keys as $key) {
            if (isset($advice[$key]) && $advice[$key] !== '') {
                return $advice[$key];
            }
        }

        return null;
    }
}
