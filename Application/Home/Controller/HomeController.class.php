<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {
    

    /**
     * 首页各栏目显示记录数量
     */
    protected  $count = 8;
    
    /**
     * 设置每页记录数量
     *
     * @var unknown
     */
    protected  $pagesize = 10;
    /**
     * 排序条件
     * @var array $order
     */
    protected  $order = array('id' => 'desc');
    /**
     * 用户登录之后的用户信息
     * @var unknown
     */
    protected $userInfo = array();

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}


    protected function _initialize(){
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
    }

	/* 用户登录检测 */
	protected function login(){
		/* 用户登录检测 */
	    if(is_login()){
	        $this->userInfo = session('userInfo');
	    }else{
	        $this->error('您没有权限访问，请先登陆',U('User/login'));
	    }
	}
	/**
	 * 根据模型名字获取对应表中title数据
	 * @param unknown $modelName
	 */
	protected function getTitle($modelName,$pid){
	    $leixingmodel = D('CategoryTree');
	    $model = D($modelName);
	    $idList = $leixingmodel->getIdByPid($pid);	    
	    foreach ($idList as $v) {
	        $list[$v['id']] = $model->getTitle('leixing_id =' . $v['id'],$this->order, $this->count);
	    }
	    $list['total'] = $model->getTitle('',$this->order, $this->count);
	    return $list;
	    
	}
	/**
	 * 根据请求条件获取对应模型的数据
	 * @param unknown $modelName
	 * @param unknown $request
	 */
	protected  function getList($modelName,$request){
	    $model = D($modelName);
	    $categorymodel = D('CategoryTree');
	    $city_id = isset($request['city_id']) ? $request['city_id'] : 0;
	    $gongneng_id = isset($request['gongneng_id']) ? $request['gongneng_id'] : 0;
	    $leixing_id = isset($request['leixing_id']) ? $request['leixing_id'] : 0;
	    $jibie_id = isset($request['jibie_id']) ? $request['jibie_id'] : 0;
	    $quyu_id = isset($request['quyu_id']) ? $request['quyu_id'] : 0;
	    $zujin = isset($request['zujin']) ? $request['zujin'] :0;
	    $mianji = isset($request['mianji']) ? $request['mianji'] :0;
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
	    if(!empty($zujin)){
	        $str = $categorymodel->getTitleById($zujin,71);
	        $arr = explode('-',$str);
	        $where .= ' AND `zujin` > '.$arr[0].' AND `zujin` < '.$arr[1];
	    }
	    if(!empty($mianji)){
	        $str = $categorymodel->getTitleById($mianji,71);
	        $arr = explode('-',$str);
	        $where .= ' AND `mianji` > '.$arr[0].' AND `mianji` < '.$arr[1];
	    }
	    return $model->getList($where, $page, $this->order, $this->pagesize);
	}
	/**
	 * 根据id获取对应model数据的详情
	 * @param unknown $modelName
	 * @param unknown $id
	 */
	protected function getDetailsById($modelName,$id){
	    $model = D($modelName);
	    return $model->getDetailsById($id);
	}
	/**
	 * 
	 * @return unknown
	 * 获取导航信息
	 */
	public function getChannel(){
		$channel = D('Channel')->field("title,url")
		->order('sort asc')
		->select();
		return  $channel;
	}
	/**
	 * 
	 * @param unknown $pid
	 * @return Ambigous <\Think\mixed, boolean, string, NULL, multitype:, unknown, mixed, object>
	 * 获取筛选条件信息
	 */
	function getType($pid){
		return M('CategoryTree')->field("id,title,pid")->where("pid = $pid")->select();
	}
}
