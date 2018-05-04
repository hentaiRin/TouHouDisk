<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('common');
        $this->load->helper('url');
    }

    public function upload(){
        header('Access-Control-Allow-Origin:*');
        $data = $this->common->PostParamsToArray();
        $this->load->library('fileupload');
        if(isset($data['file']['file']['size']) && intval($data['file']['file']['size']) > 0){

            # 判断图片尺寸
            if(intval($data['file']['file']['size']) > 1024*1024*5)
            {
                $this->common->statusCode(210, "上传的图片大小不能超过5M");
            }

            # 图片上传
            $img =$this->fileupload->picture_upload('file','news_img', $data, (rand(0,9).rand(0,9)));
            if($img){
                $data['file'] = $img;
            }else{
                #上传失败
                $this->common->statusCode(210, "上传失败");
            }
        }else{
            $this->common->statusCode(210, '请上传图片');
        }
        $this->load->driver('cache');
        $list = $this->cache->file->get('img_list');
        if(empty($list)){
            $list = array();
        }
        $this->cache->file->save('img_list', array(array_merge($list, array($data['file']))), 86400*365);
        $this->common->statusCode(200, '上传成功');
    }

    public function img_list(){
        $this->load->driver('cache');
        $list['list'] = $this->cache->file->get('img_list');
        if(empty($list['list'])){
            $list['list'] = array();
        }
        $this->load->view('img_list', $list);
    }

    public function show(){
        $front = $this->input->get();
        $this->load->library('create_image');
        //$this->create_image->show_img('assets/index.jpg', $front['x'], $front['y']);
        $this->create_image->show('assets/index.jpg');
    }

    public function get_pic_list(){
        $this->load->library('create_image');
        $img['pic'] = $this->create_image->do_img('assets'.DIRECTORY_SEPARATOR.'index.jpg');
        $img['res'] = 'assets'.DIRECTORY_SEPARATOR.'index.jpg';
        $this->common->statusCode(200, '', '', $img);
    }

    public function get_pic_url(){
        $img['url'] = base_url('assets'.DIRECTORY_SEPARATOR.'index.jpg');
        $this->common->statusCode(200, '', '', $img);
    }

    public function img(){
        $front['w'] = (int)$this->input->get('w');
        $front['h'] = (int)$this->input->get('h');
        $front['i'] = $this->input->get('i');
        $this->load->library('create_image');
        if($front['i'] == 1){
            $file = 'assets'.DIRECTORY_SEPARATOR.'index.jpg';
        }else{
            $file = 'assets'.DIRECTORY_SEPARATOR.'58431818_p0.jpg';
        }
        $this->create_image->show_original($file, $front['w'], $front['h']);
    }
}