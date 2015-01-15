<?php
/**
 * User: woniu
 *Emall:woniu365@gmail.com
 * Date: 14-12-21
 * Time: 上午12:12

 */

namespace Admin\Model;

use Think\Model;

class MemberModel extends Model
{
    protected $_map=array(
        'UserName'=>'name',
        'emaill'=>'emaill',
        'password'=>'pwd',
    );
    protected $_validate = array(
        array('name', '/^[a-z]\w{3,}$/i', '用户名验证错误！',1),
        array('name', '', '帐号名称已经存在！', 1, 'unique', 1), // 在新增的时候验证name字段是否唯一
        array('email', '', '邮箱已经存在！', 1, 'unique', 1), // 在新增的时候验证emaill字段是否唯一
        array('pwd', '/^[a-z]\w{3,}$/i', '密码非法！',1),
    );
    protected $_auto = array (
        array('status','1'),  // 新增的时候把status字段设置为1
        array('pwd','encryptor',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
        //array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
        array('reg_time','time',3,'function'), // 对update_time字段在更新的时候写入当前时间戳
        array('reg_ip','get_client_ip',3,'function'), // 对update_time字段在更新的时候写入当前时间戳
    );
}