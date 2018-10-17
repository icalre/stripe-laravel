<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    public function videos()
    {
        $video = new Video();

        return view('videos.show', compact('video'));
    }
}
