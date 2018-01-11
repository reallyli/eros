<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/1/11
 * Time: 下午2:00
 */
 namespace App\Apply;

 class WeChatBase implements SearchInterface
 {
     protected $path;

     protected $searchName;

     protected $commandLanguage;

     protected $pv;

     /**
      * Method description:__construct
      *
      * @author reallyli <zlisreallyli@outlook.com>
      * @param string $searchName
      * @return mixed
      * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
      */
     public function __construct($searchName = '')
     {
        $this->path = env('SCRIPT_FILE_PATH', base_path() . '/search.py');
        $this->searchName = $searchName;
        $this->commandLanguage = env('COMMAND_LANGUAGE', 'python3');
     }

     /**
      * Method description:getSearchResult
      *
      * @author reallyli <zlisreallyli@outlook.com>
      * @param
      * @throws \Exception
      * @return mixed
      * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
      */
     public function getSearchResult()
     {
        try {
            dd(exec($this->makeExecAction(), $output, $returnVar));
            dd($output[0]);
        } catch (\Exception $e) {
            throw $e;
        }
     }

     /**
      * Method description:makeExecAction
      *
      * @author reallyli <zlisreallyli@outlook.com>
      * @param 
      * @return string
      * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
      */
     public function makeExecAction()
     {
        return $this->commandLanguage . ' ' . $this->path . ' ' . "'$this->searchName'";
     }
 }