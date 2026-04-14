<?php

namespace App\Http\Controllers\Api\home;

use App\Http\Controllers\Controller;
use App\Models\BannerApp;
use Illuminate\Http\Request;



class HomeController extends Controller
{
    public function index(Request $request)
    {

        $banner = BannerApp::where('is_active', true)->get();
        return response()->json([
            'message' => 'Welcome to the Home API endpoint!',
            'banners' => $banner
        ]);
    }
}
