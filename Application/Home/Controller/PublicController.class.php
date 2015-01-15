<?php
namespace Home\Controller;

use Think\Controller;

class PublicController extends Controller
{
    Public function loginAction()
    {
        if (!IS_POST) {
            _404("你访问的页面不存在！");
        }
        if (empty ($_POST ['username'])) {
            $this->error('帐号错误！');
        } elseif (empty ($_POST ['password'])) {
            $this->error('密码必须！');
        } elseif ($_SESSION ['verify'] != md5($_POST ['verify'])) {
            $this->error('验证码错误！');
        }
        // 生成认证条件
        $map = array();
        // 支持使用绑定帐号登录
        $map ['name'] = $_POST ['username'];
        import('ORG.Util.RBAC');
        $authInfo = RBAC::authenticate($map);

        // 使用用户名、密码和状态的方式进行认证
        if (false === $authInfo) {
            $this->error('帐号不存在！');
        } else {
            if ($authInfo ['pwd'] != md5($_POST ['password'])) {
                $this->error('密码错误！');
            }
            $_SESSION [C('USER_AUTH_KEY')] = $authInfo['id'];
            $_SESSION ['email'] = $authInfo ['email'];
            $_SESSION ['userName'] = $authInfo ['name'];
            $_SESSION ['nickName'] = $authInfo ['nickname'];
            $_SESSION ['lastLoginTime'] = $authInfo ['lastLoginTime'];
            $_SESSION ['loginCount'] = $authInfo ['loginCount'];
            if ($authInfo ['name'] == 'admin') {
                $_SESSION ['administrator'] = true;
            }
            $u = M('role_user');
            $userRole = $u->getFieldByUser_id($authInfo ['id'], role_id);
            $_SESSION['userRole'] = $userRole;
            // 保存登录信息
            $User = M('member');
            $ip = get_client_ip();
            $time = time();
            $data = array();
            $data ['id'] = (int)$authInfo ['id'];
            $data ['last_login_time'] = $time;
            $data ['last_login_ip'] = $ip;
            $data ['login_cunt'] = $_SESSION ['loginCount'] + 1;
            $User->save($data);

            // 缓存访问权限
            RBAC::saveAccessList();
            $this->success('登录成功！', "__APP__/");
        }
    }

    public function login()
    {
        if (!isset ($_SESSION [C("USER_AUTH_KEY")])) {
            // dump($_SESSION);
            $this->display();
        } else {
            $this->success("已经登陆！！！", "__ROOT__");
        }
    }


    public function logout()
    {
        session('[destroy]');
        redirect("./login.php");
        exit ();
    }

    public function search()
    {
        if ($_GET['key']) {
            $key = explode(" ", $_GET["key"]);
            //$type=$_GET["type"];
            $so = M("content");
            $condition['title'] = array('like', '%' . $key[0] . '%');
            $condition['content'] = array('like', '%' . $key[0] . '%');
            $condition['_logic'] = 'OR';
            $query = $so->where($condition)->field('content', ture)->select();
            $this->assign('key', $_GET['key']);
            $this->assign("query", $query);
            $this->display();
        }
    }
    //指定文件夹随机输出文件夹中的图片
    public function img($dir)
    {
        if (is_dir($dir)) {
            $i = 0;
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        $data[$i] = $file;
                        $i++;
                    }
                }
                closedir($dh);
            }
        }
        $length = count($data) - 1;
        $j = rand(0, $length);
        header("Content-type: image/jpeg");
        $PSize = filesize($dir . '/' . $data[$j]);
        $picturedata = fread(fopen($dir . '/' . $data[$j], "r"), $PSize);
        echo $picturedata;
    }

}