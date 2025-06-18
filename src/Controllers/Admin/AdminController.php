<?php

namespace Azuriom\Plugin\Review\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Review\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $reviews = Review::with(['author'])
            ->when($search, fn (Builder $query) => $query->search($search))
            ->latest()
            ->paginate();

        return view('review::admin.index', [
            'search' => $search,
            'reviews' => $reviews
        ]);
    }
}
