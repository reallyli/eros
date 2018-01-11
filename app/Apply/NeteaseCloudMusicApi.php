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

    protected $initApiCollect;

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
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getSearchResult()
    {
        $res = $this->makeExecAction($this->action, $this->searchParams);
        if (!$res) {
           return '你想听什么歌？';
        }
        $data = json_decode($res, true);
        $items = collect($data['result']['songs'])->map(function ($item){
            $musicUrl = json_decode($this->makeExecAction('music_url', [$item['id']]), true);
            $musicDetail = json_decode($this->makeExecAction('song_detail', [$item['id']]), true);
            $musicComment = json_decode($this->makeExecAction('comment_music', [$item['id'], 1]), true);
            $randCommentShow = mt_rand(0, count($musicComment['hotComments']) - 1 );
            return new NewsItem([
                    'title'       => $item['name'] . ' - ' . $item['artists'][0]['name'],
                    'description' => $musicComment['hotComments'][$randCommentShow]['user']['nickname'] . '说：' . $musicComment['hotComments'][$randCommentShow]['content'],
                    'url'         => $musicUrl['data'][0]['url'],
                    'image'       => $musicDetail['songs'][0]['al']['picUrl'],
                ]);
        })->take(1)->toArray();

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

        return Curl::to($buildHost)
            ->withData($buildParams)
            ->get();
    }

    /**
     * Method description:setInitApiList
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param 
     * @return array
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function setInitApiList() : array
    {
        return [
            'keyword_search' => [
                'search/suggest' => [
                    'keywords'
                ]
            ],
            'music_url' => [
                'music/url' => [
                    'id'
                ]
            ],
            'song_detail' => [
                'song/detail' => [
                    'ids'
                ]
            ],
            'comment_music' => [
                'comment/music' => [
                    'id', 'limit'
                ]
            ]
        ];
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
        return $this->setInitApiList()[$actionName] ?? [];
    }
}