<?php
namespace Admin\Controller;

use Think\Controller;

class ContentController extends AdminController
{
    public function  index()
    {

        $this->clist($type = 'article');
    }

/*    public function clist($type)
    {
        $contentTable = C('DB_PREFIX') . 'content';
        $categoryTable = C('DB_PREFIX') . 'channel';
        $cList = M()->table($contentTable . ' content,' . $categoryTable . ' category')
            ->where(array('content.category_id = category.id', 'content.status!=-1', 'content.type='.$type))
            ->field('content.id as id, content.title as title, category.name as category_name,author,update_time,content.status,content.router as router,content.type as type')
            ->order('content.id desc')
            ->select();
        $this->assign('cList', $cList);
        $this->display($type . 'List');
    }*/

   public function clist($type)
    {
        $map['status']=1;
        $map['type']=$type;
        $cList = M('content')->where($map)->field('content',true)->order('id desc') ->select();
        $this->assign('cList', $cList);
        $this->display($type . 'List');
    }
    public function del($id)
    {
        $d = M('content');
        $condition['id'] = $id;
        $data['title'] = $d->where($condition)->getField('title');
        if ($data['title']) {
            $info = $d->where($condition)->delete();
            if ($info) {
                $this->ajaxReturn($data);
            } else {
                return false;
            }
        }
    }
    public function edit($type = null, $id = null)
    {
        if ($id != 0) {
            $e = M('content');
            $data = $e->find($id);
            if ($data) {
                $this->assign('id', $id);
                $this->assign('data', $data);
            }
        }
        $map['status'] = 1;
        $c = M('channel')->where($map)->field('id,pid,title', 0)->order('id,sort desc')->select();
        $category = list_to_tree($c);
        $this->assign('category', $category);
        $this->display($type . 'Edit');
    }
    public function go()
    {
        if (!IS_POST) {
            $this->error('非法访问！');
        }
        $data = $_POST ['info'];
        $data['author'] = $_SESSION['userName'];
        $id = $_POST['id'];
        if ($data['router']) {
            $keyword = urlencode($data['router']);//将关键字编码
            $keyword = preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99|%EF%BD%9E|%EF%BC%8E|%EF%BC%88)+/", '', $keyword);
            $data['router'] = urldecode($keyword);//将过滤后的关键字解码
        } else {
            $data['router'] = substr(md5(time() . rand()), 2, 14);
        }
        $returnData['router'] = $data['router'];
        if ($id != 0) {
            $condition ['id'] = $id;
            $data ['update_time'] = time();
            $returnData['id'] = $id;
            $s = M('content')->where($condition)->save($data);
        } else {
            $data ['create_time'] = time();
            $s = M('content')->add($data);
            $returnData['id'] = $s;
        }
        $returnData['url'] = U('/content/' . $s);
        $returnData['title'] = $data['title'];
        $returnData['time'] = time();
        $this->ajaxReturn($returnData);
    }

    public function  setStatus($id, $status)
    {
        $map['id'] = array("in", $id);
        $data['status'] = $status;
        $d = M('content');
        $d->where($map)->save($data);
        $returnData['content'] = $d->where($map)->field("id,title")->select();
        $this->ajaxReturn($returnData);
    }

    public function recycle()
    {
        $contentTable = C('DB_PREFIX') . 'content';
        $categoryTable = C('DB_PREFIX') . 'channel';
        $cList = M()->table($contentTable . ' content,' . $categoryTable . ' category')
            ->where(array('content.category_id = category.id', 'content.status=-1'))
            ->field('content.id as id, content.title as title, category.title as categoryTitle,author,update_time,content.status')
            ->order('content.id desc')
            ->select();
        $this->assign('cList', $cList);
        $this->display();
    }
}