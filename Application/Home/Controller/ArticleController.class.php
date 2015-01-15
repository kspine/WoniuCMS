<?php
namespace Home\Controller;

use Think\Controller;

class ArticleController extends Controller
{
    public function read($router)
    {
        $a = M('content');
        $this->assign('articleList', $articleList);
        $condition ['router'] = $router;
        $article = $a->where($condition)->find();
        if ($article['status'] == 1 || $_SESSION['userRole'] == 1 || $_SESSION [C('USER_AUTH_KEY')] == $article['authorId']) {
            $c = M('channel');
            $map['id'] = $article['category_id'];
            $articleChannel = $c->where($map)->find();
            $this->assign('channel', $articleChannel);
            $data['id'] = $article['id'];
            $data['click'] = $article['click'] + 1;
            $a->save($data);
            $this->assign('article', $article);
            $this->display();
        } else {
            $this->error("你访问的文章不存!", "__APP__");
        }
    }
}