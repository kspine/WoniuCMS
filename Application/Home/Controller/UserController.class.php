<?php
namespace Home\Controller;

use Think\Controller;
class UserController extends Controller
{
    public function index(){
        $this->display();
    }
    public  function reg(){
        $this->display();
    }
    public  function login(){
        $this->display();
    }
    public function avatar()
    {
        if ($email) {
            $avatar = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($email)));
        } else {
            $avatar = "http://www.gravatar.com/avatar/";
        }
        // return $avatar;
        $this->display();
    }
    public function loginAction()
    {
        if (IS_POST) {

            $codeStatus = check_verify($_POST['verify'], '');
            if (!$codeStatus) {
//                dump($_POST);
//                echo md5($_POST['verity']);
//                dump($_SESSION);
                $info = '验证码错误！';
                $status = 'n';
            } else {
                $map['name'] = $_POST['username'];
                $admin = M('member');
                $authInfo = $admin->where($map)->find();
                if (!$authInfo || !$authInfo['status'] || $authInfo['pwd'] != MD5($_POST['password'])) {
                    $info = '用户名或者密码错误！';
                    $status = 'n';
                } else {
                    $info = '登录成功！';
                    $status = 'y';
                    $_SESSION ['uid'] = $authInfo['id'];
                    $_SESSION ['userName'] = $authInfo ['name'];
                    $_SESSION ['last_login_time'] = $authInfo ['last_login_time'];
                    $_SESSION ['login_count'] = $authInfo ['login_count'];
                    // 保存登录信息
                    $data ['id'] = $authInfo ['id'];
                    $data ['last_login_time'] = time();
                    $data ['last_login_ip'] = get_client_ip();
                    $data ['login_count'] = $_SESSION ['login_count'] + 1;
                    $admin->save($data);
                }
            }
            $returnData['info'] = $info;
            $returnData['status'] = $status;

            $this->ajaxReturn($returnData);
        }
    }

    //执行注销
    function logout()
    {
        session('uid', null);
        $this->success('您已经安全退出！',__APP__ );
    }


    function verify()
    {
        $config = array(
            'fontSize' => 22, // 验证码字体大小
            'length' => 4, // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
            'reset' => true, // 验证成功后是否重置
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();

    }

    function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    function AjaxCheckVerify()
    {
        $code = $_POST["param"];
        $verify = new \Think\Verify();
        $status = $verify->check($code);
        if ($status) {
            echo '{
			"info":"验证码通过！",
			"status":"y"
		 }';
        } else {
            echo '{
			"info":"验证码错误！",
			"status":""
		 }';
        }
    }
}