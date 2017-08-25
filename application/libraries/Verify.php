<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//验证码类
class Verify {
	private $CI;

	private $charset;//随机因子
	private $codelen;//验证码长度
	private $width;//宽度
	private $height;//高度
	private $fontsize;//指定字体大小
	private $font;//指定的字体
	private $fontcolor;//指定字体颜色
	private $code;//验证码
	private $code_value;//验证码值
	private $img;//图形资源句柄
	//构造方法初始化
	public function __construct($config = array()) {
		$this->CI = & get_instance();
		$this->init_config($config);
	}
	//生成随机码
	private function createCode() {
		$_len = strlen($this->charset)-1;
		for ($i=0;$i<$this->codelen;$i++) {
			$this->code .= $this->charset[mt_rand(0,$_len)];
		}
		$this->code_value = $this->code;
	}

	/**
	 * 算术验证码
	 */
	private function createFormula(){
		$a = rand(11, 100);
		$b = rand(0, 9);
		$c = rand(0, 1);
		switch($c){
			case 0:
				$this->code_value = $a + $b;
				$this->code = $a.' + '.$b.' = ?';
				break;
			default:
				$this->code_value = $a - $b;
				$this->code = $a.' - '.$b.' = ?';
				break;
		}
		$this->codelen = strlen($this->code);
	}
	//生成文字
	private function createFormulaFont() {
		$_x = $this->width / $this->codelen;
		for ($i=0;$i<$this->codelen;$i++) {
			$this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			imagettftext($this->img,$this->fontsize,0,$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
		}
	}
	//生成背景
	private function createBg() {
		$this->img = imagecreatetruecolor($this->width, $this->height);
		$color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
		imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
	}
	/**
	 * 生成文字
	 * @param int $angle 判断是否随机旋转角度（0-随机 其它-不随机）
	 */
	private function createFont($angle = 0) {
		$angle = $angle == 0 ? mt_rand(-30,30) : 0;
		$_x = $this->width / $this->codelen;
		for ($i=0;$i<$this->codelen;$i++) {
			$this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			imagettftext($this->img,$this->fontsize,$angle,$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
		}
	}
	//生成线条、雪花
	private function createLine() {
		//线条
		for ($i=0;$i<6;$i++) {
			$color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
		}
		//雪花
		for ($i=0;$i<100;$i++) {
			$color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
			imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
		}
	}
	//输出
	private function outPut() {
		header('Content-type:image/png');
		imagepng($this->img);
		imagedestroy($this->img);
	}

	//设置SESSION CI用
	private function createSession(){
		$this->CI->load->library('session'); # 加载session类
		$key = strtoupper(md5(VERIFY_KEY.date('Y')));
		$arr[$key] = $this->code_value;
		$this->CI->session->set_userdata( $arr );
	}
	//对外生成
	public function doimg($format = 0) {
		$this->createBg();
		switch($format){
			case 0:
				$this->createCode();
				break;
			default:
				$this->createFormula();
				break;
		}
		$this->createSession();
		$this->createLine();
		$this->createFont($format);
		$this->outPut();
	}
	//获取验证码
	/*public function getCode() {
		return strtolower($this->code);
	}*/

	/**
	 * 设置配置
	 * @param array $config 配置信息数组
	 */
	private function init_config($config=array()){
		$this->codelen = isset($config['length']) ? $config['length'] : 4;
		$this->charset = isset($config['charset']) ? $config['charset'] : 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
		$this->fontsize = isset($config['fontsize']) ? $config['fontsize'] : 20;
		$this->width = isset($config['width']) ? $config['width'] : ($this->codelen * $this->fontsize * 1.5 + $this->codelen * $this->fontsize / 2);
		$this->height = isset($config['height']) ? $config['height'] : ($this->fontsize * 2.5);
		$this->font = FCPATH.'assets/font/Verdana.ttf';

	}

}