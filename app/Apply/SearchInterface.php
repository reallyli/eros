<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/1/11
 * Time: 下午1:55
 */

 namespace App\Apply;

 interface SearchInterface
 {

     /**
      * Method description:getSearchResult
      *
      * @author reallyli <zlisreallyli@outlook.com>
      * @param
      * @return void
      * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
      */
      public function getSearchResult();

 }