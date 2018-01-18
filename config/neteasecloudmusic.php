<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/1/12
 * Time: 上午10:17
 */

/*
|--------------------------------------------------------------------------
| NetEaseCloudMusic api config
|--------------------------------------------------------------------------
|
| see https://github.com/Binaryify/NeteaseCloudMusicApi
|
*/
 return [
      // 歌曲搜索
     'keyword_search' => [
         'search/suggest' => [
             'keywords'
         ]
     ],
     // 音乐资源地址
     'music_url' => [
         'music/url' => [
             'id'
         ]
     ],
     // 歌曲详情信息
     'song_detail' => [
         'song/detail' => [
             'ids'
         ]
     ],
     // 评论信息
     'comment_music' => [
         'comment/music' => [
             'id', 'limit'
         ]
     ]
     ,
     // 歌词信息
     'get_lyric' => [
        'lyric' => [
            'id'
        ]
     ]
 ];