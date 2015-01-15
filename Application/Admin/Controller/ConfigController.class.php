<?php
namespace Admin\Controller;

use Think\Controller;

class ConfigController extends AdminController
{
    public function index()
    {
        $config = M('site_config')->select();
        //dump($config);
        $this->assign('confg', $config);
        $this->display();
    }

    public function api()
    {
        $this->display();
    }

    public function theme()
    {
        $this->display();
    }

    function menu()
    {
        $Menu = M("menu");
        $this->display();
    }
    function database($type=null)
    {
        A('Database/index');
    }
}