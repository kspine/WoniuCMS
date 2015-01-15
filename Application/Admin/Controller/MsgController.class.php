<?php
namespace Admin\Controller;

use Think\Controller;

class MsgController extends AdminController
{
    function index()
    {
        $this->msgList();
    }

    public function unReadMsgCount()
    {
        $msg = M('msg');
        $condition['status'] = 0;
        $unReadMsgCount = $msg->where($condition)->count();
        header("Content-Type: text/html; charset=utf-8");
        echo '【' . $unReadMsgCount . '】';
    }

    public function msgList()
    {
        $msg = M('msg');
        $messageList = $msg->select();
        $condition['receiverID'] = $_SESSION['authId'];
        $messageList = $msg->where($condition)->select();
        $condition['status'] = 0;
        $unReadMessageList = $msg->where($condition)->select();
        $condition['status'] = 1;
        $readMessageList = $msg->where($condition)->select();
        $this->assign('messageList', $messageList);
        $this->assign('unReadMessageList', $unReadMessageList);
        $this->assign('readMessageList', $readMessageList);
        $this->display('msgList');
    }

    public function read($id)
    {
        $msg = M("msg");
        $message = $msg->find($id);
        $data['status'] = 1;
        $msg->where('id=' . $id)->save($data);
        $this->assign("message", $message);
        $this->display();
    }

    public function del()
    {
        $msg = M("msg");
        $condition = $_GET;
        $condition ['_logic'] = "OR";
        $msg->where($condition)->delete();
    }

    public function reply()
    {
    }

    public function unReadCount()
    {
        $msg = M('msg');
        $condition['receiverID'] = (int)$_SESSION['authId'];
        $condition['status'] = 0;
        $unReadCount = $msg->where($condition)->count();
        if ($unReadCount > 1) {
            $this->ajaxReturn($unReadCount, '有' . $unReadCount . '条未读消息！', 1);
        } else {
            $this->ajaxReturn($unReadCount, '没有未读消息！', 1);
        }

    }
}

?>