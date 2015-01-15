<?php
return array(
    'SHOW_PAGE_TRACE' =>false,//是否开启调试
    'SESSION_AUTO_START' => 1,
    'SITE_DOMAIN' => 'http://woniu.com', //设置网站域名
    'SITE_NAME' => 'WoniuCMS', //设置站点名
    'MODULE_ALLOW_LIST' => array('Home', 'Admin'), // 配置分组列表
    'DEFAULT_MODULE' => 'Home', // 配置默认分组
    'TMPL_DETECT_THEME' => true, //是否开启主题模式
    'DB_PARAMS' =>array(\PDO::ATTR_CASE =>\ PDO::CASE_NATURAL),
    'DB_TYPE' => 'mysql', //数据库类型
    'DB_HOST' => '127.0.0.1', //数据库地址
    'DB_PORT' => '3306', //数据库端口
    'DB_PREFIX' => 'woniu_', //数据库前缀
    'DB_USER' => 'root', //数据库用户
    'DB_PWD' => 'root', //数据库密码
    'DB_NAME' => 'woniucms', //数据库名
    'URL_HTML_SUFFIX' => 'html|php|png|jpg', //伪静态后缀
    'URL_CASE_INSENSITIVE' => true, //URL不区分大小写
    'SHOW_PAGE_TRACE' => true,
    'URL_ROUTER_ON' => true, //开启路由
    'URL_ROUTE_RULES' =>
        array(
            'login' => array('/login.php')
        ),
    'AUTH_CONFIG' => array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => 'woniu_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'woniu_auth_group_access', //用户组明细表
        'AUTH_RULE' => 'woniu_auth_rule', //权限规则表
        'AUTH_USER' => 'woniu_member' //用户信息表
    ),
    'TMPL_PARSE_STRING' => array(
        '__UPLOAD__' => '/Uploads',
    ),
    'FILE_UPLOAD_CONFIG' => array(
        'saveName' => time().'_'.mt_rand(),
        'rootPath' => './Uploads/', // 设置附件上传根目录
        'savePath' => '/files/', //保存路径
        'allowFiles' => array('.rar', '.doc', '.docx', '.zip', '.pdf', '.txt', '.swf', '.wmv'), //文件允许格式
        'maxSize' => 10*1024*1024, //文件大小限制，单位KB
        'subName' => array('date', 'Ymd')
    ),
    'IMAGE_UPLOAD_CONFIG' => array(
        'saveName' => time().'_'.mt_rand(),
        'rootPath' => './Uploads/', // 设置附件上传根目录
        'savePath' => '/images/', //保存路径
        'exts' => array('jpg', 'gif', 'png', 'jpeg'),
        'maxSize' => 2*1024*1024,
        'subName' => array('date', 'Ymd')
    )
)
?>