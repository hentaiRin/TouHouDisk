<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create_image
{
    public $config;
    private $_image_size_param;
    public function __construct($config = array())
    {
        $this->_init($config);
        # code...
    }

    public function do_img($file, $w = 4, $h = 3){
        $mine = getimagesize($file);
        $function1 = $this->get_function_name($mine['mime']);
        //var_dump($function1);
        $function2 = $this->create_function_name($mine['mime']);
        $img = $function1($file);
        $size[0] = imagesx($img);
        $size[1] = imagesy($img);
        if($size[0] >= $size[1]){
            $width = $size[0]/$w;
            $height = $size[1]/$h;
        }else{
            $width = $size[0]/$h;
            $height = $size[1]/$w;
        }
        $img_arr = array();
        for($i=0;$i<$w;$i++){
            for($j=0; $j < $h; $j++){
                //$temp_img = imagecreatetruecolor($width, $height);
                //imagesavealpha($temp_img, true);
                //imagecopy($temp_img, $img, 0, 0, $width * $i, $height * $j, $width, $height);
                //$filename = md5_file($file).'_'.$i.$j.'.'.$this->ext($mine['mime']);
                $img_arr[$i][$j] = array($i, $j);
                //imagejpeg($temp_img, $filename);
                /*header('Content-type:image/png');
                imagepng($temp_img);*/
                //imagedestroy($temp_img);
            }
        }
        return $img_arr;
       /* clone(){
            var self = this;
            //     //加载动态资源
            cc.loader.loadRes("img1",cc.SpriteFrame,function(err,spriteFrame){
                var texture = spriteFrame.getTexture();
                for(var i=0;i<4;i++){
                    imgArr2[i] = [];
                imgArray2[i] = [];
                for(var j=0;j<3;j++){
                        //碎片放入imgArray二维数组中
                        imgArray2[i][j] = new cc.SpriteFrame(texture,cc.rect(210*j,240*i,210,240)) ;
                    var blocknode = cc.instantiate(self.blockPrefab);
                    blocknode.position = cc.p(210*j,-240*i-240);
                    blocknode.opacity = 100;
                    imgArr2[i][j] = blocknode;
                    let block = blocknode.getComponent("Block");
                    block.imgSp.spriteFrame = imgArray2[i][j];
                    block.initBlock(imgArray2[i][j],self.puIndex2++,cc.v2(j,i));
                    self.gameMap.addChild(blocknode);
                }
            }

        })
    },*/

    }

    /**
     * 显示图片
     * @param $file
     * @param $x
     * @param $y
     * @param int $w
     * @param int $h
     */
    /*public function show_img($file, $x, $y, $w = 4, $h = 3){
        $mine = getimagesize($file);
        $function1 = $this->get_function_name($mine['mime']);
        $img = $function1($file);
        $size[0] = imagesx($img);
        $size[1] = imagesy($img);
        if($size[0] >= $size[1]){
            $width = $size[0]/$w;
            $height = $size[1]/$h;
        }else{
            $width = $size[0]/$h;
            $height = $size[1]/$w;
        }
        $temp_img = imagecreatetruecolor($width, $height);
        imagesavealpha($temp_img, true);
        imagecopy($temp_img, $img, 0, 0, $width * $x, $height * $y, $width, $height);
        header('Access-Control-Allow-Origin:*');
        header('Content-type:image/png');
        imagepng($temp_img);
        imagedestroy($temp_img);
    }*/

    public function show($file, $w = 4, $h = 3){
        $mine = getimagesize($file);
        $function1 = $this->get_function_name($mine['mime']);
        $img = $function1($file);
        $size[0] = imagesx($img);
        $size[1] = imagesy($img);
        if($size[0] >= $size[1]){
            $width = $size[0]/$w;
            $height = $size[1]/$h;
        }else{
            $width = $size[0]/$h;
            $height = $size[1]/$w;
        }
        $temp_img = imagecreatetruecolor($width, $height);
        imagesavealpha($temp_img, true);
        $col_ellipse = imagecolorallocate($temp_img, 22, 150, 255);
        imagefilledellipse($temp_img, $width/2, $height/2, $width, $height, $col_ellipse);
        header('Access-Control-Allow-Origin:*');
        header('Content-type:image/png');
        imagepng($temp_img);
        imagedestroy($temp_img);
    }

    public function show_original($file, $w = 0, $h = 0){
        $mine = getimagesize($file);
        $width = $mine[0];//获取宽度
        $height = $mine[1];//获取高度
        //计算缩放比例
        if($w == 0 && $h == 0){
            $scale = 1;
        }else{
            if($w == 0 && $h != 0){
                $scale = $h/$height;
            }else if($w != 0 && $h == 0){
                $scale = $w/$width;
            }else{
                $scale = ($w/$width)>($h/$height)?$h/$height:$w/$width;
            }
        }
        //计算缩放后的尺寸
        $sWidth = floor($width*$scale);
        $sHeight = floor($height*$scale);
        $function1 = $this->get_function_name($mine['mime']);
        $img = $function1($file);
        //创建目标图像资源
        $nim = imagecreatetruecolor($sWidth,$sHeight);
        //等比缩放
        imagecopyresampled($nim,$img,0,0,0,0,$sWidth,$sHeight,$width,$height);
        header('Access-Control-Allow-Origin:*');
        header('Content-type:image/png');
        imagepng($nim);
        imagedestroy($img);
        imagedestroy($nim);
    }

    /**
     * 缩放图片
     * @param $file
     * @param $type
     */
    private function _do_zoom($file, $type){
        $base_file = 'uploads/original/'.$file;
        $mine = getimagesize($base_file);
        $width = $mine[0];//获取宽度
        $height = $mine[1];//获取高度
        //计算缩放比例
        if($mine[0] >= $mine[1]){
            $scale = $this->_image_size_param[$type]/$mine[0];
        }else{
            $scale = $this->_image_size_param[$type]/$mine[1];
        }
        //计算缩放后的尺寸
        $sWidth = floor($width*$scale);
        $sHeight = floor($height*$scale);
        $function1 = $this->get_function_name($mine['mime']);
        $function2 = $this->create_function_name($mine['mime']);
        $img = $function1($base_file);
        //创建目标图像资源
        $nim = imagecreatetruecolor($sWidth,$sHeight);
        //等比缩放
        imagecopyresampled($nim,$img,0,0,0,0,$sWidth,$sHeight,$width,$height);
        if($mine[2] == 2){
            $query = $function2($nim, 'uploads/'.$type.'/'.$file, $this->config['quality']);
        }else{
            $query = $function2($nim, 'uploads/'.$type.'/'.$file);
        }
        imagedestroy($img);
        imagedestroy($nim);
        return $query;
    }

    /**
     * 仅复制
     * @param $file
     * @param $type
     * @return bool
     */
    private function _do_copy($file, $type){
        $path = 'uploads/';
        return copy($path.'original/'.$file, $path.$type.'/'.$file);
    }

    private function _zoom($file, $type){
        $path = 'uploads/';
        $mine = getimagesize($path.'original/'.$file);
        $check_length = $mine[0] >= $mine[1]? $mine[0] : $mine[1];
        if($check_length > 0 && $check_length < $this->_image_size_param['little']){
            return '_do_copy';
        }else if($check_length > $this->_image_size_param['little'] && $check_length < $this->_image_size_param['small']){
            if($type == 'little'){
                return '_do_zoom';
            }else{
                return '_do_copy';
            }
        }else if($check_length > $this->_image_size_param['small'] && $check_length < $this->_image_size_param['normal']){
            if($type == 'little' || $type == 'small'){
                return '_do_zoom';
            }else{
                return '_do_copy';
            }
        }
        else if($check_length > $this->_image_size_param['normal'] && $check_length < $this->_image_size_param['big']){
            if($type == 'little' || $type == 'small' || $type == 'normal'){
                return '_do_zoom';
            }else{
                return '_do_copy';
            }
        }
        else if($check_length > $this->_image_size_param['big'] && $check_length < $this->_image_size_param['large']){
            if($type == 'little' || $type == 'small' || $type == 'normal' || $type == 'big'){
                return '_do_zoom';
            }else{
                return '_do_copy';
            }
        }
        else if($check_length > $this->_image_size_param['large']){
            return '_do_zoom';
        }
        else{
            return false;
        }
    }

    /**
     * 缩放
     * @param $file
     * @param $type
     * @return bool
     */
    public function zoom($file, $type){
        $query = false;
        if(isset($this->_image_size_param[$type])){
            $function = $this->_zoom($file, $type);
            if($function){
                $query = $this->$function($file, $type);
            }
        }
        return $query;
    }

    /**
     * 获取方法名字
     * @param $type
     * @return bool|string
     */
    private function get_function_name($type){
        switch($type){
            case 'image/png':
                return 'imagecreatefrompng';
                break;
            case 'image/jpeg':
            case 'image/jpg':
                return 'imagecreatefromjpeg';
                break;
            case 'image/gif':
                return 'imagecreatefromgif';
                break;
            default:
                exit();
                break;
        }
    }

    /**
     * @param $type
     * @return bool|string
     */
    private function create_function_name($type){
        switch($type){
            case 'image/png':
                return 'imagepng';
                break;
            case 'image/jpeg':
            case 'image/jpg':
                return 'imagejpeg';
                break;
            case 'image/gif':
                return 'imagegif';
                break;
            default:
                exit();
                break;
        }
    }

    /**
     * @param $type
     * @return bool|string
     */
    private function ext($type){
        switch($type){
            case 'image/png':
                return 'png';
                break;
            case 'image/jpeg':
            case 'image/jpg':
                return 'jpg';
                break;
            case 'image/gif':
                return 'gif';
                break;
            default:
                exit();
                break;
        }
    }

    /**
     * 参数设置
     * @param $config
     */
    private function _init($config){
        $this->_image_size_param = array(
            'little'=> 120,
            'small' => 320,
            'normal'=> 690,
            'big'   => 1024,
            'large' => 1920,
            'original' => 0
        );
        $auto_config = array(
            'quality' => 80
        );
        $this->config = array_merge($auto_config, $config);
    }
}