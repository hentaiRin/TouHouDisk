<html>
    <head>
        <style>
            *{

            }
        </style>
    </head>
    <body>
        <ul>
            <?php foreach($list as $k => $v){?>
            <li><?=base_url($v)?></li>
            <?php }?>
        </ul>
    </body>
</html>
