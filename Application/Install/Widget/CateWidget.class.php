<?php

namespace Home\Widget;

use Think\Controller;

class CateWidget extends Controller
{
    public function sidebar()
    {
        $a = M('article');
        $condition['status'] = 1;
        $hotList = $a->where($condition)->field('id,title')->order('click desc')->limit(10)->select();
        $articleList = $a->where($condition)->field('id,title')->order('id desc')->limit(10)->select();
        $this->assign('articleList', $articleList);
        $this->assign('hotList', $hotList);
        $this->display('Cate/Sidebar');

    }


    public function menu()
    {
        $Menu = M("channel");
        $condition['menu'] = 1;
        $condition['status'] = 1;
        $data = $Menu->where($condition)->order("sort")->select();
        $this->assign('menu', $data);
        $this->display('Cate/menu');
    }


}
