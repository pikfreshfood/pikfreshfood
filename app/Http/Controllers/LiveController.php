<?php

namespace App\Http\Controllers;

use App\Models\VendorLiveVideo;

class LiveController extends Controller
{
    public function index()
    {
        $videos = VendorLiveVideo::query()
            ->with('vendor')
            ->where('is_active', true)
            ->whereHas('vendor')
            ->latest()
            ->take(60)
            ->get();

        return view('live.index', compact('videos'));
    }
}
