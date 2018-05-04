<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>图片列表</title>
        <style>
            *{
                margin: 0;
                padding: 0;
            }
            .main{
                width: 100%;
            }
            .last-page{
                margin-top: 50px;
                width: 100%;
                height: 35px;
                line-height: 35px;
                text-align: left;
                font-size: 30px;
                color:#009f95;
            }
            .last-page a{
                margin-left: 15px;
            }
            .content{
                width: 100%;
                margin-top: 50px;
            }
            .page{
                width: 100%;
                height: 50px;
                margin-top: 10px;
            }
            .div-row{
                float: left;
                width: 25%;
                text-align: center;
                margin-left: 5%;
            }
            .title{
                text-align: center;
                line-height: 25px;
                font-size: 18px;
                height: 25px;
            }
            .row_content{
                margin-top: 5px;
            }
        </style>
    </head>
    <body>
        <div class="main">
            <div class="last-page">
                <a href="<?=base_url('run/url_code/1?pid='.$pid)?>">返回上层</a>
            </div>
            <div class="content">
            <?php foreach($data as $k => $v){?>
                <div class="div-row">
                    <div class="title"><?=$v['real_name']?></div>
                    <div class="row_content">
                <?php if($v['file_type'] == 2){?>
                <a target="_balnk" href="<?=base_url($base_path.$v['filename'].'.'.$v['ext'])?>">
                    <img src="<?=base_url($base_path.$v['filename'].'.'.$v['ext'])?>" alt="" height="280">
                </a>

                <?php }else{?>
                    <a href="<?=base_url('run/url_code/1?pid='.$v['id'])?>">
                        <img src="<?=base_url('original/dir.jpg')?>" alt="" height="280">
                    </a>
                <?php }?>
                    </div>
                </div>
            <?php }?>
            </div>
            <div class="page">

            </div>
        </div>
    </body>
</html>
