<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/1/11
 * Time: 下午4:47
 */

namespace App\Apply;

use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use Ixudra\Curl\Facades\Curl;

class NeteaseCloudMusicApi implements SearchInterface
{
    protected $host;

    protected $action;

    protected $searchParams;

    /**
     * Method description:__construct
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param string $action
     * @param array $searchParams
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function __construct(array $searchParams = [], string $action = 'keyword_search')
    {
        $this->host = env('CLOUD_MUSIC_API_HOST');
        $this->action = $action;
        $this->searchParams = $searchParams; // ['说散就散'];
    }

    /**
     * Method description:getSearchResult
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param int $limit
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getSearchResult(int $limit = 1) : News
    {
        $data = $this->makeExecAction($this->action, $this->searchParams);
        if (!$data || !$data['result']) {
            return $this->getDefaultItem();
        }
        $items = collect($data['result']['songs'])->map(function ($item) {
            return new NewsItem([
                    'title'       => $item['name'] . ' - ' . $item['artists'][0]['name'],
                    'description' => $this->getMusicDescription($item['id'], $item['album']['name']),
                    'url'         => $this->getMusicUrl($item['id']),
                    'image'       => $this->getMusicImage($item['id']),
                ]);
        })->take($limit)->toArray();

        return new News($items);
    }

    /**
     * Method description:make
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param string $action
     * @param array $searchParams
     * @throws \Exception
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function makeExecAction(string $action, array $searchParams)
    {
        $api = $this->getApiByActionName($action);

        if (!$api) {
            throw new \Exception('api not found');
        }

        $buildHost = $this->host . '/' . key($api);

        $buildParams = collect($api[key($api)])->combine($searchParams)->all();

        $send = Curl::to($buildHost)
            ->withData($buildParams)
            ->get();
        if (!$send) {
            return false;
        }

        return json_decode($send, true);
    }

    /**
     * Method description:getApiBy
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param string $actionName
     * @return array
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getApiByActionName(string $actionName) : array
    {
        return config('neteasecloudmusic')[$actionName] ?? [];
    }

    /**
     * Method description:getMusicDescription
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param int $musicId
     * @param string $defaultComment
     * @param int $limit
     * @return string
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getMusicDescription(int  $musicId, string $defaultComment, int  $limit = 1) : string
    {
        $musicComment = $this->makeExecAction('comment_music', [$musicId, $limit]);
        if (empty($musicComment['hotComments'])) {
            return $defaultComment;
        }
        // 随机评论
        $randCommentShow = count($musicComment['hotComments']) > 2 ? mt_rand(0, count($musicComment['hotComments']) - 1 ) : 0;

        return $musicComment['hotComments'][$randCommentShow]['user']['nickname'] . ' 评论道👉 ' . $musicComment['hotComments'][$randCommentShow]['content'];
    }

    /**
     * Method description:getMusicImage
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param int $musicId
     * @return string
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getMusicImage(int $musicId) : string
    {
        $musicDetail = $this->makeExecAction('song_detail', [$musicId]);

        return $musicDetail['songs'][0]['al']['picUrl'];
    }

    /**
     * Method description:getMusicUrl
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param int $musicId
     * @return string
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getMusicUrl(int $musicId) : string
    {
        $musicUrl = $this->makeExecAction('music_url', [$musicId]);

        return $musicUrl['data'][0]['url'];
    }

    /**
     * Method description:getDefaultItem
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param 
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getDefaultItem() : News
    {
        $items = [
            new NewsItem([
                'title'       => '网易云音乐在线搜索歌曲',
                'description' => '你想听什么歌？请输入你的歌名...😘',
                'url'         => '#',
                'image'       => 'http://p1.music.126.net/6y-UleORITEDbvrOLV0Q8A==/5639395138885805.jpg',
            ]),
        ];

        return new News($items);
    }
}