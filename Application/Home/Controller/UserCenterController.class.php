<?php
namespace Home\Controller;
use Think\Controller;
class UserCenterController extends HomeController{
    /**
     * 构造函数，检测用户登录状态
     */
    public function __construct(){
        parent::__construct();
        $this->login();
    }
    /**
     * index()，显示用户中心页面
     */
    public function index(){

        $this->user();
    }
    /**
     * 用户中心
     */
    public function user(){
        print_r(session('userInfo'));exit;
        $this->assign('userInfo',$this->userInfo);
        $this->display();
    }
    public function delete(){
        //$data['id'] = 3;
        $data['title'] = 'charu测试';
        echo insert('Yuanqu',$data);
    }
}