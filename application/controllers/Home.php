<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends MY_Controller{
    protected $download_path;
    protected $create_type;
    public function __construct()
    {
        parent::__construct();
        $this->download_path = "D:\\Downloads".DIRECTORY_SEPARATOR;
        $this->create_type = array('diyidan', 'bcy');
    }

    /*加载主页*/
    public function index()
    {

    }

    public function do_ajax(){
        header('Access-Control-Allow-Origin:*');
        $this->common->StatusCode(210, '访问失败');
    }



    /*退出登录清除session 、cookie*/
    public function log_out()
    {

    }

    /*public function download(){
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
    }*/


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

    public function download(){
        set_time_limit(180);
        $front = $this->input->post();
        $arr = array('refer', 'url', 'type', 'coser', 'title', 'name');
        $this->check_params($arr, $front);
        if(!in_array($front['type'], $this->create_type)){
            $this->common->StatusCode(210, '站点不正确');
        }
        $front['title'] = $this->strFilter($front['title']);
        $fileType = mb_detect_encoding($front['title'] , array('UTF-8','GBK','LATIN1','BIG5'));
        $front['title'] = mb_convert_encoding($front['title'] , 'GBK', $fileType);
        $fileType = mb_detect_encoding($front['coser'] , array('UTF-8','GBK','LATIN1','BIG5'));
        $front['coser'] = mb_convert_encoding($front['coser'] , 'GBK', $fileType);
        $option = array(
            'http' => array(
                'header' => 'Referer:'.$front['refer']
            )
        );
        $handle = file_get_contents($front['url'], false, stream_context_create($option));
        if($handle){
            $data = array(
                'url' => base_url('uploads/cache/'.$front['name']),
                'name' => $front['name']
            );
            $data = array();
            $path = $this->download_path.$front['type'].DIRECTORY_SEPARATOR.$front['coser'].DIRECTORY_SEPARATOR.$front['title'].DIRECTORY_SEPARATOR;
            if(!file_exists($path)){
                @mkdir($path, 0777, true);
            }
            $res = file_put_contents($path.$front['name'], $handle);
            if($res){
                $this->common->StatusCode(200, '', '', $data);
            }else{
                $this->common->StatusCode(210, '操作失败');
            }
        }else{
            $this->common->StatusCode(210, '操作失败');
        }
    }

    private function strFilter($str){
        $str = str_replace('`', '', $str);
        $str = str_replace('·', '', $str);
        $str = str_replace('~', '', $str);
        $str = str_replace('!', '', $str);
        $str = str_replace('@', '', $str);
        $str = str_replace('#', '', $str);
        $str = str_replace('$', '', $str);
        $str = str_replace('￥', '', $str);
        $str = str_replace('%', '', $str);
        $str = str_replace('^', '', $str);
        $str = str_replace('……', '', $str);
        $str = str_replace('&', '', $str);
        $str = str_replace('*', '', $str);
        $str = str_replace('(', '', $str);
        $str = str_replace(')', '', $str);
        $str = str_replace('（', '', $str);
        $str = str_replace('）', '', $str);
        $str = str_replace('-', '', $str);
        $str = str_replace('_', '', $str);
        $str = str_replace('——', '', $str);
        $str = str_replace('+', '', $str);
        $str = str_replace('=', '', $str);
        $str = str_replace('|', '', $str);
        $str = str_replace('\\', '', $str);
        $str = str_replace(';', '', $str);
        $str = str_replace('；', '', $str);
        $str = str_replace(':', '', $str);
        $str = str_replace('：', '', $str);
        $str = str_replace('\'', '', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace(',', '', $str);
        $str = str_replace('<', '', $str);
        $str = str_replace('>', '', $str);
        $str = str_replace('.', '', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace('、', '', $str);
        $str = str_replace('?', '', $str);
        return trim($str);
    }

}