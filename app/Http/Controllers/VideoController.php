<?php

namespace App\Http\Controllers;

use App\Content;
use App\Http\Requests\StoreVideoRequest;
use App\Jobs\ConvertVideo;
use App\Jobs\ConvertVideoForDownloading;
use App\Jobs\ConvertVideoForStreaming;
use App\Storage;
use App\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    //
    public function store(StoreVideoRequest $request)
    {
        $video = Video::create([
            'disk'          => 'videos_disk',
            'original_name' => $request->video->getClientOriginalName(),
            'path'          => $request->video->store('videos', 'videos_disk'),
            'title'         => $request->title,
        ]);
        $this->dispatch(new ConvertVideoForDownloading($video));
        $this->dispatch(new ConvertVideoForStreaming($video));

        return response()->json([
            'id' => $video->id,
        ], 201);
    }

    public function convert(Request $request)
    {
        $content_id = $request->content_id;
        Content::findOrFail($content_id);
        $storage = Storage::where(['content_id'=>$content_id,'version'=>'High'])->first();
        //Convert Video
        echo $content_id;

        if (!empty($storage)){
            echo $storage->id;
            $this->dispatch(new ConvertVideo($storage));
        }

    }
}
