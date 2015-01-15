<?php
namespace Admin\Controller;

use Think\Controller;

class channelController extends AdminController
{
    function index()
    {
        $data = m('channel')->order('sort')->select();
        $channel = list_to_tree($data);
        $this->assign('channel', $channel);
        //   dump($channel);
        $this->display();
    }

    public function category()
    {
        $c = M('category')->where($map)->field('id,pid,title', 0)->order('sort')->select();
        $category = list_to_tree($c);
        $this->assign('category', $category);
        $this->display();
    }
}
