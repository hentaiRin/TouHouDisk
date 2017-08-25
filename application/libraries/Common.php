<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 公共辅助类
*/
class Common
{
	protected $CI;

	public function __construct()
	{
		$this->CI = & get_instance();
	}
	/**
	 * [PostParamsToArray 接口返回$_Post获取Array]
	 * 请求中ContentType 必须为 application/x-www-form-urlencoded
	 */
	public function PostParamsToArray()
	{
		$data = $_POST;
		if(!empty($_FILES)){
			$data['file'] = $_FILES;
		}
		return self::FilterData($data);
	}

	/**
	 * [GetParamsToArray 接口返回$_Get获取Array]
	 */
	public function GetParamsToArray()
	{
		$data = $_GET;
		return self::FilterData($data);
	}

	/**
	 * [GetMobileType 获取访问者请求的手机系统类型]
	 */
	public function GetMobileType()
	{
		$clinet = $this->CI->input->user_agent(); #CI input类库
		log_message('error',"检测:".$clinet);
		if(strpos($clinet, 'okhttp') >= 0 && strpos($clinet, 'okhttp') !== false){
			return 1; #Android
		}else if(strpos($clinet, 'iOS') >= 0 || strpos($clinet, 'iPhone') >= 0 || strpos($clinet, 'iPad') >= 0){
			return 2; #iOS;
		}else{
			return 3; #Other Platform
		}
	}

// ------------------------------------------------------------------------ 令牌&验证码&订单编号处理块

	/**
	 * [GenToken 生成随机Token-方法一]
	 * @param  integer $len [长度]
	 * @param  boolean $md5 [是否为MD5]
	 * @return [type]       [返回token令牌]
	 */
	function GenToken( $len = 32, $md5 = true ) {
       # Seed random number generator
          # Only needed for PHP versions prior to 4.2
          mt_srand( (double)microtime()*1000000 );
          # Array of characters, adjust as desired
          $chars = array(
              'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',
              'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',
              '/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',
              'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',
              '?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',
              '=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',
              'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*'
          );
          # Array indice friendly number of chars;
          $numChars = count($chars) - 1; $token = '';
          # Create random token at the specified length
          for ( $i=0; $i<$len; $i++ )
              $token .= $chars[ mt_rand(0, $numChars) ];
          # Should token be run through md5?
          if ( $md5 ) {
              # Number of 32 char chunks
              $chunks = ceil( strlen($token) / 32 ); $md5token = '';
              # Run each chunk through md5
              for ( $i=1; $i<=$chunks; $i++ )
                  $md5token .= md5( substr($token, $i * 32 - 32, 32) );
              # Trim the token
              $token = substr($md5token, 0, $len);
          } return $token;
      }

  /**
   * [GenCaptcha 生成随机字符串-验证码]
   * @param [type] $len   [长度]
   * @param [type] $chars [自定义字符]
   */
	public function GenCaptcha($len = 4, $chars = null)
	{
	    if (is_null($chars)){
	    	$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
	    }
	    mt_srand(10000000*(double)microtime());
	    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
	        $str .= $chars[mt_rand(0, $lc)];
	    }
	    return $str;
	}

  /**
   * GUID唯一编码
   */
	function guid() {
	    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
	    $uuid =
	    substr($charid, 0, 8).
	    substr($charid, 8, 4).
	    substr($charid,12, 4).
	    substr($charid,16, 4).
	    substr($charid,20,12);
	    return $uuid;
	}

// ------------------------------------------------------------------------ 数据安全处理块
	/**
	 * [FilterData 对数据进行安全过滤]
	 * @param [type] $data [原始数据]
	 */
	private function FilterData($data)
	{
		$data = $this->CI->security->xss_clean($data);
		return $data;
	}

	/**
	 * [ParamIsNull 判断参数是否为空]
	 * @param [type] $param [参数名]
	 */
	public function ParamIsNull($data, $paramName)
	{
		if(!isset($data["$paramName"])){
			self::StatusCode(300,"缺少参数[".$paramName."]");
			exit();
		}else{
			return $data["$paramName"];
		}
	}

// ------------------------------------------------------------------------ 返回码处理块

	/**
	 * [StatusCode 返回码说明]
	 * @param integer $code  [返回码 必须]
	 * @param string  $mark  [返回说明 不必须]
	 * @param string  $token [令牌 不必须]
	 * @param array   $array [返回数据 不必须]
	 */
	public function StatusCode($code = 200,$mark = '',$token='',$array = array(), $model = 0)
	{
		$data = array();
		switch ($code) {
			# 系统繁忙，请求超时
			case 100:
			# 权限不足
			case 403:
			# 无效接口
			case 404:
			# 服务器内部错误
			case 500:
				$data = array('code' => $code, 'time' => time());
				break;
			# 请求成功|带数组
			case 200:
				if(count($array) != 0){
					if($token != ''){
						$data = array('code' => $code, 'token' => $token, 'datas' => $array, 'time' => time());
					}else{
						$data = array('code' => $code, 'datas' => $array, 'time' => time());
					}
				}else{
					if($model == 0){
						$data = array('code' => $code, 'time' => time());
					}else{
						$data = array('code' => $code, 'datas' => array(), 'time' => time());
					}
				}
				break;
			# token无效
			case 220:
				$mark = "登录状态无效或者已过期！";
				$data = array('code' => $code, 'mark' => $mark, 'time' => time());
				break;
			# 存在业务错误，描述
			case 210:
			# 接口参数错误，描述
			case 300:
				$data = array('code' => $code, 'mark' => $mark, 'time' => time());
				break;
			# 默认
			default:
				$data = array('code' => $code, 'time' => time());
				break;
		}
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit();
	}

// ------------------------------------------------------------------------ 数据分页类
	/**
	 * [getOffsetSize 获取分页偏移量]
	 * @param  [int] $nowPage  [当前页码]
	 * @param  [int] $pageSize [当前页数]
	 * @return [int]           [分页偏移量]
	 */
	public function getOffsetSize($nowPage,$pageSize)
	{
		if($nowPage <= 1){
			$offSize = 0;
		}else{
			$offSize = $pageSize * ( $nowPage - 1);
		}
		return $offSize;
	}

 	/** 计算两组经纬度坐标 之间的距离
 	* params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km);
 	* return m or km */
 	function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
 	{
 		$EARTH_RADIUS=6378.137;
 		$PI=3.1415926;
 		$radLat1 = $lat1 * $PI / 180.0;
 		$radLat2 = $lat2 * $PI / 180.0;
 		$a = $radLat1 - $radLat2;
 		$b = ($lng1 * $PI / 180.0) - ($lng2 * $PI / 180.0);
 		$s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
 		$s = $s * $EARTH_RADIUS; $s = round($s * 1000);
 			if ($len_type > 1) {
 					$s /= 1000;
 				}
 		return round($s,$decimal);
 	}

}
