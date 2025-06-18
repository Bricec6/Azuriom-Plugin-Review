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
        $reviews = Review::orderBy('created_at', 'asc')->paginate(9);

        return view('review::index', ['reviews' => $reviews]);
    }

}
