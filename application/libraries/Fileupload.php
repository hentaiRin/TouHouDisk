<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @func   文件上传类 
 * 
 * @authors Victor Koo (fairyeye@live.cn)
 * @date    2016-10-13 17:12:40
 * @version Ver
 *
 * @company http://www.scbbc.cn
 */
class Fileupload{

	/**
	 * [pic_upload 头像上传类]
	 * @param  [type]  $arr  [description]
	 * @param  integer $mode [description]
	 * @return [type]        [description]
	 * 
	  "name": "cngeeker_minilogo.png",
	  "type": "image/png",
	  "tmp_name": "/private/var/tmp/php8NsnBe",
	  "error": "0",
	  "size": "5295"
	 * 
	 */
	public function avatar_upload($data, $mode = 1)
	{
		$common = new Common();
		if(isset($data['file'])){
	      	#检查大小
	      	if($data['file']['avatar']['size'] > APP_DEFAULT_SIZE){
	        	$common->StatusCode(210, "上传的图片不能大于".(APP_DEFAULT_SIZE/1024/1024)."Mb!");
	        	exit;
	      	}
	      	#检查文件类型
	      	$ext = strtolower(trim(substr(strrchr($data['file']['avatar']['name'], '.'), 1)));
	      	$allowed = array('jpg', 'png', 'gif', 'jpeg');
	      	$isin = in_array($ext, $allowed);
	      	if($isin){
	      		$name = 'avatar_'.time().".".$ext;
	      		$dir = FCPATH.'assets/avatar/'.$name;
	      		if(move_uploaded_file($data['file']['avatar']['tmp_name'], $dir)) 
			    {  
			        return $name; 
			    }  
			    else  
			    {  
			        //$data = json_encode($_FILES);  
			        //echo $data;
			        return false;  
			    }  
	      	}else{
	      		$common->StatusCode(210, "不支持该类型的图片!");
	      		exit;
	      	}
	    }else{
	    	return false;
	    }
	}

	/**
	 * [file_upload 图片上传类]
	 * @param  [type]  $arr  [description]
	 * @param  integer $mode [description]
	 * @return [type]        [description]
	 */
	public function pic_upload($cname, $tag, $data, $mode = 1)
	{
		$common = new Common();
		if(isset($data['file'][$cname])){
	      	#检查大小
	      	if($data['file'][$cname]['size'] > APP_DEFAULT_SIZE){
	        	$common->StatusCode(210, "上传的图片不能大于".(APP_DEFAULT_SIZE/1024/1024)."Mb!");
	        	exit;
	      	}
	      	#检查文件类型
	      	$ext = strtolower(trim(substr(strrchr($data['file'][$cname]['name'], '.'), 1)));
	      	$allowed = array('jpg', 'png', 'gif', 'jpeg');
	      	$isin = in_array($ext, $allowed);
	      	if($isin){
	      		$name = $tag.'_'.time().".".$ext;
	      		$dir = FCPATH.'assets/gallery/'.$tag.'/'.$name;
	      		if(move_uploaded_file($data['file'][$cname]['tmp_name'], $dir)) 
			    {  
			        return 'assets/gallery/'.$tag.'/'.$name; 
			    }  
			    else  
			    {  
			        //$data = json_encode($_FILES);  
			        //echo $data;
			        return false;  
			    }  
	      	}else{
	      		$common->StatusCode(210, "不支持该类型的图片!");
	      		exit;
	      	}
	    }else{
	    	return false;
	    }
	}

	/**
	 * [qiniu_upload 七牛上传]
	 * @return [type] [description]
	 */
	private function qiniu_upload(){

	}

	/**
	 * 朋友圈文件上传
	 * @param $cname
	 * @param $tag
	 * @param $data
	 * @param $mid
	 * @return bool|string
	 */
	public function picture_upload($cname, $tag, $data, $mid)
	{
		$common = new Common();
		if(isset($data['file'][$cname])){
			#检查大小
			if($data['file'][$cname]['size'] > APP_DEFAULT_SIZE){
				$common->StatusCode(210, "上传的图片不能大于".(APP_DEFAULT_SIZE/1024/1024)."Mb!");
				exit;
			}
			#检查文件类型
			$ext = strtolower(trim(substr(strrchr($data['file'][$cname]['name'], '.'), 1)));
			$allowed = array('jpg', 'png', 'gif', 'jpeg');
			$isin = in_array($ext, $allowed);
			if($isin){
				$name = $cname.'_'.$mid.'_'.time().".".$ext;
				$dir = FCPATH.'assets/'.$tag.'/'.$name;
				if(!file_exists($dir)){
					@mkdir($dir, 0777, true);
				}
				if(move_uploaded_file($data['file'][$cname]['tmp_name'], $dir.$name))
				{
					return 'assets/'.$tag.'/'.$name;
				}
				else
				{
					//$data = json_encode($_FILES);
					//echo $data;
					return false;
				}
			}else{
				$common->StatusCode(210, "不支持该类型的图片!");
				exit;
			}
		}else{
			return false;
		}
	}


	/**
	 * 多文件上传{数组形式}
	 * @param $cname
	 * @param $tag
	 * @param $data
	 * @param int $mid [用户id
	 * @return array|bool
	 */
	public function more_upload($cname, $tag, $data, $mid = 9527)
	{
		$error = array();
		$info = array();
		if(isset($data['file'][$cname])){
			$file = $data['file'][$cname];
			if(is_array($file['name'])){
				for($i = 0; $i<count($file['name']); $i++){
					#检查大小
					if($file['size'][$i] > APP_DEFAULT_SIZE){
						$this->common->StatusCode(210, "上传的图片不能大于".(APP_DEFAULT_SIZE/1024/1024)."Mb!");
						exit;
					}
					#检查文件类型
					$ext = strtolower(trim(substr(strrchr($file['name'][$i], '.'), 1)));
					$allowed = array('jpg', 'png', 'gif', 'jpeg');
					$isin = in_array($ext, $allowed);
					if($isin){
						$name = $tag.$i.$mid."_".time().".".$ext;
						$dir = FCPATH.'assets/gallery/'.$tag.'/'.$name;
						if(move_uploaded_file($file['tmp_name'][$i], $dir))
						{
							$info[] = 'assets/gallery/'.$tag.'/'.$name;
							continue;
						}
						else
						{
							$error[] = "第".($i+1)."个文件上传出错";
							continue;
						}
					}else{
						$this->common->StatusCode(210, "不支持该类型的图片!");
						exit;
					}
				}
				return array(
					'name' => $info,
					'error' => $error
				);
			}else{
				self::pic_upload($cname, $tag, $data, 1);
			}
		}else{
			return false;
		}
	}

}

/* End of file Fileupload.php */
/* Location: ./application/controllers/Fileupload.php */