<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 欢迎界面
 */
class Run extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		echo 'Happy Helloween!!';
	}

	public function charset(){
		header("Content-type: text/html; charset=gb2312");
		echo PHP_OS;
		$command = "chcp";
		$output = array();
		//echo exec($command, $output);
		echo exec($command);
		var_dump($output);
	}

	public function url_code(){
		$pid = $this->input->get('pid');
		if(empty($pid)){
			$pid = 0;
		}
		$uri = trim($_SERVER['REQUEST_URI'], '/');
		$uri = explode('/', $uri);
		if(isset($uri[3])){
			$page_size = (int)$uri[3];
			if($page_size <= 0){
				$page_size = 300;
			}
		}else{
			$page_size = 300;
		}
		if(isset($uri[2])){
			$page = (int)$uri[2];
			if($page <= 0){
				$page = 1;
			}
		}else{
			$page = 1;
		}
		$offset = ($page - 1) * $page_size;
		$this->load->model('file_model');
		$data = $this->file_model->get_list(array('parent_path' => $pid), $offset, $page_size);
		$file = $this->file_model->get_file($pid);
		if(empty($file)){
			$data['pid'] = 0;
		}else{
			$data['pid'] = $file['parent_path'];
		}
		$data['page'] = $page;
		$data['base_path'] = 'uploads/link/cache/';
		$this->load->view('img_list', $data);
	}

	public function create_md5_url(){
		exit('功能已停用');
		set_time_limit(120);
		//$path = 'uploads'.DIRECTORY_SEPARATOR.'link'.DIRECTORY_SEPARATOR.'picture'.DIRECTORY_SEPARATOR;
		$path = 'uploads'.DIRECTORY_SEPARATOR.'link'.DIRECTORY_SEPARATOR;
		$this->load->library('file_list');
		$arr = $this->file_list->open_dir('picture', $path);
	}
}
