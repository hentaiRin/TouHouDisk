<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

    # 网页顶部的菜单列表
    protected $top_menu;

  public function __construct()
  {
    parent::__construct();
    $this->load->library('session');
    $this->load->library('common');
      $this->load->helper('url');
    # 定义中国时区
    date_default_timezone_set('PRC');


  }
/*
|--------------------------------------------------------------------------
| verify_sms 验证短信是否有效
|--------------------------------------------------------------------------
| Desc: 检查不同用途的短信验证码是否有效
|
| @param $phone 手机号
| @param $sms 短信
| @param $sms_type 用途 1 - 注册 2-修改密码
|
| @return 是否有效
*/
  public function verify_sms($phone, $sms, $sms_type)
  {
    $query = $this->common->verifySMS($phone, $sms, $sms_type);
    return $query;
  }

/*
|--------------------------------------------------------------------------
| rule_param 校验前台输入数据是否有效
|--------------------------------------------------------------------------
| Desc: 校验前台输入数据是否有效
|
| @param $str 被检参数
| @param $type 类型
|
| @return 是否有效
*/
  public function rule_param($str, $type)
  {
    switch ($type) {
      case 'phone':
        $query = strlen($str) == 11 ? true : false;
        $msg = "手机号必须为11位数字！";
        break;
      case 'passwd':
        $query = (strlen($str) >= 6 && strlen($str) <= 18) ? true : false;
        $msg = "密码长度必须为6-18位！";
      default:
        # code...
        break;
    }
    if(!$query){
      $this->common->statusCode(210, $msg);
    }
  }

/*
|--------------------------------------------------------------------------
| 参数检测
|--------------------------------------------------------------------------
| Desc: 检测是否有给定数组中指定参数
|
| @param Array 需要匹配的参数数组
| @param Array 被需要匹配的数组
|
| @return Array OR FALSE
*/
  public function check_params($arr, $data)
  {
    foreach($arr as $key){
      $this->common->ParamIsNull($data, $key);
    }
  }

    /**
     * 将指定数组中的值按js和css分开
     * @param $arr
     * @return array
     */
    public function give_href($arr){
        $data = array(
            'js' => array(),
            'css' => array(),
            'icon' => array(),
        );
        if(!empty($arr)){
            foreach($arr as $value){
                $ext = strtolower(trim(substr(strrchr($value, '.'), 1)));
                if($ext == 'js'){
                    $data['js'][] = $value;
                }elseif($ext == 'css'){
                    $data['css'][] = $value;
                }else{
                    $data['icon'][] = $value;
                    $data['ext'] = $ext;
                }
            }
        }
        return $data;
    }

}
