<?php

namespace App\Http\Controllers;

use App\Apply\NeteaseCloudMusicApi;
use Illuminate\Http\Request;

class MusicController extends Controller
{
    public function list(Request $request)
    {
        $musicId = $request->input('id');
        $neteaseCloudMusicApi = new NeteaseCloudMusicApi();
        $comment = $neteaseCloudMusicApi->makeExecAction('comment_music', [$musicId, 1]);
        $musicDetail = $neteaseCloudMusicApi->makeExecAction('song_detail', [$musicId]);
        $lyric = $neteaseCloudMusicApi->makeExecAction('get_lyric', [$musicId]);
        $musicLyric = str_replace("\n", "\\n", $lyric['lrc']['lyric']);

        return view('music.detail', compact('comment', 'musicDetail', 'musicLyric'));
    }

}