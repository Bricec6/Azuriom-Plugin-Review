<?php

namespace Azuriom\Plugin\Review\Controllers\Api;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Review\Models\Review;

class ApiController extends Controller
{
    /**
     * Show the plugin API default page.
     */
    public function index()
    {
        return Review::paginate(10);
    }
}
