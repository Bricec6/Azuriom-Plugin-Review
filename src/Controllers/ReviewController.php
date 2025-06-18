<?php

namespace Azuriom\Plugin\Review\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Review\Models\Review;
use Azuriom\Plugin\Review\Requests\ReviewRequest;

class ReviewController extends Controller
{
    /**
     * Construct a new ReviewController instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Review::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request)
    {
        $user = auth()->user();

        if (Review::where('author_id', $user->id)->count() >= 1) {
            return back()->with('error', trans('review::messages.validation.max-reviews', ['count' => 1]));
        }

        Review::create($request->validated());

        return to_route('review.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \LogicException
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return to_route('review.index');
    }
}
