<?php
namespace app\user\controller;

use think\Controller;
use think\Db;
use think\Log;
use think\Model;

class GlobalData extends Controller
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









    public function getGlobalData($code,$gameKey)
    {
       $returnData = [
                   'rule_text'=>C('rule'),  //规则数组
                   'game_text'=>C('explain'),
                   'total'=>$totalChallenge, //总参赛挑战次数
                   'right'=>'答对12题随机送娃娃', //标题
                   //'prize'=>[['text'=>'两只熊熊', 'src'=>'https://hb.gzzh.co/envelop_tzdt/prize1.jpg'],['text'=>'两只兔兔', 'src'=>'https://hb.gzzh.co/envelop_tzdt/prize2.jpg']],   //奖品列表
                   'title'=>'答对了送娃娃',    //转发标题
                   'flag'=>$cConfig['ind_share'],   //分享群按钮开关
       			'flag1'=>$cConfig['fail_share'],   //挑战失败分享群按钮开关
                   'flag2'=>$cConfig['card_btn'],  //购买复活卡开关
                   'flag3'=>$cConfig['zl_share'],  //群内智力
                   'flag4'=>$cConfig['zj_share'],  //炫耀战绩
                   'flag5'=>$cConfig['jh_share'],  //获得挑战机会
                   'flag6'=>1,//游戏中分享到群
                   'revive_money'=>C('resCarJE'),//复活卡金额
                   'ctime'=>$sInfo['zm_points'], //用户当前可挑战次数
                   'life'=>$sInfo['revive_time'],  //  当前用户复活卡数量
                   'share_revive_time'=>C('resAmount'), //分享复活次数
                   'buy_revive_time'=>C('resCarAmount'), //购买复活次数
                   'max_challenge_time'=>10,   //最大挑战次数
                   'max_get_prize_time'=>C('giftAmount'),    //最大领奖品次数
                   'excAmount'=>C('excAmount'),
                   'guide_txt'=>'邀请好友，帮忙答题',
                   'sildeTxt'=>$prizeList,//[['head_img'=>'https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epkDmbTFnMWh0qsjDjw0tFlpJw4CibOXgbr6bhdRhTwhjxHGhsSznJmoYAqnOWB7bVZzk2iaTicyIrWQ/0', 'sildeText'=>'1恭喜xx领到xx奖品'], ['head_img'=>'https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epkDmbTFnMWh0qsjDjw0tFlpJw4CibOXgbr6bhdRhTwhjxHGhsSznJmoYAqnOWB7bVZzk2iaTicyIrWQ/0', 'sildeText'=>'2恭喜xx领到xx奖品'],  ]//获取最近领到娃娃奖品的信息
               ];


     }



     public function getGameFlagOld(){
             $wxUserModel = M('wx_user');
             try {
                 $res = $wxUserModel->where(['openid'=>$this->openid])->setDec('zm_points', 1);

                 if (empty($res)) {
                     $this->ajaxReturn ( [
                         'code' => 40500,
                         'msg' => '数据异常',
                         'ctime' => -1
                     ]);
                 }
             } catch (Exception $e) {
                 $this->ajaxReturn ( [
                     'code' => 40500,
                     'msg' => '数据异常',
                     'ctime' => -1
                 ]);
             }

             $ctimes = $wxUserModel->where(['openid'=>$this->openid])->getField('zm_points');

             $this->ajaxReturn ( [
                 'code' => 20000,
                 'msg' => '获取成功',
                 'flag' => 'flag',
                 'ctime' => $ctimes
             ]);
         }





         public function getGameFlag(){

                      $this->ajaxReturn ( [
                          'code' => 20000,
                          'msg' => '获取成功',
                          'flag' => 'flag',
                          'ctime' => 3
                      ]);
                  }





}
