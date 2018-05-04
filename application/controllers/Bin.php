<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bin extends MY_Controller{


    public $origin;
    public function __construct()
    {
        parent::__construct();
        $this->origin = 'original';
        $this->load->helper('url');
    }

    /**
     * 索引图片处理
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $file_p = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $origin_file = 'assets/'.$this->origin.'/'.$file_p[1];
        if(file_exists($origin_file)){
            $this->load->library('create_image');
            $this->create_image->config['quality'] = 100;
            $query = $this->create_image->zoom($file_p[1], $file_p[0]);
            $mew_img = $_SERVER['REQUEST_URI'];
        }else{
            $query = false;
            $mew_img = '';
        }
        // 判断是否处理成功
        if($query === TRUE)
        {
            redirect($mew_img);
        }
        else
        {
            show_404();
        }
    }



    /*退出登录清除session 、cookie*/
    public function log_out()
    {

    }

    public function download(){
        $size = 1024;
        $flag = 0;
        for(;;){
            $temp = file_get_contents('assets/index.jpg', false, null, $size * $flag, $size);
            if(!empty($temp)){
                file_put_contents('assets/news_img/cache_'.$flag, $temp);
                $flag++;
            }else{
                break;
            }
        }
    }


    public function copy(){
        $flag = 0;
        $file = fopen('assets/news_img/index.jpg', 'ab');
        for(;;){
            if(file_exists('assets/news_img/cache_'.$flag)){
                fwrite($file, file_get_contents('assets/news_img/cache_'.$flag));
                $flag++;
            }else{
                break;
            }
        }
    }

    public function file(){
        $this->load->library('file_list');
        $arr = $this->file_list->file_open_list('F:\Pictures');
        $front['w'] = (int)$this->input->get('w');
        $front['h'] = (int)$this->input->get('h');
        $front['i'] = (int)$this->input->get('i');
        $this->load->library('create_image');
        if(empty($arr) || !isset($arr[$front['i']])){
            $file = FCPATH.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'index.jpg';
        }else{
            $file = $arr[$front['i']];
        }
        $this->create_image->show_original($file, $front['w'], $front['h']);
    }

}