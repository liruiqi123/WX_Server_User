<?php
namespace app\user\controller;

use think\Controller;
use think\Db;
use think\Log;
use think\Model;

class Login extends Controller
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



    public function getUserOpenid($code)
    {
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=wx7a1aa038211464b4&secret=56a72e174923c1bdadd7cb39fefa101f&js_code=$code&grant_type=authorization_code";

        $info = file_get_contents($url);

        $json = json_decode($info);//对json数据解码

        $arr = get_object_vars($json);

        $openid = $arr['openid'];

        $session_key = $arr['session_key'];

        return $openid;

     }





    public function postUserLogin($code,$gameKey)
    {
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=wx7a1aa038211464b4&secret=56a72e174923c1bdadd7cb39fefa101f&js_code=$code&grant_type=authorization_code";
       
        Log::write('查看是否存在记录cookie');

        $info = file_get_contents($url);

        $json = json_decode($info);//对json数据解码
       
        $arr = get_object_vars($json);
     
        $openid = $arr['openid'];
 
        $session_key = $arr['session_key'];

        $result = Db::execute('insert into user_login (gamekey,code,openid,session_key) values (?,?,?,?)',[$gameKey,$code,$openid,$session_key]);

        return $openid;

     }


    public function postUserInfo($code,$gameKey,$picture,$city,$country,$gender,$language,$nickName,$province)
    {

        $result = Db::execute('insert into user_info (gamekey,code,picture,city,country,gender,language,nickName,province) values (?,?,?,?,?,?,?,?,?)',[$gameKey,$code,$picture,$city,$country,$gender,$language,$nickName,$province]);

        $token =  $this->postUserLogin($code,$gameKey);

        if ($result == 1) {

           $this->ajaxReturn(['code'=>20000, 'msg'=>'操作成功','token'=>$token]);

        }else{

           $this->ajaxReturn(['code'=>20000, 'msg'=>'操作失败']);

        }


    }





}
