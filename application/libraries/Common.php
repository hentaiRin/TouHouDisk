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

// ------------------------------------------------------------------------ 接口数据处理块

	/**
	 * [PostStringToArray POST获取字符串转Array]
	 * 格式:A=a&B=b
	 */
	public function PostStringToArray()
	{
		$remoteData = file_get_contents("php://input");
		$remote = explode('&',$remoteData);
		$data = array();
		foreach ($remote as $item){
			$temp = explode('=',$item);
			$data[$temp[0]] = $temp[1];
		}
		return $data;
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
	 * [GetXmlToArray 微信返回解析]
	 */
	public function GetXmlToArray()
	{
		$postStr = file_get_contents('php://input');
		$msg = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		return $msg;
	}

	/**
	 * [GetIpAddress 获取客户端IP地址]
	 */
	public function GetIpAddress()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    } else {
	        $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}

	/**
	 * [GetClinetType 获取客户端类型]
	 */
	public function GetClinetType()
	{
		$clinet = $_SERVER['HTTP_USER_AGENT'];
		return $clinet;
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
   * [GenCaptcha 生成随机字符串-验证码]
   * @param [type] $len   [长度]
   * @param [type] $chars [自定义字符]
   */
	public function GenAdminCaptcha($len = 4, $chars = null)
	{
	    if (is_null($chars)){
	    	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
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

	/**
	 * [buildOrderNo 生成订单编号]
	 * @return [int] [返回订单编号]
	 */
	function buildOrderNo($mid = '')
	{
		/* 选择一个随机的方案 */
		//mt_srand((double) microtime() * 1000000);
		//return date('Ymd') . uniqid() .str_pad($mid,3,0,STR_PAD_LEFT);
		return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
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
		header('Access-Control-Allow-Origin:*');
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

 	/**
 	 * [ParseXml 解析微信XML信息]
 	 * @param [字符串] $xml [xml信息]
 	 * @return [array] 数组
 	 */
 	public function ParseXml($xml)
	{
		$data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		return $data;
	}


	/**
	 * [CovertXml 把数组转XML]
	 * @param [type] $data [description]
	 */
	public function CovertXml($data)
	{
		$xml = "<?xml version='1.0' encoding='utf-8'?><xml>";
		foreach($data as $key=>$value){
			$xml = $xml."<$key>$value</$key>";
		}
		$xml = $xml."</xml>";
		return $xml;
	}

	/**
	 * [encrypto 对字符串进行正序排序后sha1加密]
	 * @param  [string] $str [来源字符串]
	 * @return [string]      [加密字符串]
	 */
	public function encrypto($str)
	{
		$str = $str."gxssh2016";
		$arr=str_split($str);//提取出字符，放入数组
		usort($arr,'strcmp');//对字符数组进行排序
		$str=implode('',$arr);//形成排序后的字符串
		return sha1($str); //sha1加密
	}

	// ------------------------------------------------------------------------ 短信接口

	protected function Post($data, $target) {
	    $url_info = parse_url($target);
	    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
	    $httpheader .= "Host:" . $url_info['host'] . "\r\n";
	    $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
	    $httpheader .= "Content-Length:" . strlen($data) . "\r\n";
	    $httpheader .= "Connection:close\r\n\r\n";
	    //$httpheader .= "Connection:Keep-Alive\r\n\r\n";
	    $httpheader .= $data;

	    $fd = fsockopen($url_info['host'], 80);
	    fwrite($fd, $httpheader);
	    $gets = "";
	    while(!feof($fd)) {
	        $gets .= fread($fd, 128);
	    }
	    fclose($fd);
	    return $gets;
	}

	/**
	 * [send_sms 短信验证码]
	 * @param  [type]  $phone [手机号码]
	 * @param  integer $type  [短信用途]
	 * @return [type]         [返回是否成功]
	 */
	public function send_sms($phone, $type = 1, $nickname = "", $orderSN = "", $expressName = "")
	{
		$smsCode = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		switch ($type) {
			# 注册
			case 1:
				$content = "【".APP_SMS_NAME."】"."您好，您此次验证码为".$smsCode."，请您尽快注册。验证码有效时间为".EX_TIME."分钟。";
				break;
			# 登录
			case 2:
				$content = "【".APP_SMS_NAME."】"."您好，您此次验证码为".$smsCode."，请您尽快登录。验证码有效时间为".EX_TIME."分钟。";
				break;
			# 忘记密码
			case 3:
				$content = "【".APP_SMS_NAME."】"."您好，您此次验证码为".$smsCode."，请您尽快验证修改密码。验证码有效时间为".EX_TIME."分钟。";
				break;
			# 更换手机号
			case 4:
				$content = "【".APP_SMS_NAME."】"."您好，您此次验证码为".$smsCode."，请您尽快更改手机号。验证码有效时间为".EX_TIME."分钟。";
				break;
			# 支付密码
			case 5:
				$content = "【".APP_SMS_NAME."】"."您好，您此次验证码为".$smsCode."，请您尽快验证修改支付密码。验证码有效时间为".EX_TIME."分钟。";
				break;
            # 绑定手机号
            case 6:
                $content = "【".APP_SMS_NAME."】"."您好，您此次验证码为".$smsCode."，请您尽快绑定手机号。验证码有效时间为".EX_TIME."分钟。";
                break;
			default:
				self::StatusCode(210, "暂无该类型短信验证!");
				break;
		}
		// $target = "http://dc.28inter.com/sms.aspx";
		// //替换成自己的测试账号,参数顺序和wenservice对应
		// $post_data = "action=send&userid=&account=myapcr&password=my2015hter&mobile=$phone&sendTime=&content=".rawurlencode("$content");
		// //$binarydata = pack("A", $post_data);
		// $gets = self::Post($post_data, $target);
		// $start = strpos($gets,"<?xml");
		// $data = substr($gets,$start);
		// $xml = simplexml_load_string($data);
		// var_dump(json_decode(json_encode($xml),TRUE));
		//请自己解析$gets字符串并实现自己的逻辑
		//<State>0</State>表示成功,其它的参考文档
		$post_data = array();
		$post_data['userid'] = SMS_ID;
		$post_data['account'] = SMS_ACCOUNT;
		$post_data['password'] = SMS_PWD;
		$post_data['mobile'] = "$phone";
		$sendTime = date("Y-m-d H:i:s");
		$post_data['sendtime'] ='';//sendTime;

		//中文需要转换为UTF8编码格式提交
		$post_data['content'] = $content;//iconv('GB2312', 'UTF-8', '$content');
		$url='http://dc.28inter.com/sms.aspx?action=send';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
		$result = curl_exec($ch);
		//$data = explode(" ",$result);
		$data = (array)simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
		if($data['returnstatus'] == "Success" && $data['message'] == "ok"){
			$microtime = strtotime($sendTime);
			$sms = array(
				'phone' => $phone,
				'type' => $type,
				'sms_code' => $smsCode,
				'send_time' => $microtime,
				'expire_time' => ($microtime + (EX_TIME * 60))
				);
			return $sms;
		}else{
			self::StatusCode(210, $data['message']);
		}
	}

	/**
	 * [verifySMS 校验短信]
	 * @param [string] [手机号码]
	 * @param [string] [手机验证码]
	 * 
	 * @return [type] [返回验证信息]
	 */
	public function verifySMS($toPhone, $smsCode, $type)
	{
		$arr = $this->CI->session->tempdata();
		log_message('error',"校验短信:".json_encode($arr));
		$phone = $this->CI->session->tempdata('phone'); #接受短信的手机号
		$useMethod = $this->CI->session->tempdata('type'); #短信用途
		$sms = $this->CI->session->tempdata('sms_code'); #验证码
		$time = $this->CI->session->tempdata('expire_time'); #过期时间
		if($toPhone == $phone && $smsCode == $sms && time() <= $time && $type == $useMethod){
			return true;
		}else{
			return false;
		}
	}
}
