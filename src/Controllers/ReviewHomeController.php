<?php

namespace Azuriom\Plugin\Review\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Review\Models\Review;

class ReviewHomeController extends Controller
{
    /**
     * Show the home plugin page.
     */
    public function index()
    {
        $displayImported = setting('review.display-imported', true);

        $reviews = Review::with('author.role')
            ->when(! $displayImported, fn ($query) => $query->local())
            ->latest()
            ->paginate(setting('review.per-page', 9));

        $ratings = Review::whereNotNull('rating')
            ->when(! setting('review.average-imported', true), fn ($query) => $query->local())
            ->selectRaw('avg(rating) as average, count(*) as total')
            ->first();

        return view('review::index', [
            'reviews' => $reviews,
            'average' => $ratings->average !== null ? round($ratings->average, 1) : null,
            'ratingsCount' => $ratings->total,
        ]);
    }
}
