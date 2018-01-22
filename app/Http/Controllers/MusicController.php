<?php

namespace App\Http\Controllers;

use App\Apply\NeteaseCloudMusicApi;
use Illuminate\Http\Request;

class MusicController extends Controller
{
    protected $musicObj;

    /**
     * Method description:__construct
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param 
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function __construct()
    {
        $this->musicObj = new NeteaseCloudMusicApi();
    }

    public function list(Request $request)
    {
        $musicId = $request->input('id');
        $comment = $this->musicObj->makeExecAction('comment_music', [$musicId, 1]);
        $musicDetail = $this->musicObj->makeExecAction('song_detail', [$musicId]);
        $lyric = $this->musicObj->makeExecAction('get_lyric', [$musicId]);
        $source = $this->musicObj->getMusicUrl($musicId);
        $musicLyric = str_replace("\n", "\\n", $lyric['lrc']['lyric']);

        return view('music.detail', compact('comment', 'musicDetail', 'musicLyric', 'source'));
    }

    /**
     * Method description:debug
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param 
     * @return void
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function debug()
    {
        $data = $this->musicObj->makeExecAction('keyword_search', ['带你去旅行']);

        dd($data);
    }

    /**
     * Method description:getComment
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param Request $request
     * @return string
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getComment(Request $request) : string
    {
        $musicId = $request->input('id');
        $page = $request->input('page', 1);

        $comment = $this->musicObj->makeExecAction('comment_music', [$musicId, $page]);

        return response()->json($comment);
    }

}