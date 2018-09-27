<?php
namespace app\user\controller;

use think\Controller;
use think\Db;
use think\Log;
use think\Model;

class Questions extends Controller
{
    public function index()
    {
         $this->ajaxReturn(['code'=>20000, 'msg'=>'success']);
    }



    public  function ajaxReturn($data,$type='',$json_option=0) {
            //if(empty($type)) $type  =   C('DEFAULT_AJAX_RETURN');
            if(empty($type)) $type  =   "JSON";
            switch (strtoupper($type)){
                case 'JSON' :
                    // 返回JSON数据格式到客户端 包含状态信息
                    header('Content-Type:application/json; charset=utf-8');
                    exit(json_encode($data,$json_option));
                case 'XML'  :
                    // 返回xml格式数据
                    header('Content-Type:text/xml; charset=utf-8');
                    exit(xml_encode($data));
                case 'JSONP':
                    // 返回JSON数据格式到客户端 包含状态信息
                    header('Content-Type:application/json; charset=utf-8');
                    $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                    exit($handler.'('.json_encode($data,$json_option).');');
                case 'EVAL' :
                    // 返回可执行的js脚本
                    header('Content-Type:text/html; charset=utf-8');
                    exit($data);
                default     :
                    // 用于扩展其他返回格式数据
                    Hook::listen('ajax_return',$data);
            }
        }









    public function startGame()
    {

          $list = Db::query("select * from questions");
              if($list){
                  $quests0 = $list[0];
                  $quests1 = $list[1];
                  $quests2 = $list[2];
                  dump($quests0);
                  dump($quests1);
                  dump($quests2);
              } else {
                  return '0000';
              }

     }





}
