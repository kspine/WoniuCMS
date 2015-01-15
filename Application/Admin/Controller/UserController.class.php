<?php
namespace Admin\Controller;

use Think\Controller;

class UserController extends AdminController
{
    public function  index()
    {
        $this->UserList();
    }

    public function UserList()
    {
        $user = M("user");
        $userList = $user->select();
        $this->assign('userList', $userList);
        $this->display('UserList');
    }

    public function Member()
    {
        $user = M("member");
        $userList = $user->select();
        $this->assign('userList', $userList);
        $this->display();
    }

    public function Action()
    {
        $this->display();
    }

    public function info($id)
    {
        if ($id == false) {
            $this->error("非法访问！", "__GROUP__");
        } else {
            $user = M("member");
            $userInfo = $user->find($id);
            if (!$userInfo) {
                $this->error("非法访问！", "__GROUP__");
            } else {
                $this->assign('userInfo', $userInfo);
                $this->display();
            }
        }
    }

    public function UserAdd()
    {
        if (IS_POST) {
            $User = D("User"); // 实例化User对象
            if (!$User->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                exit($User->getError());
            } else {
                // 验证通过 可以进行其他数据操作

                // 把数据对象添加到数据库
                $User->add();
                $this->ajaxReturn('提交成功');
            }
        } else {
            $this->error();
        }
    }

    public function delete()
    {
    }

    public function actionlog()
    {
    }
}