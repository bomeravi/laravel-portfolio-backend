<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\PortfolioItem;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $portfolioItems = PortfolioItem::orderBy('created_at', 'desc')->where('status',1)->get();
        return response()->json($portfolioItems);
    }

    public function list(Request $request): JsonResponse
    {
        $portfolioItems = PortfolioItem::orderBy('created_at', 'desc')->where('status',1)->where('featured_homepage', 1)->get();
        return response()->json($portfolioItems);
    }

    public function show(PortfolioItem $portfolioItem): JsonResponse
    {
        return response()->json($portfolioItem);
    }
}
