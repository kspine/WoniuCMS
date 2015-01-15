<?php
namespace Home\Controller;

use Think\Controller;

class ChannelController extends Controller
{
    public function index($id = 0)
    {
        $condition['id'] = $id;
        $channelTypeID = M('channel')->where($condition)->getField('typeID');
        $channelTitle = M('channel')->where($condition)->getField('title');
        $channelTable = M('channel_type')->where('id=' . $channelTypeID)->getField('type');
        $l = M('article');
        $condition2['channelID'] = $id;
        $condition2['status'] = 1;
        $condition2['_logic'] = 'AND';
        $alist = $l->where($condition2)->order('id desc')->limit(50)->field('content,description,keywords', ture)->select();
        $this->assign('channelTitle', $channelTitle);
        $this->assign('alist', $alist);
        $this->display();
    }
}