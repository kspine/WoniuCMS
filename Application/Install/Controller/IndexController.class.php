<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        {
            $article = M(article);
            $condition['id'] < 10;
            $condition['status'] = 1;
            $articleList = $article->where('status=1')->order("id desc")->limit(10)->field('content', ture)->select();
            $recommendList = $article->where('status=1')->order('rand()')->limit(10)->select();
            $hotList = $article->where('status=1')->order('readCOunt desc')->limit(10)->select();
            $this->assign('articleList', $articleList);
            $this->assign('articleNews', $articleNews);
            $this->assign('recommendList', $recommendList);
            $this->assign('hotList', $hotList);
            $this->display();
        }
    }
}