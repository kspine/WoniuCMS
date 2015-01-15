<?php
namespace Home\Controller;

use Think\Controller;

class ArticleController extends Controller
{
    public function read($id)
    {
        $a = M('article');
        $this->assign('articleList', $articleList);
        $condition ['id'] = $id;
        $article = $a->where($condition)->find();
        if ($article['status'] == 1 || $_SESSION['userRole'] == 1 || $_SESSION [C('USER_AUTH_KEY')] == $article['authorId']) {
            $c = M('Channel');
            $map['id'] = $article['channelID'];
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

?>