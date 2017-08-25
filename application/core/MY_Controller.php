<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Controller extends CI_Controller{
	public function __construct()
    {
        parent::__construct();
        # 定义中国时区 
        date_default_timezone_set('PRC');
        # 加载常用类库
        $this->load->library('common');
        $this->load->library('session');
    }

    /**
     * 检查参数是否不为null
     * @param $arr array 需要检查参数的键名
     * @param $data array 被检查的数组
     */
    protected function check_params($arr, $data)
    {
        $mark = "缺少参数";
        $flag = false;
        foreach($arr as $key){
            if(!isset($data["$key"])){
                $mark .= "[".$key."]";
                $flag = true;
            }
        }
        if($flag){
            $this->common->StatusCode(300, $mark);
        }
    }

    /**
     * 检查参数是否为数字
     * @param $arr
     * @param $data
     */
    protected function check_params_num($arr, $data)
    {
        $mark = "参数";
        $flag = false;
        foreach($arr as $key){
            if(!is_numeric($data["$key"])){
                $mark .= "[".$key."]";
                $flag = true;
            }
        }
        $mark .= '必须为数字';
        if($flag){
            $this->common->StatusCode(300, $mark);
        }
    }

    /**
     * 检查参数是否为空字符串
     * @param $arr
     * @param $data
     */
    protected function check_params_empty($arr, $data)
    {
        $mark = "参数";
        $flag = false;
        foreach($arr as $key){
            if($data["$key"] === ''){
                $mark .= "[".$key."]";
                $flag = true;
            }
        }
        $mark .= '不能为空字符串';
        if($flag){
            $this->common->StatusCode(300, $mark);
        }
    }

    /**
     * 联合参数数据检查
     * @param array $num 数字类型参数的key数组
     * @param array $str 字符串类型参数的key数组
     * @param array $data 被检查的参数数组
     */
    protected function check_argument($num = array(), $str = array(), Array $data){
        self::check_params(array_merge($num, $str), $data);
        if(!empty($num)){
            self::check_params_num($num, $data);
        }
        if(!empty($str)){
            self::check_params_empty($str, $data);
        }
    }

    /**
     * 密码加盐
     * @param $pwd
     * @param $salt
     * @return string
     */
    final protected function salt($pwd, $salt){
        return md5(md5($pwd).md5($salt));
    }

    /**
     * 检查验证码是否正确
     * @param $verify string 用户输入的验证码
     * @param $type int 验证码类型
     * @return bool
     */
    final protected function checkVerify($verify, $type = 0){
        $key = strtoupper(md5(VERIFY_KEY.date('Y')));
        $se_verify = $this->session->userdata( $key );
        if(!isset($se_verify)){
            $this->common->StatusCode(210, '验证码已失效');
        }
        $query = strtoupper($verify) == strtoupper($se_verify) ? true : false;
        if($query){
            $this->session->unset_userdata( $key ); # 清除验证码session
            $this->session->unset_userdata( $key.'flag' ); # 清除验证码session的flag
        }else{
            $temp = $this->session->userdata($key.'flag');# 记录输入验证码错误次数
            $flag = isset($temp) ? ++$temp : 1;
            if($flag > 5){ # 错误超过五次之后清楚验证码
                $this->session->unset_userdata( $key ); # 清除验证码session
                $this->session->unset_userdata( $key.'flag' ); # 清除验证码session的flag
                self::checkVerify($verify, $type);
            }
            $this->common->StatusCode(210, '验证码错误');
        }
        return true;
    }

    /**
     * 正则验证输入参数
     * @param $str string 被验证的字符串
     * @param $type string 验证的类型
     */
    final protected function rule_param($str, $type)
    {
        switch ($type) {
            case 'phone':
                $query = preg_match("/^1[34578]{1}\d{9}$/", $str);
                $msg = '请输入正确的手机号码';
                break;
            case 'account':
                $query = preg_match("/^[a-zA-Z]{1}([a-za-z0-9]|[_]){6,30}$/", $str);
                $msg = "用户名只能以字母开头，字母数字下划线，长度6-30！";
                break;
            case 'password':
                $query = (strlen($str) >= 6 && strlen($str) <= 18) ? 1 : 0;
                $msg = "密码只能为6-18位字符";
                break;
            case 'email':
                $query = preg_match("/^[a-z0-9]+@([a-z0-9]+\.)+[a-z]{2,4}$/", $str);
                $msg = "请输入正确的邮箱";
                break;
            case 'qq':
                $query = preg_match("/^[1-9]{1}[0-9]{4,9}$/", $str);
                $msg = "QQ号可能是5到10位吧？检查QQ号是否正确哦~";
                break;
            default:
                $query = 0;
                $msg = "验证类型不正确";
                break;
        }
        if($query == 0){
            $this->common->StatusCode(210, $msg);
        }
    }
}