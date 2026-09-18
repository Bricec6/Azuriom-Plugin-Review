<?php

namespace Azuriom\Plugin\Review\Sources;

use Carbon\Carbon;

class ExternalReview
{
    public function __construct(
        public readonly string $id,
        public readonly string $content,
        public readonly ?string $authorName = null,
        public readonly ?int $rating = null,
        public readonly ?Carbon $createdAt = null,
    ) {
        //
    }
}
