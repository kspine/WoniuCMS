<?php

namespace Home\Widget;

use Think\Controller;

class CateWidget extends Controller
{
    public function sidebar()
    {
        $a = M('content');
        $condition['status'] = 1;
        $hotList = $a->where($condition)->field('id,router,title')->order('click desc')->limit(10)->select();
        $articleList = $a->where($condition)->field('id,router,title')->order('id desc')->limit(10)->select();
        $this->assign('articleList', $articleList);
        $this->assign('hotList', $hotList);
        $this->display('Cate/Sidebar');

    }

    public function menu()
    {
        $Menu = M("channel");
        $condition['is_nav'] = 1;
        $condition['status'] = 1;
        $data = $Menu->where($condition)->order("id asc")->select();
        $navList = list_to_tree($data);
        //dump($navList);
        $this->assign('menu', $navList);
        $this->display('Cate/menu');
    }
}
