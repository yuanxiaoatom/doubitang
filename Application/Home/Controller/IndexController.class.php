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
    public function index(){
    	//获取成绩的ID将城市的ID存入cookie中
    	$c_id = $_GET['c_id'];
    	if(empty($c_id)){
    		$c_id = 40;
    	}
    	cookie('c_id',$c_id,3600*24*7);
    	$city_name = M('CategoryTree')->field("title")->where("id = $c_id")->find();
    	$this->assign('city_name',$city_name);
        //用户信息，未登录为空
        $userInfo = $this->userInfo;
        $this->assign('userInfo ', $userInfo );
        // 读取栏目列表数据
        $channel = $this->getChannel();
        $this->assign('channel', $channel);
        $this->display();
        exit();
        // 新闻
        $news = $this->getTitle('News', 60);
        $this->assign('news', $news);  
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
    	//读取导航信息
    	$channel = $this->getChannel();
    	$this->assign('channel', $channel);
    	
    	//获取城市的ID
    	$c_id = cookie('c_id');
    	if(empty($c_id)){
    		$c_id = 40;
    	}
    	$city_name = M('CategoryTree')->field("title")->where("id = $c_id")->find();
    	$this->assign('city_name',$city_name);
    	//获取城市信息
    	$cityList = $this->getType($c_id);
    	$this->assign('cityList',$cityList);
    	//读取功能信息
    	$gongnengList = $this->getType(55);
    	$this->assign('gongnengList',$gongnengList);
    	//读取类型信息
    	$leixingList = $this->getType(60);
    	$this->assign('leixingList',$leixingList);
    	//读取级别信息
    	$jibieList = $this->getType(65);
    	$this->assign('jibieList',$jibieList);
    	//拼接url
    	
    	$quyu_id = 0;
    	$gongneng_id = 0;
    	$leixing_id = 0;
    	$jiebie_id = 0;
    	if(!empty($_GET['quyu_id'])) $quyu_id = $_GET['quyu_id'];
    	if(!empty($_GET['gongneng_id'])) $gongneng_id = $_GET['gongneng_id'];
    	if(!empty($_GET['leixing_id'])) $leixing_id = $_GET['leixing_id'];
    	if(!empty($_GET['jibie_id'])) $jibie_id = $_GET['jibie_id'];

    	$this->assign('quyu_id',$quyu_id);
    	$this->assign('gongneng_id',$gongneng_id);
    	$this->assign('leixing_id',$leixing_id);
    	$this->assign('jibie_id',$jiebie_id);
    	
    	
    	
        $list = $this->getList('Yuanqu', $_GET);
        $this->assign('list', $list);
       
        $this->display();
    }
    /**
     * 厂房列表
     */
    public function rental()
    {
        $list = $this->getList('Changfang', $_GET);
        $this->assign('list', $list);
        $this->display();      
    }
    /**
     * 土地列表
     */
    public function tudi()
    {
        $list = $this->getList('Tudi', $_GET);
        $this->assign('list', $list);
        $this->display(); 
    }
    /**
     * 楼宇列表
     */
    public function louyu()
    {
        $list = $this->getList('Louyu', $_GET);
        $this->assign('list', $list);
        $this->display();
    }
    /**
     * 新闻列表
     */
    public function news()
    {
        $list = $this->getList('News', $_GET);
        $this->assign('list', $list);
        $this->display();
    }
    /**
     * 留言列表
     */
    public function message()
    {
        $list = $this->getList('Liuyuan', $_GET);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 服务列表
     */
    public function server()
    {
        $list = $this->getList('Fuwu', $_GET);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 税务规划列表
     */
    public function taxation()
    {
        $list = $this->getList('Suiwu', $_GET);
        $this->assign('list', $list);
        $this->display();
    }
    
    public function getCity(){
    	//查询城市名称
    	$c_id = cookie('c_id');
    	if(empty($c_id)){
    		$c_id = 40;
    	}
    	$city_name = M('CategoryTree')->field("title")->where("id = $c_id")->find();
    	$this->assign('city_name',$city_name);
    	
    	$channel = $this->getChannel();
    	$this->assign('channel', $channel);
    	$cityList = $this->getType(39);
    	$this->assign("cityList",$cityList);
    	$this->display('city');
    }
    /**
     * 拼接Url
     */
    public function  getUrl(){
    	$type = I('post.type');
    	$id = I('post.id');
    	$url = U('Index/gion');
    	static $data = array();
    	//点击谁就将谁保存到数组中，如果有一样的则替换，没有一样的就添加
    	if($type=='quyu_id'){
    		$data['quyu_id'] = $id;
    	}
    	if($type=='gongneng_id'){
    		$data['gongneng_id'] = $id;
    	}
    	pre($data);
    	
    	
    }
    
    
}