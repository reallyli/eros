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
        $res = $this->makeExecAction();
        if ($res) {
            $data = json_decode($res, true);
        }
        $items = collect($data['result']['songs'])->map(function ($item){
            return new NewsItem([
                    'title'       => $item['name'],
                    'description' => $item['alias'][0] ?? $item['artists'][0]['name'],
                    'url'         => 'https://www.hixiaogan.cn',
                    'image'       => $item['artists'][0]['img1v1Url'] ?? 'https://www.hixiaogan.cn/img/daily_pic.png',
                ]);
        })->toArray();

        return new News($items);
    }

    /**
     * Method description:make
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @throws \Exception
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function makeExecAction()
    {
        $api = $this->getApiByActionName($this->action);

        if (!$api) {
            throw new \Exception('api not found');
        }

        $buildHost = $this->host . '/' . key($api);

        $buildParams = collect($api[key($api)])->combine($this->searchParams)->all();

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
                'search' => [
                    'keywords'
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