<?php
namespace Home\Controller;

use Think\Controller;

class ChannelController extends Controller
{
    public function index($router)
    {
        $condition['router'] = $router;
        $channel = M('channel')->where($condition)->find();
        if ($channel) {
            $condition2['status'] = 1;
            $condition2['category_id'] = $channel['id'];
            $alist = M('content')->where($condition2)->order('id desc')->limit(50)->field('content,description,keywords', ture)->select();
            $this->assign('alist', $alist);
            $this->display();

        } else {
            $this->error("访问错误！");
        }

    }

    public function edit()
    {
        $this->display();
    }
}