<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-3-3
 * Time: 下午8:27
 */
namespace Admin\Widget;

use Think\Controller;

class CateWidget extends Controller
{
    public function  Sidebar()
    {
        $auth = new \Think\Auth();
        if ((session('uid') != 1)) {
            $_tmpinfo = $auth->getGroups(session('uid'));
            $menuIDList = explode(',', $_tmpinfo[0]['rules']); //当前用户有效权限的目录ID
            // dump($menuIDList);
            $map['id'] = array('in', $menuIDList); //查询当前用户有效权限的目录
        }
        $map['is_menu'] = 1;
        $map['module'] = 'admin';
        //  dump($map);
        $sidebarList = M('auth_rule')->where($map)->order('sort')->select();
        $sidebarList2 = list_to_tree($sidebarList);
        //dump($sidebarList2);
        $this->assign('sidebarList', $sidebarList2);
        $this->display('Cate/Sidebar');
    }
} 