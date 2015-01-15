<?php

class IndexAction extends Action
{
    function index()
    {
        if (!empty($step)) {
            $step = 1;
        } else {
            $step = $_GET ["step"];
        }
        if ($step == 1) {
            $this->display('step-1');
        } elseif ($step == 2) {
            $this->display('step-2');
        } elseif ($step == 3) {
            $this->install();
            $this->display('step-3');
        }
    }

    function install()
    {

        $DB_Host = $_POST ["DB_Host"];
        $DB_User = $_POST ["DB_User"];
        $DB_Pwd = $_POST ["DB_Pwd"];
        $DB_NAME = $_POST ["DB_NAME"];
        $DB_PRE = $_POST ["DB_PRE"];
        $adminName = $_POST ["adminName"];
        $email = $_POST ["email"];
        $adminPwd = md5($_POST ["adminPwd"]);
        $regDate = time();
        $regIP = get_client_ip();

        // 连接数据库
        $conn = mysql_connect($DB_Host, $DB_User, $DB_Pwd) or die ("数据库连接失败！");
        mysql_query("DROP DATABASE `" . $DB_NAME . "`;", $conn);
        mysql_query("CREATE DATABASE IF NOT EXISTS `" . $DB_NAME . "` DEFAULT CHARSET=utf8;", $conn);
        mysql_select_db($DB_NAME) or die ("选择数据库失败");

        // 从DB_.sql创建数据表
        $i = file_get_contents(dirname(__FILE__) . '/DB_.sql');
        $j = str_replace(";", ";.", $i);
        $k = explode(".", $j);
        foreach ($k as $query) {
            mysql_query($query, $conn);
        }

        // 增加管理员帐号
        $adminquery = "INSERT INTO `user` (name,pwd,email,regTime,regIP) VALUES ( 'admin', '" . $adminPwd . "','" . $email . "','" . $regDate . "','" . $regIP . "');";
        mysql_query($adminquery, $conn);

        // 修改字段前缀
        $result = mysql_query("SHOW TABLES");
        while ($row = mysql_fetch_array($result)) {
            // echo $row[0]."<br />"; // 输出所有表
            $table [] = $row [0];
        }
        mysql_free_result($result);
        for ($i = 0; $i < count($table); $i++) {
            mysql_query("RENAME TABLE $table[$i] TO " . $pre . "_$table[$i]"); // 给表添加前缀
        }

        // 写入配置文件
        C("DB_HOST", $$DB_HOST);
        C("DB_PORT", $DB_PORT);
        C("DB_PREFIX", $DB_PREFIX);
        C("DB_USER", $DB_USER);
        C("DB_PWD", $DB_PWD);
        C("DB_NAME", $DB_NAME);
        $this->display('step-3');
        exit ();
    }
}

?>