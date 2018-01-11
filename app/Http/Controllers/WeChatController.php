<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/1/10
 * Time: 下午10:15
 */
namespace App\Http\Controllers;

use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class WeChatController
 * @package App\Http\Controllers;
 */
class WeChatController extends Controller
{
    /**
     * Method description:server
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @throws \Exception
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function server()
    {
        Log::info('request arrived.');
        try {
            $app = app('wechat.official_account');
            $app->server->push(function ($message) {
                Log::info(json_encode($message));
                switch ($message['MsgType']) {
                    case 'event':
                        //订阅行为
                        if ($message == 'subscribe') {
                            return $this->getMyNews();
                        }
                        return '你到底在干啥？';
                        break;
                    case 'text':
                        return date('Y-m-d', $message['CreateTime']) . '是个值得纪念的日子终于等到你';
                        break;
                    case 'image':
                        return '图片链接是' . $message['PicUrl'];
                        break;
                    case 'voice':
                        return '你说的什么我听不清楚!但是我知道这个语音的格式是' . $message['Format'];
                        break;
                    case 'video':
                        return '你发的什么玩意儿？视频消息媒体ID' . $message['MediaId'];
                        break;
                    case 'location':
                        return '哈哈 我知道你在哪里了，经度：' . $message['Location_X'] . '，维度：' . $message['Location_Y'] . ", 具体位置是" . $message['Label'];
                        break;
                    case 'link':
                        return '你这个链接我懂得，消息标题是' . $message['Title'] . '，消息描述是' . $message['Description'] . '，消息链接是' . $message['Url'];
                        break;
                    // ... 其它消息
                    default:
                        return '终于等到你';
                        break;
                }
            });

            return $app->server->serve();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function valid(Request $request)
    {
        Log::error(json_encode($_REQUEST));
        $echoStr = $request->input('echostr');
        if ($this->checkSignature($request)) {
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature($request)
    {
        $signature = $request->input('signature');
        $timestamp = $request->input('timestamp');
        $nonce = $request->input('nonce');
        $token = 'kerry0709';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method description:createMenu
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @param
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function getMyNews()
    {
        $items = [
            new NewsItem([
                'title'       => '路途遥远 四季分明',
                'description' => '走过一段路，那些道听途说似乎真的有道理',
                'url'         => 'https://www.hixiaogan.cn',
                'image'       => 'https://www.hixiaogan.cn/img/daily_pic.png',
            ])
        ];

        return new News($items);
    }
}
