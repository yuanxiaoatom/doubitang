<?php
namespace Home\Controller;

use OT\DataDictionary;
use Zend\Crypt\PublicKey\Rsa\PrivateKey;

/**
 * 详情数据页
 * 主要根据id获取对应记录数据
 */
class DetailsController extends HomeController
{
    /**
     * 根据id获取产业园区详情
     */
    public function gion()
    {   
        if(!isset($_GET['id'])){
            $this->error('未获取到ID',U('Index/gion'));
        }
        $details = $this->getDetailsById('Yuanqu', $_GET['id']);
        if(!empty($details)){
            $this->error('未获取到信息',U('Index/gion'));
        }
        $this->assign('details', $details);
        $this->display();
    }
    /**
     * 根据id获取厂房详情
     */
    public function rental()
    {
        if(!isset($_GET['id'])){
            $this->error('未获取到ID',U('Index/rental'));
        }
        $details = $this->getDetailsById('Changfang', $_GET['id']);
        if(!empty($details)){
            $this->error('未获取到信息',U('Index/rental'));
        }
        $this->assign('details', $details);
        $this->display();
    }
    /**
     * 根据id获取土地詳情
     */
    public function tudi()
    {
        if(!isset($_GET['id'])){
            $this->error('未获取到ID',U('Index/tudi'));
        }
        $details = $this->getDetailsById('Tudi', $_GET['id']);
        if(!empty($details)){
            $this->error('未获取到信息',U('Index/tudi'));
        }
        $this->assign('details', $details);
        $this->display();
    }
    /**
     *根据id获取 楼宇詳情
     */
    public function louyu()
    {
        if(!isset($_GET['id'])){
            $this->error('未获取到ID',U('Index/louyu'));
        }
        $details = $this->getDetailsById('Louyu', $_GET['id']);
        if(!empty($details)){
            $this->error('未获取到信息',U('Index/louyu'));
        }
        $this->assign('details', $details);
        $this->display();
    }
    /**
     * 根据id获取新闻詳情
     */
    public function news()
    {
        if(!isset($_GET['id'])){
            $this->error('未获取到ID',U('Index/news'));
        }
        $details = $this->getDetailsById('News', $_GET['id']);
        if(!empty($details)){
            $this->error('未获取到信息',U('Index/news'));
        }
        $this->assign('details', $details);
        $this->display();
    }
    /**
     * 根据id获取留言詳情
     */
    public function message()
    {
        if(!isset($_GET['id'])){
            $this->error('未获取到ID',U('Index/message'));
        }
        $details = $this->getDetailsById('Liuyuan', $_GET['id']);
        if(!empty($details)){
            $this->error('未获取到信息',U('Index/message'));
        }
        $this->assign('details', $details);
        $this->display();
    }
    
    /**
     * 根据id获取服务詳情
     */
    public function server()
    {
        if(!isset($_GET['id'])){
            $this->error('未获取到ID',U('Index/server'));
        }
        $details = $this->getDetailsById('Fuwu', $_GET['id']);
        if(!empty($details)){
            $this->error('未获取到信息',U('Index/server'));
        }
        $this->assign('details', $details);
        $this->display();
    }
    
    /**
     * 根据id获取税务规划詳情
     */
    public function taxation()
    {
        if(!isset($_GET['id'])){
            $this->error('未获取到ID',U('Index/taxation'));
        }
        $details = $this->getDetailsById('Suiwu', $_GET['id']);
        if(!empty($details)){
            $this->error('未获取到信息',U('Index/taxation'));
        }
        $this->assign('details', $details);
        $this->display();
    }
}