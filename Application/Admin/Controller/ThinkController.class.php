<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 模型数据管理控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class ThinkController extends AdminController {

    /**
     * 显示指定模型列表数据
     * @param  String $model 模型标识
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function lists($model = null, $p = 0){
        $model || $this->error('模型名标识必须！');
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据

        //获取模型信息
        $model = M('Model')->getByName($model);
        $model || $this->error('模型不存在！');

        //解析列表规则
        $fields = array();
        $grids  = preg_split('/[;\r\n]+/s', trim($model['list_grid']));
        foreach ($grids as &$value) {
        	if(trim($value) === ''){
        		continue;
        	}
            // 字段:标题:链接
            $val      = explode(':', $value);
            // 支持多个字段显示
            $field   = explode(',', $val[0]);
            $value    = array('field' => $field, 'title' => $val[1]);
            if(isset($val[2])){
                // 链接信息
                $value['href']	=	$val[2];
                // 搜索链接信息中的字段信息
                preg_replace_callback('/\[([a-z_]+)\]/', function($match) use(&$fields){$fields[]=$match[1];}, $value['href']);
            }
            if(strpos($val[1],'|')){
                // 显示格式定义
                list($value['title'],$value['format'])    =   explode('|',$val[1]);
            }
            foreach($field as $val){
                $array	=	explode('|',$val);
                $fields[] = $array[0];
            }
        }
        // 过滤重复字段信息
        $fields =   array_unique($fields);
        // 关键字搜索
        $map	=	array();
        $key	=	$model['search_key']?$model['search_key']:'title';
        if(isset($_REQUEST[$key])){
            $map[$key]	=	array('like','%'.$_GET[$key].'%');
            unset($_REQUEST[$key]);
        }
        // 条件搜索
        foreach($_REQUEST as $name=>$val){
            if(in_array($name,$fields)){
                $map[$name]	=	$val;
            }
        }
        $row    = empty($model['list_row']) ? 10 : $model['list_row'];

        //读取模型数据列表
        if($model['extend']){
            $name   = get_table_name($model['id']);
            $parent = get_table_name($model['extend']);
            $fix    = C("DB_PREFIX");

            $key = array_search('id', $fields);
            if(false === $key){
                array_push($fields, "{$fix}{$parent}.id as id");
            } else {
                $fields[$key] = "{$fix}{$parent}.id as id";
            }

            /* 查询记录数 */
            $count = M($parent)->join("INNER JOIN {$fix}{$name} ON {$fix}{$parent}.id = {$fix}{$name}.id")->where($map)->count();

            // 查询数据
            $data   = M($parent)
                ->join("INNER JOIN {$fix}{$name} ON {$fix}{$parent}.id = {$fix}{$name}.id")
                /* 查询指定字段，不指定则查询所有字段 */
                ->field(empty($fields) ? true : $fields)
                // 查询条件
                ->where($map)
                /* 默认通过id逆序排列 */
                ->order("{$fix}{$parent}.id DESC")
                /* 数据分页 */
                ->page($page, $row)
                /* 执行查询 */
                ->select();

        } else {
            if($model['need_pk']){
                in_array('id', $fields) || array_push($fields, 'id');
            }
            $name = parse_name(get_table_name($model['id']), true);
            $data = M($name)
                /* 查询指定字段，不指定则查询所有字段 */
                ->field(empty($fields) ? true : $fields)
                // 查询条件
                ->where($map)
                /* 默认通过id逆序排列 */
                ->order($model['need_pk']?'id DESC':'')
                /* 数据分页 */
                ->page($page, $row)
                /* 执行查询 */
                ->select();

            /* 查询记录总数 */
            $count = M($name)->where($map)->count();
        }

        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }

        $data   =   $this->parseDocumentList($data,$model['id']);
        $this->assign('model', $model);
        $this->assign('list_grids', $grids);
        $this->assign('list_data', $data);
        $this->meta_title = $model['title'].'列表';
        $this->display($model['template_list']);
    }

    public function del($model = null, $ids=null){
        $model = M('Model')->find($model);
        $model || $this->error('模型不存在！');

        $ids = array_unique((array)I('ids',0));

        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }

        $Model = M(get_table_name($model['id']));
        $map = array('id' => array('in', $ids) );
        if($Model->where($map)->delete()){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author huajie <banhuajie@163.com>
     */
    public function setStatus($model='Document'){
        return parent::setStatus($model);
    }
    
    public function edit($model = null, $id = 0){
        //获取模型信息
        $model = M('Model')->find($model);
        $model || $this->error('模型不存在！');

        if(IS_POST){
            $Model  =   D(parse_name(get_table_name($model['id']),1));
            // 获取模型的字段信息
            $Model  =   $this->checkAttr($Model,$model['id']);
            if($Model->create() && $Model->save()){
                $this->success('保存'.$model['title'].'成功！', U('lists?model='.$model['name']));
            } else {
                $this->error($Model->getError());
            }
        } else {
            $fields     = get_model_attribute($model['id']);

            //获取数据
            $data       = M(get_table_name($model['id']))->find($id);
            $data || $this->error('数据不存在！');

            $this->assign('model', $model);
            $this->assign('fields', $fields);
            $this->assign('data', $data);
            $this->meta_title = '编辑'.$model['title'];
            $this->display($model['template_edit']?$model['template_edit']:'');
        }
    }

    public function add($model = null){
        //获取模型信息
        $model = M('Model')->where(array('status' => 1))->find($model);
        $model || $this->error('模型不存在！');
        if(IS_POST){
            $Model  =   D(parse_name(get_table_name($model['id']),1));
            // 获取模型的字段信息
            $Model  =   $this->checkAttr($Model,$model['id']);
            if($Model->create() && $Model->add()){
                $this->success('添加'.$model['title'].'成功！', U('lists?model='.$model['name']));
            } else {
                $this->error($Model->getError());
            }
        } else {

            $fields = get_model_attribute($model['id']);

            $this->assign('model', $model);
            $this->assign('fields', $fields);
            $this->meta_title = '新增'.$model['title'];
            $this->display($model['template_add']?$model['template_add']:'');
        }
    }

    protected function checkAttr($Model,$model_id){
        $fields     =   get_model_attribute($model_id,false);
        $validate   =   $auto   =   array();
        foreach($fields as $key=>$attr){
            if($attr['is_must']){// 必填字段
                $validate[]  =  array($attr['name'],'require',$attr['title'].'必须!');
            }
            // 自动验证规则
            if(!empty($attr['validate_rule'])) {
                $validate[]  =  array($attr['name'],$attr['validate_rule'],$attr['error_info']?$attr['error_info']:$attr['title'].'验证错误',0,$attr['validate_type'],$attr['validate_time']);
            }
            // 自动完成规则
            if(!empty($attr['auto_rule'])) {
                $auto[]  =  array($attr['name'],$attr['auto_rule'],$attr['auto_time'],$attr['auto_type']);
            }elseif('checkbox'==$attr['type']){ // 多选型
                $auto[] =   array($attr['name'],'arr2str',3,'function');
            }elseif('date' == $attr['type']){ // 日期型
                $auto[] =   array($attr['name'],'strtotime',3,'function');
            }elseif('datetime' == $attr['type']){ // 时间型
                $auto[] =   array($attr['name'],'strtotime',3,'function');
            }
        }
        return $Model->validate($validate)->auto($auto);
    }
    
	public function yuanquadd(){
		$model = M('Model')->where(array('status' => 1))->find(10);
		$model || $this->error('模型不存在！');
		$fields = get_model_attribute(10);
		$this->assign('model', $model);
		$this->assign('fields', $fields);
		$this->meta_title = '新增'.$model['title'];
		//取出城市的数据
		$citylist = M('CategoryTree')->field("title,id,pid")->where("pid = 39")->select();
		$this->assign('citylist',$citylist);
		//读取功能数据
		$gongnengList = M("CategoryTree")->field("title,id,pid")->where("pid = 55")->select();
		$this->assign('gongnengList',$gongnengList);
		//读取类型数据
		$leixingList = M("CategoryTree")->field("title,id,pid")->where("pid = 60")->select();
		$this->assign('leixingList',$leixingList);
		
		//读取级别数据
		$jibieList = M("CategoryTree")->field("title,id,pid")->where("pid = 65")->select();
		$this->assign('jibieList',$jibieList);
		if(IS_POST){
			/*多图片上传*/
			$info = moreUploadImg('photo',1);
			$xiangce = json_encode($info['yuantuUrl']);
			$thumb = json_encode($info['thumbUrl']);
			$data = $_POST;
			$data['create_time'] = time();
			$data['xiangce'] = $xiangce;
			$data['thumb'] = $thumb;
			$data['servers'] = preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", json_encode($_POST['servers']));
			if(M('Yuanqu')->add($data)){
				$this->success('添加成功',U('yuanqulists'));exit;
			}else{
				$this->error('添加失败');
			}
		}
		
		$this->display();
	}
	//园区列表
	public function yuanqulists(){
		//读取园区的信息列表
		$model = M('Model')->where(array('status' => 1))->find(10);
		$model || $this->error('模型不存在！');
		$fields = get_model_attribute(10);
		$this->assign('model', $model);
		$this->meta_title = '列表'.$model['title'];
		
		//读取新闻分来的数据
		$typelist = $this->getType(60);
		$this->assign('typelist',$typelist);
		$leixing_id = $_GET['leixing_id']+0;
		$list = $this->getList('Yuanqu',$leixing_id);
		$this->assign('list',$list);
		$this->display();
	}
	
	//修改
	public function yuanquedit(){
		//取出城市的数据
		$citylist = M('CategoryTree')->field("title,id,pid")->where("pid = 39")->select();
		$this->assign('citylist',$citylist);
		//读取功能数据
		$gongnengList = M("CategoryTree")->field("title,id,pid")->where("pid = 55")->select();
		$this->assign('gongnengList',$gongnengList);
		//读取类型数据
		$leixingList = M("CategoryTree")->field("title,id,pid")->where("pid = 60")->select();
		$this->assign('leixingList',$leixingList);
		//读取级别数据
		$jibieList = M("CategoryTree")->field("title,id,pid")->where("pid = 65")->select();
		$this->assign('jibieList',$jibieList);
		
		$id = $_GET['id']+0;
		//根据id查询相关信息
		$info = M('Yuanqu')->where("id = $id")->find();
		$this->assign('info',$info);
		//将服务信息转成数组
		$servers = json_decode($info['servers']);
		$xiangce = json_decode($info['xiangce']);
		$thumb   = json_decode($info['thumb']);
		$this->assign('thumb',$thumb);
		$this->assign('servers',$servers);
		//修改
		if(IS_POST){
			$data = $_POST;
			if(!empty($_FILES)){
				$info = moreUploadImg('photo',1);
				$xiangce = json_encode($info['yuantuUrl']);
				$thumb = json_encode($info['thumbUrl']);
				$data['xiangce'] = $xiangce;
				$data['thumb'] = $thumb;
			}
			
			$id = $_POST['id'];
			unset($_POST['id']);
			$data['create_time'] = time();
			
			$data['servers'] = preg_replace("#\\\u([0-9a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", json_encode($_POST['servers']));
			if(M('Yuanqu')->where("id = $id")->save($data)!==false){
				$this->success('修改成功',U('yuanqulists'));exit;
			}else{
				$this->error('修改失败');
			}
			
		}

		$this->display();
		
	}
	public function yuanqudel(){
		$id = $_GET['id']+0;
		if(M('Yuanqu')->where("id = $id")->delete()!==false){
			$this->success('删除成功',U('yuanqulists'));exit;
		}else{
			$this->error('参数错误');
		}
		
	}
	/*楼宇经济信息管理,列表*/
	public function louyulists(){
		$typelist = $this->getType(95);
		$this->assign('typelist',$typelist);
		
		$leixing_id = $_GET['leixing_id']+0;
		
		$list = $this->getList('Louyu',$leixing_id);
		$this->assign('list',$list);
		$this->display();
	}
	
	/*楼宇经济信息管理,楼宇添加*/
	public function louyuadd(){
		//读取城市分类信息
		$model = M('CategoryTree');
		$citylist = $model->field("title,id,pid")->where("pid = 39")->select();
		$this->assign('citylist',$citylist);
		//读取类型
		$typelist = $model->field("title,id,pid")->where("pid = 95")->select();
		$this->assign('typelist',$typelist);
		
		if(IS_POST){
			/*多图片上传*/
			$info = moreUploadImg('photo',1);
			$xiangce = json_encode($info['yuantuUrl']);
			$thumb = json_encode($info['thumbUrl']);
			$data = $_POST;
			$data['create_time'] = time();
			$data['xiangce'] = $xiangce;
			$data['thumb'] = $thumb;
			if(M('Louyu')->add($data)){
				$this->success('添加成功',U('yuanqulists'));exit;
			}else{
				$this->error('添加失败');
			}
		}

		$this->display();
		
	}
	/*楼宇经济修改*/
	public function louyuedit(){
		$model = M('CategoryTree');
		$citylist = $model->field("title,id,pid")->where("pid = 39")->select();
		$this->assign('citylist',$citylist);
		//读取类型
		$typelist = $model->field("title,id,pid")->where("pid = 95")->select();
		$this->assign('typelist',$typelist);
		
		//获取id编号
		$id = $_GET['id']+0;
		//查询对应的数据
		$info = M('Louyu')->where("id = $id")->find();
		$this->assign('info',$info);
		//将服务信息转成数组

		$thumb   = json_decode($info['thumb']);
		$this->assign('thumb',$thumb);
		if(IS_POST){
			$data = $_POST;
			if(!empty($_FILES)){
				$info = moreUploadImg('photo',1);
				$xiangce = json_encode($info['yuantuUrl']);
				$thumb = json_encode($info['thumbUrl']);
				$data['xiangce'] = $xiangce;
				$data['thumb'] = $thumb;
			}
			$id = $_POST['id'];
			unset($_POST['id']);
			$data['create_time'] = time();
			if(M('Louyu')->where("id = $id")->save($data)!==false){
				$this->success('修改成功',U('louyulists'));exit;
			}else{
				$this->error('修改失败');
			}
			
		}
		
		$this->display();
		
	}
	public function louyudel(){
		$id = $_GET['id']+0;
		if(M('Louyu')->where("id = $id")->delete()!==false){
			$this->success('删除成功',U('yuanqulists'));exit;
		}else{
			$this->error('参数错误');
		}
		
	}
	
	/*新闻管理中心*/
	public function newslist(){
		//读取新闻分来的数据
		$typelist = M('CategoryTree')->field("id,title,pid")->where("pid = 86")->select();
		$this->assign('typelist',$typelist);
		//获取条件
		$type_id = $_GET['id']+0;
		//读取新闻列表,查询所有信息
		$newslist = $this->getList('News',$type_id);
		$this->assign('newslist',$newslist);
		$this->display();
	}
	//添加新闻
	public function newsadd(){
		//读取新闻分来的数据
		$typelist = M('CategoryTree')->field("id,title,pid")->where("pid = 86")->select();
		$this->assign('typelist',$typelist);
		
		if(IS_POST){
			$data = $_POST;
			if(!empty($_FILES)){
				$info = moreUploadImg('photo',1,170,118);
				$xiangce = json_encode($info['yuantuUrl']);
				$thumb = json_encode($info['thumbUrl']);
				$data['xiangce'] = $xiangce;
				$data['thumb'] = $thumb;
			}
			
			if(empty($data['yueduliang'])){
				$data['yueduliang'] = rand(100,300);
			}
			$data['create_time'] = time();
			if(M('News')->add($data)!==false){
				$this->success('添加成功',U('newslist'));exit;
				
			}else{
				$this->error('系统繁忙，请稍后再试');
			}
			
		}
		$this->display();
	
	}
	//查看新闻信息
	public function newsedit(){
		$typelist = M('CategoryTree')->field("id,title,pid")->where("pid = 86")->select();
		$this->assign('typelist',$typelist);
		$id = $_GET['id']+0;
		$info = M('News')->where("id = $id")->find();
		$this->assign('info',$info);
		$thumb   = json_decode($info['thumb']);
		$this->assign('thumb',$thumb);
		if(IS_POST){
			$id = $_POST['id'];
			$data = $_POST;
			if(!empty($_FILES)){
				$info = moreUploadImg('photo',1,170,118);
				$xiangce = json_encode($info['yuantuUrl']);
				$thumb = json_encode($info['thumbUrl']);
				$data['xiangce'] = $xiangce;
				$data['thumb'] = $thumb;
			}
			unset($data['id']);
			if(empty($data['yueduliang'])){
				$data['yueduliang'] = rand(100,300);
			}
			$data['create_time'] = time();
			if(M('News')->where("id = $id")->save($data)!==false){
				$this->success('修改成功',U('newslist'));exit;
				
			}else{
				$this->error('系统繁忙，请稍后再试');
			}
		}
		$this->display();
	}
	public function newsdel(){
		$id= $_GET['id']+0;
		if(delete('News',array('id'=>$id))!==false){
				$this->success('删除成功',U('Think/newslist'));exit;
				
			}else{
				$this->error('删除失败');
			}	
	}
	
	/*土地信息管理*/
	public function tudilist(){
		//读取栏目类型
		$typelist = $this->getType(106);
		$this->assign('typelist',$typelist);
		$leixing_id = $_GET['leixing_id']+0;
		$list = $this->getList('Tudi',$leixing_id);
		$this->assign('list',$list);
		$this->display();
	}
	public function tudiadd(){
		//获取城市信息
		$citylist = $this->getType(39);
		$this->assign('citylist',$citylist);
		//读取用途信息
		$yongtulist = $this->getType(106);
		$this->assign('yongtulist',$yongtulist);
		if(IS_POST){
			/*多图片上传*/
			$data = $_POST;
			$info = moreUploadImg('photo',1);
			$xiangce = json_encode($info['yuantuUrl']);
			$thumb = json_encode($info['thumbUrl']);
			$data['create_time'] = time();
			$data['xiangce'] = $xiangce;
			$data['thumb'] = $thumb;
			$data['update_time'] = time();
			if(insert('Tudi', $data)){
				$this->success('添加成功',U('Think/tudilist'));exit;
			}else{
				$this->error('系统繁忙，请稍后再试');
			}
		}
		$this->display();
		
	}
	public function tudiedit(){
		$id = $_GET['id']+0;
		//获取城市信息
		$citylist = $this->getType(39);
		$this->assign('citylist',$citylist);
		//读取用途信息
		$yongtulist = $this->getType(106);
		$this->assign('yongtulist',$yongtulist);
		//根据id查询一条数据
		$info = M('Tudi')->where("id = $id")->find();
		$this->assign("info",$info);
		$thumb   = json_decode($info['thumb']);
		$this->assign('thumb',$thumb);
		if(IS_POST){
			$data = $_POST;
			if(!empty($_FILES)){
				$info = moreUploadImg('photo',1);
				$xiangce = json_encode($info['yuantuUrl']);
				$thumb = json_encode($info['thumbUrl']);
				$data['xiangce'] = $xiangce;
				$data['thumb'] = $thumb;
			}
			
			$id = $_POST['id'];
			$data['update_time'] = time();
			if(update('Tudi',$data)!==false){
				$this->success('修改成功',U('tudilist'));exit;
			}else{
				$this->error('系统繁忙！');
			}
		}
		$this->display();
		
	}
	
	public function tudidel(){
		$id = $_GET['id']+0;
		if(delete('Tudi',array('id'=>$id))!==false){
			$this->success('删除成功',U('Think/tudilist'));exit;
			
		}else{
			$this->error('删除失败');
		}
	}
	/**
	 * 厂房信息管理
	 */
	public function changfanglist(){
		$leixing_id = $_GET['leixing_id']+0;
		$typelist = $this->getType(130);
		$this->assign('typelist',$typelist);
		$list = $this->getList('Cangfang',$leixing_id);
		$this->assign('list',$list);
		$this->display();
	}
	public function changfangadd(){
		//获取城市信息
		$citylist = $this->getType(39);
		$this->assign('citylist',$citylist);
		//获取类型信息
		$yongtulist = $this->getType(130);
		$this->assign('yongtulist',$yongtulist);
		
		if(IS_POST){
			/*多图片上传*/
			$data = $_POST;
			$info = moreUploadImg('photo',1);
			$xiangce = json_encode($info['yuantuUrl']);
			$thumb = json_encode($info['thumbUrl']);
			$data['create_time'] = time();
			$data['xiangce'] = $xiangce;
			$data['thumb'] = $thumb;
			$data['update_time'] = time();
			$data['bianhao'] = 'CF'.uniqid();
			if(insert('Cangfang', $data)){
				$this->success('添加成功',U('Think/tudilist'));exit;
			}else{
				$this->error('系统繁忙，请稍后再试');
			}
		}
		
		$this->display();
	}
	
	public function changfangedit(){
		$id = $_GET['id']+0;
		//获取城市信息
		$citylist = $this->getType(39);
		$this->assign('citylist',$citylist);
		//获取类型信息
		$yongtulist = $this->getType(130);
		$this->assign('yongtulist',$yongtulist);
		//根据id查询一条数据
		$info = M('Cangfang')->where("id = $id")->find();
		$this->assign("info",$info);
		$thumb   = json_decode($info['thumb']);
		$this->assign('thumb',$thumb);
		if(IS_POST){
			$id = $_POST['id'];
			$data = $_POST;
			if(!empty($_FILES)){
				$info = moreUploadImg('photo',1);
				$xiangce = json_encode($info['yuantuUrl']);
				$thumb = json_encode($info['thumbUrl']);
				$data['xiangce'] = $xiangce;
				$data['thumb'] = $thumb;
			}
			if(update('Cangfang',$data)!==false){
				$this->success('修改成功',U('changfanglist'));exit;
			}else{
				$this->error('系统繁忙！');
			}
		}
		$this->display();
	}
	
	public function changfangdel(){
		$id = $_GET['id']+0;
		if(delete('Cangfang',array('id'=>$id))!==false){
			$this->success('删除成功',U('Think/tudilist'));exit;
				
		}else{
			$this->error('删除失败');
		}
		
	}
	/**
	 * 企业服务信息管理开始
	 * serveradd() 企业服务增加记录
	 * serverlist()企业服务列表
	 * serveredit()企业服务修改
	 * serverdel() 企业服务删除
	 */
	public function serverlist(){
		//读取分类信息
		$typelist = $this->getType(133);
		$this->assign('typelist',$typelist);
		$type_id = $_GET['id']+0;
		//读取新闻列表,查询所有信息
		$newslist = $this->getList('Fuwu',$type_id);
		$this->assign('newslist',$newslist);
		$this->display();
	}
	
	public function serveradd(){
		//读取新闻分来的数据
		$typelist = $this->getType(133);
		$this->assign('typelist',$typelist);
		
		if(IS_POST){
			$data = $_POST;
			if(!empty($_FILES)){
				$info = moreUploadImg('photo',1,240,150);
				$xiangce = json_encode($info['yuantuUrl']);
				$thumb = json_encode($info['thumbUrl']);
				$data['xiangce'] = $xiangce;
				$data['thumb'] = $thumb;
			}
			$data['create_time'] = time();
			if(M('Fuwu')->add($data)!==false){
				$this->success('添加成功',U('serverlist'));exit;
		
			}else{
				$this->error('系统繁忙，请稍后再试');
			}
				
		}
		$this->display();
	}
	public function serveredit(){
		$typelist = $this->getType(133);
		$this->assign('typelist',$typelist);
		$id = $_GET['id']+0;
		$info = M('Fuwu')->where("id = $id")->find();
		$this->assign('info',$info);
		$thumb   = json_decode($info['thumb']);
		$this->assign('thumb',$thumb);
		if(IS_POST){
			$id = $_POST['id'];
			$data = $_POST;
			if(!empty($_FILES)){
				$info = moreUploadImg('photo',1,170,118);
				$xiangce = json_encode($info['yuantuUrl']);
				$thumb = json_encode($info['thumbUrl']);
				$data['xiangce'] = $xiangce;
				$data['thumb'] = $thumb;
			}
			unset($data['id']);
			$data['create_time'] = time();
			if(M('Fuwu')->where("id = $id")->save($data)!==false){
				$this->success('修改成功',U('serverlist'));exit;
		
			}else{
				$this->error('系统繁忙，请稍后再试');
			}
		}
		
		$this->display();
	}
	
	public function serverdel(){
		$id = $_GET['id']+0;
		if(delete('Fuwu',array('id'=>$id))!==false){
			$this->success('删除成功',U('Think/serverlist'));exit;
		
		}else{
			$this->error('删除失败');
		}
		
	}
	
	public function shuiwulist(){
		//读取分类信息
		$typelist = $this->getType(140);
		$this->assign('typelist',$typelist);
		$type_id = $_GET['id']+0;
		//读取新闻列表,查询所有信息
		$newslist = $this->getList('shuiwuguihua',$type_id);
		$this->assign('newslist',$newslist);
		$this->display();
		
	}
	public function shuiwuadd(){
		$typelist = $this->getType(140);
		$this->assign('typelist',$typelist);
		if(IS_POST){
			$data = $_POST;
			$data['create_time'] = time();
			if(M('shuiwuguihua')->add($data)!==false){
				$this->success('添加成功',U('Think/shuiwulist'));exit;
				
			}else{
				$this->error('添加失败');
			}
		}
		$this->display();
	}
	public function shuiwuedit(){
		$id = $_GET['id']+0;
		$typelist = $this->getType(140);
		$this->assign('typelist',$typelist);
		$info = M('Shuiwuguihua')->where("id = $id")->find();
		$this->assign('info',$info);
		if(IS_POST){
			$data = $_POST;
			if(M('Shuiwuguihua')->where("id = $id")->save($data)!==false){
				$this->success('修改成功',U('shuiwulist'));exit;
			
			}else{
				$this->error('系统繁忙，请稍后再试');
			}
		}
		$this->display();
	}
	
	public function shuiwudel(){
		$id = $_GET['id']+0;
		if(delete('Shuiwuguihua',array('id'=>$id))!==false){
			$this->success('删除成功',U('Think/shuiwulist'));exit;
		}else{
			$this->error('删除失败');
		}
	
	}
	
	
	
	
	/**
	 * 获取分类信息
	 * @param unknown $pid
	 * @return Ambigous <\Think\Model, \Think\Model>
	 */
	function getType($pid){
		return M('CategoryTree')->field("id,title,pid")->where("pid = $pid")->select();
	}
	
	
	/**
	 * 获取地区的方法
	 */
	public function getQuyu(){
		$id = I('post.id')+0;
		$quyu_id = I('post.quyu_id');
		$info =  M('CategoryTree')->field('title,id,pid')->where("pid = $id")->select();
		$str = '';
		$str .= '<select name="quyu_id" style="float:left; width:100px;">';
		$str .= '<option value="">请选择区域</option>';
		$selected = "";
		foreach($info as $v){
			if($v[id] ==$quyu_id){
				$selected = "selected=selected";
			}
			$str .='<option value="'.$v['id'].'"'.$selected.'>'.$v['title'].'</option>';
		}
		$str .= '</select>';
		echo $str;
	}
	/**
	 * 获取类型的方法
	 * @param unknown $tableName
	 * @param number $leixing_id 类型id
	 */
	function getList($tableName,$leixing_id=0){
		if(empty($leixing_id)){
			return  M($tableName)->field("a.*,b.title as type_title")->join("a left join qifan_category_tree b on a.leixing_id = b.id")->order("a.create_time desc")->select();
		}else{
			return  M($tableName)->field("a.*,b.title as type_title")->join("a left join qifan_category_tree b on a.leixing_id = b.id")->where("a.leixing_id = $leixing_id")->order("a.create_time desc")->select();
		}
		
	}
	
}