<?php
class File_list
{

    public $file_list;
    public $charset;
    public $change_charset;
    public $CI;
    public function __construct(){
        $this->charset = 'utf8';
        $this->change_charset = false;
        $this->CI = & get_instance();
    }

    /**
     * 输出目录下的所有文件
     * @param $dir
     * @param array $file_type
     * @return array
     */
    public function file_open_list($dir, $file_type = array('jpg', 'jpeg', 'png', 'bmg', 'gif')){
        return $this->for_array($this->do_open($dir, $file_type));
    }

    /**
     * 递归打开文件夹
     * @param $dir
     * @param $file_type
     * @return array
     */
    private function do_open($dir, $file_type){
        $files = array();
        $ext_array = $file_type;
        if(@$handle = opendir($dir)) { //注意这里要加一个@，不然会有warning错误提示：）
            while(($file = readdir($handle)) !== false) {
                if($file != ".." && $file != ".") { //排除根目录；
                    if(is_dir($dir.DIRECTORY_SEPARATOR.$file)) { //如果是子文件夹，就进行递归
                        $temp = $this->do_open($dir.DIRECTORY_SEPARATOR.$file, $file_type);
                        if(!empty($temp)){
                            $files[] = $this->for_array($temp);
                        }
                    } else { //不然就将文件的名字存入数组；
                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                        if(in_array($ext, $ext_array)){
                            //$dir=char_to_utf8($dir);
                            //$dir=urlencode($dir);
                            if($this->change_charset){
                                $files[] = $this->char_to_utf8($dir).DIRECTORY_SEPARATOR.$this->char_to_utf8($file);
                            }else{
                                $files[] = $dir.DIRECTORY_SEPARATOR.$file;
                            }

                        }
                    }
                }
            }
            closedir($handle);
            return $files;
        }
    }

    /**
     * 转换字符集
     * @param $data
     * @return mixed|string
     */
    protected function char_to_utf8($data){
        if( !empty($data) ){
            $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5'));
            $data = mb_convert_encoding($data , $this->change_charset, $fileType);
        }
        return $data;
    }

    /**
     * 转一维数组
     * @param $multi
     * @return array
     */
    public function for_array($multi){
        $arr = array();
        foreach ($multi as $key => $val) {
            if( is_array($val) ) {
                $arr = array_merge($arr, $this->for_array($val));
            } else {
                $arr[] = $val;
            }
        }
        return $arr;
    }

    public function  open_dir($dir, $path, $ext_array = array('jpg', 'jpeg', 'png', 'gif', 'bmp')){
        $bin_path = 'uploads'.DIRECTORY_SEPARATOR.'link'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR;
        $command_file = 'mklink "';
        $this->CI->load->model('file_model');
        $ext = '';
        $md5 = md5($path.$dir.DIRECTORY_SEPARATOR);
        $name = $md5;
        $type = 1;
        $real_name = $this->char_to_utf8($dir);
        $data = array(
            'file_type' => $type,
            'filename' => $name,
            'ext' => $ext,
            'real_name' => $real_name,
            'parent_path' => $this->CI->file_model->get_parent(md5($path)),
            'create_time' => time()
        );
        $this->CI->file_model->file_insert($data);
        if(@$handle = opendir($path.$dir)) { //注意这里要加一个@，不然会有warning错误提示：）
            while(($file = readdir($handle)) !== false) {
                if($file != ".." && $file != ".") { //排除根目录；
                    if(is_dir($path.$dir.DIRECTORY_SEPARATOR.$file)) { //如果是子文件夹，就进行递归
                        $this->open_dir($file, $path.$dir.DIRECTORY_SEPARATOR);
                        /*$md5 = md5($path.$dir.DIRECTORY_SEPARATOR.$file);
                        $files[] = array(
                            'path' => $path.$dir.DIRECTORY_SEPARATOR.$file,
                            'md5' => $md5,
                            'name' => $this->char_to_utf8($file),
                            'type' => 1
                        );*/
                    } else { //不然就将文件的名字存入数组；
                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                        if(in_array($ext, $ext_array)){
                            $md5 = md5_file($path.$dir.DIRECTORY_SEPARATOR.$file);
                            if(!file_exists($bin_path.$md5.'.'.$ext)){
                                $command = $command_file.FCPATH.$bin_path.$md5.'.'.$ext.'" "'.FCPATH.$path.$dir.DIRECTORY_SEPARATOR.$file.'"';
                                $output = exec($command);
                            }
                            $name = $md5;
                            $type = 2;
                            $real_name = $this->char_to_utf8($file);
                            $data = array(
                                'file_type' => $type,
                                'filename' => $name,
                                'ext' => $ext,
                                'real_name' => $real_name,
                                'parent_path' => $this->CI->file_model->get_parent(md5($path.$dir.DIRECTORY_SEPARATOR)),
                                'create_time' => time()
                            );
                            $this->CI->file_model->file_insert($data);
                        }
                    }
                }
            }
            closedir($handle);
        }
        return true;
    }
}