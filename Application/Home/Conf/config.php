<?php
return array(
    'URL_MODEL' => 2,
    'VIEW_PATH' => './Theme/',
    'DEFAULT_THEME' => 'bootstrap',
    'URL_ROUTE_RULES' => array(
        'Article/:router' => array('Article/read'),
        'Channel/:router' => array('Channel/index'),
        'search' => array('Public/search'),
        'login' => array('User/login'),
        'reg' => array('User/reg'),
    ),
)
?>