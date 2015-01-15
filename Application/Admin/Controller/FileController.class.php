<?php
/**
 * Created by PhpStorm.
 * User: Woniu
 * Date: 14-3-9
 * Time: 下午8:52
 */

namespace Admin\Controller;

use Think\Think;

class FileController extends AdminController
{
    public function index()
    {

    }

    public function download()
    {
        $this->display('downloadList');
    }

    public function imgUpload()
    {
        if ( !empty($_FILES)) {
            //图片上传设置
            $config = C('IMAGE_UPLOAD_CONFIG');
            $upload = new \Think\Upload($config); // 实例化上传类
            $info = $upload->upload();
            if ($info) {
                foreach ($info as $file) {
                    $image_base_path= $file['savepath'] . $file['savename'];
                    $image_path = $config['rootPath'] .$image_base_path;
                    //echo $image_path;
                    $image = new \Think\Image();//实例化图像处理类
                    $image->open($image_path);
                    $thumb_base_path=$file['savepath'] . 'thumb_' . $file['savename'];
                    $thumb_path = $config['rootPath'].$thumb_base_path;
                    $image->thumb(150, 150, \Think\Image::IMAGE_THUMB_FILLED)->save($thumb_path);
                    //返回文件地址和名给JS作回调用
                   // echo $thumb_base_path;
                    $data['thumb']=$thumb_base_path;
                    $data['image']=$image_base_path;
                    $data['time']=time();
                    $this->ajaxReturn($data);
                }
            } else {
                $this->error($upload->getError()); //获取失败信息
            }
        } else {
            $this->error();
        }
    }
}
