<?php

return [
    'plugin' => [
        'name' => "Review",
    ],
    'index' => [
        'title' => "Reviews",
    ],
    'imports' => [
        'title' => "Import reviews",
    ],
    'settings' => [
        'title' => "Settings",
        'display' => [
            'title' => "Display",
            'per-page' => "Reviews per page",
            'imported' => "Display imported reviews in the list",
            'average' => "Include imported reviews in the average rating",
        ],
    ],
    'sources' => [
        'title' => "Import sources",
        'description' => "Import the reviews already published on the listings where your server is referenced.",
        'token' => "API token",
        'test' => "Test",
        'sync' => "Sync reviews",
        'enable' => "Import the reviews of this listing",
        'vote-token' => "Token retrieved from the Vote plugin (:site).",
        'vote-placeholder' => "Using the Vote plugin token",
        'missing-token' => "No API token is configured for this listing.",
        'test-success' => ":count review(s) found on this listing.",
        'sync-success' => ":count review(s) imported.",
        'imported-count' => "{1} :count imported review|[2,*] :count imported reviews",
        'last-sync' => "Last sync: :date",
        'unsupported' => [
            'serveur-prive' => "The API of this listing returns the number of reviews, not their content.",
            'serveur-minecraft' => "This listing does not expose any API for its reviews.",
        ],
    ],
    'permissions' => [
        "create" => "Create a review",
        "delete" => [
            "other" => "Delete other reviews",
        ],
    ],
    'logs' => [
        "reviews-reviews" => [
            "created" => "Created the review #:id",
            "updated" => "Updated the review #:id",
            "deleted" => "Deleted the review #:id",
        ],
        'settings' => "Updated review settings",
        'synced' => "Imported :count review(s) from :source",
    ],
    'table' => [
        'rating' => 'Rating',
        'source' => 'Source',
        'local' => 'Website',
    ],
    'achievement' => [
        'post' => 'Review posted',
        'five_star' => 'Five-star review posted',
    ],
    'support' => "Discord support",
    "serveurliste" => "Top Servers listing",
    "contribute" => "Contribute",
];
