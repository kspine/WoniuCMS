<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $article = M('content');
        $map['status'] = 1;
        $articleList = $article->where($map)->order("id desc")->limit(8)->field('content', true)->select();
        $recommendList = $article->where($map)->order('rand()')->limit(8)->field('content', true)->select();
        $hotList = $article->where($map)->order('click desc')->limit(8)->field('content', true)->select();
        //dump($articleList);
        $this->assign('articleList', $articleList);
        $this->assign('hotList', $hotList);
        $this->assign('recommendList', $recommendList);
        $this->display();
    }



}