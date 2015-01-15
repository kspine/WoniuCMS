<?php

class FileAction extends CommonAction
{
    public function uploadAvatar()
    {
        import('ORG.Net.UploadFile');
        $upload = new UploadFile(); // 实例化上传类
        $upload->maxSize = 1 * 1024 * 1024; //设置上传图片的大小
        $upload->allowExts = array('jpg', 'png', 'gif'); //设置上传图片的后缀
        $upload->uploadReplace = true; //同名则替换
        $upload->saveRule = 'avatar'; //设置上传头像命名规则(临时图片),修改了UploadFile上传类
        //完整的头像路径
        $path = C('UPLOAD_PATH') . '/avatars/';
        $upload->savePath = $path;
        if (!$upload->upload()) { // 上传错误提示错误信息
            $this->ajaxReturn('', $upload->getErrorMsg(), 0, 'json');
        } else { // 上传成功 获取上传文件信息
            $info = $upload->getUploadFileInfo();
            $temp_size = getimagesize($path . 'temp.jpg');
            if ($temp_size[0] < 100 || $temp_size[1] < 100) { //判断宽和高是否符合头像要求
                $this->ajaxReturn(0, '图片宽或高不得小于100px！', 0, 'json');
            }
            $this->ajaxReturn($path . $user_path . 'temp.jpg', $info, 1, 'json');
        }
    }

    //裁剪并保存用户头像
    public function cropImg()
    {
        //图片裁剪数据
        $params = $this->_post(); //裁剪参数
        if (!isset($params) && empty($params)) {
            return;
        }

        //头像目录地址
        $path = C('UPLOAD_PATH') . '/avatars/';
        //要保存的图片
        $real_path = $path . 'avatar.jpg';
        //临时图片地址
        $pic_path = $path . 'temp.jpg';
        import('ORG.ThinkImage.ThinkImage');
        $Think_img = new ThinkImage(THINKIMAGE_GD);
        //裁剪原图
        $Think_img->open($pic_path)->crop($params['w'], $params['h'], $params['x'], $params['y'])->save($real_path);
        //生成缩略图
        $Think_img->open($real_path)->thumb(100, 100, 1)->save($path . 'avatar_100.jpg');
        $Think_img->open($real_path)->thumb(60, 60, 1)->save($path . 'avatar_60.jpg');
        $Think_img->open($real_path)->thumb(30, 30, 1)->save($path . 'avatar_30.jpg');
        $this->success('上传头像成功');
    }

    public
    function uploadfile()
    {
        import('ORG.Net.UploadFile');
        $config = C('IMAGE_UPLOAD_CONFIG');
        $config['autoSub'] = true;
        $config['subType'] = "date";
        $config['thumb'] = true;
        $config['thumbPrefix'] = 'm_,s_';
        $config['thumbExt'] = "jpg";
        $config['thumbMaxWidth'] = '200,50';
        $config['thumbMaxHeight'] = '200,50';
        $upload = new UploadFile($config); // 实例化上传类并传入参数

        if (!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        } else {
            $this->success('上传成功！');
        }

    }

    public function fileUP()
    {
        header("Content-Type: text/html; charset=utf-8");
        error_reporting(E_ERROR | E_WARNING);
        vendor('UeditorUploader');
        //上传配置
        $config = C('FILE_UPLOAD_CONFIG');
        //生成上传实例对象并完成上传
        $up = new Uploader("upfile", $config);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "originalName" => "",   //原始文件名
         *     "name" => "",           //新文件名
         *     "url" => "",            //返回的地址
         *     "size" => "",           //文件大小
         *     "type" => "" ,          //文件类型
         *     "state" => ""           //上传状态，上传成功时必须返回"SUCCESS"
         * )
         */
        $info = $up->getFileInfo();

        /**
         * 向浏览器返回数据json数据
         * {
         *   'url'      :'a.rar',        //保存后的文件路径
         *   'fileType' :'.rar',         //文件描述，对图片来说在前端会添加到title属性上
         *   'original' :'编辑器.jpg',   //原始文件名
         *   'state'    :'SUCCESS'       //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
         * }
         */

        $info["url"] = preg_replace('/\./', '', $info["url"], 1);
        echo '{"url":"' . $info["url"] . '","fileType":"' . $info["type"] . '","original":"' . $info["originalName"] . '","state":"' . $info["state"] . '"}';
    }

    public function getRemoteImage()
    {
        header("Content-Type: text/html; charset=utf-8");
        error_reporting(E_ERROR | E_WARNING);
        //远程抓取图片配置
        $config = C('IMAGE_UPLOAD_CONFIG');
        $uri = htmlspecialchars($_POST['upfile']);
        $uri = str_replace("&amp;", "&", $uri);
        $this->getRemoteImage1($uri, $config);
        echo "{'url':'" . implode("ue_separate_ue", $tmpNames) . "','tip':'远程图片抓取成功！','srcUrl':'" . $uri . "'}";
    }

    function getRemoteImage1($uri, $config)
    {
        header("Content-Type: text/html; charset=utf-8");
        error_reporting(E_ERROR | E_WARNING);
        /**
         * 远程抓取
         * @param $uri
         * @param $config
         */

        /**
         * 返回数据格式
         * {
         *   'url'   : '新地址一ue_separate_ue新地址二ue_separate_ue新地址三',
         *   'srcUrl': '原始地址一ue_separate_ue原始地址二ue_separate_ue原始地址三'，
         *   'tip'   : '状态提示'
         * }
         */
        //忽略抓取时间限制
        set_time_limit(0);
        //ue_separate_ue  ue用于传递数据分割符号
        $imgUrls = explode("ue_separate_ue", $uri);
        $tmpNames = array();
        foreach ($imgUrls as $imgUrl) {
            //http开头验证
            if (strpos($imgUrl, "http") !== 0) {
                array_push($tmpNames, "error");
                continue;
            }
            //获取请求头
            $heads = get_headers($imgUrl);
            //死链检测
            if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
                array_push($tmpNames, "error");
                continue;
            }

            //格式验证(扩展名验证和Content-Type验证)
            $fileType = strtolower(strrchr($imgUrl, '.'));
            if (!in_array($fileType, $config['allowFiles']) || stristr($heads['Content-Type'], "image")) {
                array_push($tmpNames, "error");
                continue;
            }

            //打开输出缓冲区并获取远程图片
            ob_start();
            $context = stream_context_create(
                array(
                    'http' => array(
                        'follow_location' => false // don't follow redirects
                    )
                )
            );
            //请确保php.ini中的fopen wrappers已经激活
            readfile($imgUrl, false, $context);
            $img = ob_get_contents();
            ob_end_clean();

            //大小验证
            $uriSize = strlen($img); //得到图片大小
            $allowSize = 1024 * $config['maxSize'];
            if ($uriSize > $allowSize) {
                array_push($tmpNames, "error");
                continue;
            }
            //创建保存位置
            $savePath = $config['savePath'];
            if (!file_exists($savePath)) {
                mkdir("$savePath", 0777);
            }
            //写入文件
            $tmpName = $savePath . rand(1, 10000) . time() . strrchr($imgUrl, '.');
            try {
                $fp2 = @fopen($tmpName, "a");
                fwrite($fp2, $img);
                fclose($fp2);
                array_push($tmpNames, $tmpName);
            } catch (Exception $e) {
                array_push($tmpNames, "error");
            }
        }
    }

    public function imageManager()
    {
        header("Content-Type: text/html; charset=utf-8");
        error_reporting(E_ERROR | E_WARNING);
        //需要遍历的目录列表，最好使用缩略图地址，否则当网速慢时可能会造成严重的延时
        $path = C('IMAGE_UPLOAD_CONFIG.savePath');
        //$path = '/12300/Uploads/Images/';
        $action = htmlspecialchars($_POST["action"]);
        if ($action == "get") {
            $files = array();
            $tmp = $this->getfiles($path);
            if ($tmp) {
                $files = array_merge($files, $tmp);
            }

            if (!count($files)) return;
            rsort($files, SORT_STRING);
            $str = "";
            foreach ($files as $file) {
                $str .= $file . "ue_separate_ue";
            }
            echo $str;
        }

    }

    function getfiles($path, &$files = array())
    {
        /**
         * 遍历获取目录下的指定类型的文件
         * @param $path
         * @param array $files
         * @return array
         */

        if (!is_dir($path)) return null;
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != ' . ' && $file != ' ..') {
                $path2 = $path . ' / ' . $file;
                if (is_dir($path2)) {
                    getfiles($path2, $files);
                } else {
                    if (preg_match("/\.(gif|jpeg|jpg|png|bmp)$/i", $file)) {
                        $files[] = $path2;
                    }
                }
            }
        }
        return $files;
    }

    public
    function imageUp()
    {
        header("Content-Type: text/html; charset=utf-8");
        error_reporting(E_ERROR | E_WARNING);
        date_default_timezone_set("Asia/chongqing");
        vendor('UeditorUploader');
        //上传图片框中的描述表单名称，
        $title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
        $path = htmlspecialchars($_POST['dir'], ENT_QUOTES);

        //上传配置
        $config = C('IMAGE_UPLOAD_CONFIG');
        //生成上传实例对象并完成上传
        $up = new Uploader("upfile", $config);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "originalName" => "",   //原始文件名
         *     "name" => "",           //新文件名
         *     "url" => "",            //返回的地址
         *     "size" => "",           //文件大小
         *     "type" => "" ,          //文件类型
         *     "state" => ""           //上传状态，上传成功时必须返回"SUCCESS"
         * )
         */
        $info = $up->getFileInfo();

        /**
         * 向浏览器返回数据json数据
         * {
         *   'url'      :'a . jpg',   //保存后的文件路径
         *   'title'    :'hello',   //文件描述，对图片来说在前端会添加到title属性上
         *   'original' :'b . jpg',   //原始文件名
         *   'state'    :'SUCCESS'  //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
         * }
         */
        echo "{'url':'" . $info["url"] . "','title':'" . $title . "','original':'" . $info["originalName"] . "','state':'" . $info["state"] . "'}";


    }

    public function scrawlUp()
    {

        header("Content-Type:text/html;charset=utf-8");
        error_reporting(E_ERROR | E_WARNING);
        vendor('UeditorUploader');
        //上传配置
        $config = $config = C('IMAGE_UPLOAD_CONFIG');
        //临时文件目录
        $tmpPath = "tmp/";

        //获取当前上传的类型
        $action = htmlspecialchars($_GET["action"]);
        if ($action == "tmpImg") { // 背景上传
            //背景保存在临时目录中
            $config["savePath"] = $tmpPath;
            $up = new Uploader("upfile", $config);
            $info = $up->getFileInfo();
            /**
             * 返回数据，调用父页面的ue_callback回调
             */
            echo "<script>parent.ue_callback('" . $info["url"] . "','" . $info["state"] . "')</script>";
        } else {
            //涂鸦上传，上传方式采用了base64编码模式，所以第三个参数设置为true
            $up = new Uploader("content", $config, true);
            //上传成功后删除临时目录
            if (file_exists($tmpPath)) {
                delDir($tmpPath);
            }
            $info = $up->getFileInfo();
            echo "{'url':'" . $info["url"] . "',state:'" . $info["state"] . "'}";
        }


    }

    function delDir($dir)
    {
        /**
         * 删除整个目录
         * @param $dir
         * @return bool
         */
        //先删除目录下的所有文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    delDir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        return rmdir($dir);
    }

    public function getMovie()
    {
        $key = htmlspecialchars($_POST["searchKey"]);
        $type = htmlspecialchars($_POST["videoType"]);
        $html = file_get_contents('http://api.tudou.com/v3/gw?method=item.search&appKey=myKey&format=json&kw=' . $key . '&pageNo=1&pageSize=20&channelId=' . $type . '&inDays=7&media=v&sort=s');
        echo $html;
    }
}