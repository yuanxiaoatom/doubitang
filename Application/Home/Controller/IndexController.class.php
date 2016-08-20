<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;

use OT\DataDictionary;
use Zend\Crypt\PublicKey\Rsa\PrivateKey;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController
{
    /**
     * 首页
     */
    public function index()
    {
        
        // 读取栏目列表数据
        $channel = D('Channel')->field("title,url")
            ->order('sort asc')
            ->select();
        $this->assign('channel', $channel);
        $this->display();
        exit();
        // 新闻
        $xinwen = $this->getTitle('Xinwen', 60);
        $this->assign('xinwen', $xinwen);  
        // 首页产业园区数据
        $yuanqu= $this->getTitle('Yuanqu', 60);
        $this->assign('yuanqu', $yuanqu);       
        // 楼宇经济
        $louyu = $this->getTitle('Louyu', 60);
        $this->assign('louyu', $louyu);       
        // 厂房出售
        $changfang = $this->getTitle('Changfang', 60);
        $this->assign('changfang', $changfang);        
        // 土地
        $tudi = $this->getTitle('Tudi', 60);
        $this->assign('tudi', $tudi);
        
        $this->display();
    }

    /**
     * 产业园区列表
     */
    public function gion()
    {  
        $list = $this->getList('Yuanqu', $_GET);
        $this->assign('list', $list);
        $this->display();
    }
    // 厂房租售
    public function rental()
    {
        $list = $this->getList('Changfang', $_GET);
        $this->assign('list', $list);
        $this->display();      
    }

    public function tudi()
    {
        $list = $this->getList('Tudi', $_GET);
        $this->assign('list', $list);
        $this->display();
    }

    public function louyu()
    {
        $list = $this->getList('Louyu', $_GET);
        $this->assign('list', $list);
        $this->display();
    }

    /* 新闻中心 */
    public function news()
    {
        $list = $this->getList('Xinwen', $_GET);
        $this->assign('list', $list);
        $this->display();
    }

    /* 留言板 */
    public function message()
    {
        $list = $this->getList('Liuyuan', $_GET);
        $this->assign('list', $list);
        $this->display();
    }

    /* 服务 */
    public function server()
    {
        $list = $this->getList('Fuwu', $_GET);
        $this->assign('list', $list);
        $this->display();
    }

    /* 税务规划 */
    public function taxation()
    {
        $list = $this->getList('Suiwu', $_GET);
        $this->assign('list', $list);
        $this->display();
    }
}