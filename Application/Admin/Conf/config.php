<?php
return array(
    'SHOW_PAGE_TRACE' =>false,//是否开启调试
    'TMPL_SWITCH_ON' => true,
    'URL_CASE_INSENSITIVE'  =>  0,   // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL' =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    //'TMPL_DETECT_THEME' => true,
    //'DEFAULT_THEME' => 'default',
    'URL_ROUTE_RULES' =>
        array(
            'login' => array('Auth/login')
        ),
    'DATA_BACKUP_PATH'=>'Data/',
    'DATA_BACKUP_PART_SIZE'=>10*1024*1024,
    'DATA_BACKUP_COMPRESS_LEVEL'=>9,
    'DATA_BACKUP_COMPRESS'=>1,
)
?>
