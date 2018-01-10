<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/1/10
 * Time: 下午10:15
 */
namespace App\Http\Controllers;

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
      * @param
      * @return mixed
      * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
      */
     public function server()
     {
         $app = app('wechat.official_account');
         $app->server->push(function ($message) {
             return "终于等到你 ！";
         });

         return $app->server->serve();
     }


     public function valid(Request $request)
     {
         Log::error(json_encode($_REQUEST));
         $echoStr = $request->input('echostr');
         if($this->checkSignature($request)){
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
         $tmpStr = implode( $tmpArr );
         $tmpStr = sha1( $tmpStr );
         if( $tmpStr == $signature ) {
             return true;
         } else {
             return false;
         }
     }
 }

