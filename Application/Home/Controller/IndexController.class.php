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
     * 首页各栏目显示记录数量
     */
    private $count = 8;

    /**
     * 设置每页记录数量
     * 
     * @var unknown
     */
    private $pagesize = 10;

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
        
        $leixingmodel = D('CategoryTree');
        
        // 新闻
        $xinwenmodel = D('Xinwen');
        $idList = $leixingmodel->getIdByPid(60);
        
        foreach ($idList as $v) {
            $xinwen[$v['id']] = $xinwenmodel->getXinwenTitle('leixing_id =' . $v['id'], array(
                'id' => 'desc'
            ), $this->count);
        }
        $this->assign('xinwen', $xinwen);
        
        // 首页产业园区数据
        $yuanqumodel = D('Yuanqu');
        $idList = $leixingmodel->getIdByPid(60);
        foreach ($idList as $v) {
            $gion[$v['id']] = $yuanqumodel->getYuanquTitle('leixing_id =' . $v['id'], array(
                'id' => 'desc'
            ), $this->count);
        }
        print_r($gion);
        exit();
        $this->assign('gion', $gion);
        
        // 楼宇经济
        $louyumodel = D('Louyu');
        $idList = $leixingmodel->getIdByPid(60);
        
        foreach ($idList as $v) {
            $louyu[$v['id']] = $louyumodel->getLouyuTitle('leixing_id =' . $v['id'], array(
                'id' => 'desc'
            ), $this->count);
        }
        $this->assign('louyu', $louyu);
        
        // 厂房出售
        $changfangmodel = D('Changfang');
        $idList = $leixingmodel->getIdByPid(60);
        
        foreach ($idList as $v) {
            $changfang[$v['id']] = $changfangmodel->getChangfangTitle('leixing_id =' . $v['id'], array(
                'id' => 'desc'
            ), $this->count);
        }
        $this->assign('changfang', $changfang);
        
        // 土地
        $tudimodel = D('Tudi');
        $idList = $leixingmodel->getIdByPid(60);
        
        foreach ($idList as $v) {
            $tudi[$v['id']] = $tudimodel->getTudiTitle('leixing_id =' . $v['id'], array(
                'id' => 'desc'
            ), $this->count);
        }
        $this->assign('tudi', $tudi);
        
        $this->display();
    }

    /**
     * 产业园区列表
     */
    public function gion()
    {
//         $this->display();
//         exit();
        $yuanqumodel = D('Yuanqu');
        $city_id = isset($_GET['city_id']) ? $_GET['city_id'] : 0;
        $gongneng_id = isset($_GET['gongneng_id']) ? $_GET['gongneng_id'] : 0;
        $leixing_id = isset($_GET['leixing_id']) ? $_GET['leixing_id'] : 0;
        $jibie_id = isset($_GET['jibie_id']) ? $_GET['jibie_id'] : 0;
        $quyu_id = isset($_GET['quyu_id']) ? $_GET['quyu_id'] : 0;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = '';
        // 拼接where条件
        if (! empty($city_id)) {
            $where .= ' AND `city_id` = ' . $city_id;
        }
        if (! empty($gongneng_id)) {
            $where .= ' AND `gongneng_id` = ' . $gongneng_id;
        }
        if (! empty($leixing_id)) {
            $where .= ' AND `leixing_id` = ' . $leixing_id;
        }
        if (! empty($jibie_id)) {
            $where .= ' AND `jibie_id` = ' . $jibie_id;
        }
        if (! empty($quyu_id)) {
            $where .= ' AND `quyu_id` = ' . $quyu_id;
        }
        $yuanqulist = $yuanqumodel->getYuanquList($where, $page, $order = array(
            'id' => 'desc'
        ), $this->pagesize);
        $this->assign('yuanqulist', $yuanqulist);
        $this->display();
    }
    // 厂房租售
    public function rental()
    {
        $changfangmodel = D('Changfang');
        $city_id = isset($_GET['city_id']) ? $_GET['city_id'] : 0;
        $gongneng_id = isset($_GET['gongneng_id']) ? $_GET['gongneng_id'] : 0;
        $leixing_id = isset($_GET['leixing_id']) ? $_GET['leixing_id'] : 0;
        $jibie_id = isset($_GET['jibie_id']) ? $_GET['jibie_id'] : 0;
        $quyu_id = isset($_GET['quyu_id']) ? $_GET['quyu_id'] : 0;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $where = '';
        // 拼接where条件
        if (! empty($city_id)) {
            $where .= ' AND `city_id` = ' . $city_id;
        }
        if (! empty($gongneng_id)) {
            $where .= ' AND `gongneng_id` = ' . $gongneng_id;
        }
        if (! empty($leixing_id)) {
            $where .= ' AND `leixing_id` = ' . $leixing_id;
        }
        if (! empty($jibie_id)) {
            $where .= ' AND `jibie_id` = ' . $jibie_id;
        }
        if (! empty($quyu_id)) {
            $where .= ' AND `quyu_id` = ' . $quyu_id;
        }
        $yuanqulist = $yuanqumodel->getYuanquList($where, $page, $order = array(
            'id' => 'desc'
        ), $this->pagesize);
        $this->assign('yuanqulist', $yuanqulist);
        $this->display();
        
        $this->display();
    }

    public function tudi()
    {
        $this->display();
    }

    public function louyu()
    {
        $this->display();
    }

    /* 新闻中心 */
    public function news()
    {
        $this->display();
    }

    /* 留言板 */
    public function message()
    {
        $this->display();
    }

    /* 服务 */
    public function server()
    {
        $this->display();
    }

    /* 税务规划 */
    public function taxation()
    {
        $this->display();
    }
}