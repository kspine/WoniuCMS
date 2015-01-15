<?php
namespace Admin\Controller;

use Think\Controller;

/**
 * 后台公共控制器
 */
class AdminController extends Controller
{

    /**
     * 后台控制器初始化
     */
    //
    protected function _initialize()
    {
        // 获取当前用户ID 
        $user = session('uid');
        //  dump($user);
        if ($user == false) {
            $this->error('您尚未登录！正在跳转登录页面',__APP__ .'/Admin/login.php');
        }
        $uid = $user['uid'];
        // 是否是超级管理员 ，UID == 1 的就是 管理员  //管理员允许访问任何页面 
        if (($uid == 1)) {
            return true;//管理员允许访问任何页面 
        }
        //  检测当前页是否有权限访问      // ' admin/index/index'
        $rule = strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME);

        static $Auth = null;
        if (!$Auth) {
            $Auth = new \Think\Auth();
        }
        if (!$Auth->check($rule, $uid, array('in', '1,2'), $mode = 'url')) {
            $this->error('没有权限访问本页面!');
        }
    }

}  