<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>|OneThink管理平台</title>
    <link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/module.css">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
     <!--[if lt IE 9]>
    <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <!-- Logo -->
        <span class="logo"></span>
        <!-- /Logo -->

        <!-- 主导航 -->
        <ul class="main-nav">
            <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (U($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <!-- /主导航 -->

        <!-- 用户栏 -->
        <div class="user-bar">
            <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
            <ul class="nav-list user-menu hidden">
                <li class="manager">你好，<em title="<?php echo session('user_auth.username');?>"><?php echo session('user_auth.username');?></em></li>
                <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
                <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
                <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
            </ul>
        </div>
    </div>
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar">
        <!-- 子导航 -->
        
            <div id="subnav" class="subnav">
                
                
                    <!-- 子导航 -->
                   
                   <?php if(!empty($key)): ?><h3><i class="icon icon-unfold"></i>栏目内容管理</h3><?php endif; ?>
                        <ul class="side-sub-menu">
                         		<li>
                                    <a class="item" href="<?php echo U('Think/yuanqulists',array('id'=>10))?>">产业园区</a>
                                </li>

                                <li>
                                    <a class="item" href="<?php echo (U($menu["url"])); ?>">楼宇经济</a>
                                </li>
                                <li>
                                    <a class="item" href="<?php echo (U($menu["url"])); ?>">厂房租售</a>
                                </li>
                                
                                <li>
                                    <a class="item" href="<?php echo (U($menu["url"])); ?>">土地</a>
                                </li>
                            
                        </ul>
                    
                    <!-- /子导航 -->
               
            </div>
        
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content">
        <div id="top-alert" class="fixed alert alert-error" style="display: none;">
            <button class="close fixed" style="margin-top: 4px;">&times;</button>
            <div class="alert-content">这是内容</div>
        </div>
        <div id="main" class="main">
            
            <!-- nav -->
            <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                <span>您的位置:</span>
                <?php $i = '1'; ?>
                <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                    <?php else: ?>
                    <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                    <?php $i = $i+1; endforeach; endif; ?>
            </div><?php endif; ?>
            <!-- nav -->
            

            
<script type="text/javascript" src="/Public/static/uploadify/jquery.uploadify.min.js"></script>
    <div class="main-title cf">
        <h2>新增 [<?php echo ($model['title']); ?>]</h2>
    </div>
    <!-- 标签页导航 -->
<div class="tab-wrap">
    <ul class="tab-nav nav">
			<?php $_result=parse_config_attr($model['field_group']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($i % 2 );++$i;?><li data-tab="tab<?php echo ($key); ?>" <?php if(($key) == "1"): ?>class="current"<?php endif; ?>><a href="javascript:void(0);"><?php echo ($group); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
    <div class="tab-content">
    <!-- 表单 -->
    <style type='text/css'>
    	td{ cellpadding:10px; height:50px; line-height:50px;}
    	td input[type=text]{ border:1px solid #ccc; height:35px; width:300px;}
    	td span{ padding:0 20px;}
    	
    
    </style>
    <form name='form1' action="/admin.php?s=/Think/yuanquadd" method = 'post' enctype="multipart/form-data">
    	<table width='100%' cellpadding = '10'>
    		<tr>
    			<td>选择城市：</td>
    			<td>
    				<p><select name="city_id" onchange="getDiqu(this.value)" style="float:left; margin-right:20px;">
    					<option value="40">请选择城市</option>
    					<?php foreach($citylist as $v){?>
    						<option value="<?php echo $v['id']?>"><?php echo $v['title']?></option>
    					<?php } ?>
    				</select>
    				<p>
    				
    				<p id = 'quyu'></p>
    			</td>
    		</tr>
    		
    		<tr>
    			<td>选择功能</td>
    			<td>
    			<select name="gongneng_id">
    				<option value="">请选择功能</option>
    				<?php foreach($gongnengList as $v){?>
    				
    					<option value="<?php echo $v['id']?>"><?php echo $v['title']?></option>
    				<?php } ?>
    			</select>
    			
    			</td>
    		
    		</tr>
    		
    		<tr>
    			<td>选择类型</td>
    			<td>
    			
    			<select name="leixing_id">
    				<option value="">请选择类型</option>
    				<?php foreach($leixingList as $v){?>
    					<option value="<?php echo $v['id']?>"><?php echo $v['title']?></option>
    				<?php } ?>
    			</select>
    				
    			</td>
    		</tr>
    		
    		<tr>
    			<td>选择级别</td>
    			<td>
    				<select name="jibie_id">
    				<option value="">请选择级别</option>
    				<?php foreach($jibieList as $v){?>
    					<option value="<?php echo $v['id']?>"><?php echo $v['title']?></option>
    				<?php } ?>
    			</select>
    			
    			</td>
    			
    		</tr>
    	
    		
    	
    		<tr>
    			<td width="10%">标题：</td>
    			<td><input type="text" name="title"></td>
    		</tr>
    		<tr>
    			<td>相册</td>
    			<td>
    				<table width="90%" id="album-table">
			          <tr>
			            <td><a href="javascript:"  onclick="copyimg(this)">[+]</a>上传文件：</td>
			            <td><input type="file" name="photo[]"/></td>
			          </tr>
			        </table>
    			
    			</td>
    		</tr>
    		
    		<tr>
    			<td>推荐指数：</td>
    			<td><input type="text" name="leave"></td>
    		</tr>
    		
    		<tr>
    			<td>企业服务：</td>
    			<td>
    				<input type="text" name="servers[]">
    				<input type="text" name="servers[]">
    				<input type="text" name="servers[]">
    			</td>
    		</tr>
    		<tr>
    			<td>企业优势标示语</td>
    			<td>
	    			<span><input type="radio" name="is_biaoshi" value="1" />是</span>
	    			<span><input type="radio" name="is_biaoshi" value="0" />否</span>
    			</td>
    		</tr>
    	
    		<tr>
    			<td>主导产业</td>
    			<td>
	    			<input type="text" name="chanye" value="" />
    			</td>
    		</tr>
    		
    		<tr>
    			<td>基础设施</td>
    			<td>
	    			<input type="text" name="sheshi" value="" />
    			</td>
    		</tr>
    		
    		<tr>
    			<td>园区面积</td>
    			<td>
	    			<input type="text" name="mianji" value="" />
    			</td>
    		</tr>
    		
    		<tr>
    			<td>土地价格</td>
    			<td>
	    			<input type="text" name="jiage" value="" />
    			</td>
    		</tr>
    		
    		<tr>
    			<td>投资</td>
    			<td>
	    			<input type="text" name="touzi" value="" />
    			</td>
    		</tr>
    		
    		<tr>
    			<td>税收</td>
    			<td>
	    			<input type="text" name="shuishou" value="" />
    			</td>
    		</tr>
    		
    		<tr>
    			<td>园区厂房</td>
    			<td>
	    			<input type="text" name="changfang" value="" />
    			</td>
    		</tr>
    		
    		<tr>
    			<td>租赁价格</td>
    			<td>
	    			<input type="text" name="zulinjiage" value="" />
    			</td>
    		</tr>
    		
    		<tr>
    			<td>地址</td>
    			<td>
	    			<input type="text" name="dizhi" value="" />
    			</td>
    		</tr>
    		
    		<tr>
    			<td>园区介绍</td>
    			<td>
                 <textarea id="jieshao" name="jieshao" style="height:180px; width:100%"> </textarea>
    			</td>
    		</tr>
    		
    		
    		
    		<tr>
    			<td>入驻企业</td>
    			<td>
	    			<textarea id="ruzhuqiye" name='ruzhuqiye' style="height:180px; width:100%"> </textarea>
    			</td>
    		</tr>
    		
    		<tr>
    			<td>园区政策</td>
    			<td>
	    			<textarea id="zhengce" name="zhengce" style="height:180px; width:100%"> </textarea>
    			</td>
    		</tr>
    		
    		<tr>
    			<td>配套</td>
    			<td>
	    			<textarea id="peitao" name='peitao' style="height:180px; width:100%"> </textarea>
    			</td>
    		</tr>
    	</table>
    	
    	<div class="form-item cf">
            <button class="btn submit-btn "  type="submit">确 定</button>
            <a class="btn btn-return" href="javascript:;">返 回</a>
        </div>
        
        </form>
    
    </div>
</div>

        </div>
        <div class="cont-ft">
            <div class="copyright">
                <div class="fl">感谢使用<a href="http://www.onethink.cn" target="_blank">OneThink</a>管理平台</div>
                <div class="fr">V<?php echo (ONETHINK_VERSION); ?></div>
            </div>
        </div>
    </div>
    <!-- /内容区 -->
    <script type="text/javascript">
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "", //当前网站地址
            "APP"    : "/admin.php?s=", //当前项目地址
            "PUBLIC" : "/Public", //项目公共目录地址
            "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
    </script>
    <script type="text/javascript" src="/Public/static/think.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/common.js"></script>
    <script type="text/javascript">
        +function(){
            var $window = $(window), $subnav = $("#subnav"), url;
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 130);
            }).resize();

            /* 左边菜单高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            $subnav.find("a[href='" + url + "']").parent().addClass("current");

            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(){
                var $this = $(this);
                $this.find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                      prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });

            $("#subnav h3 a").click(function(e){e.stopPropagation()});

            /* 头部管理员菜单 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

	        /* 表单获取焦点变色 */
	        $("form").on("focus", "input", function(){
		        $(this).addClass('focus');
	        }).on("blur","input",function(){
				        $(this).removeClass('focus');
			        });
		    $("form").on("focus", "textarea", function(){
			    $(this).closest('label').addClass('focus');
		    }).on("blur","textarea",function(){
			    $(this).closest('label').removeClass('focus');
		    });

            // 导航栏超出窗口高度后的模拟滚动条
            var sHeight = $(".sidebar").height();
            var subHeight  = $(".subnav").height();
            var diff = subHeight - sHeight; //250
            var sub = $(".subnav");
            if(diff > 0){
                $(window).mousewheel(function(event, delta){
                    if(delta>0){
                        if(parseInt(sub.css('marginTop'))>-10){
                            sub.css('marginTop','0px');
                        }else{
                            sub.css('marginTop','+='+10);
                        }
                    }else{
                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
                            sub.css('marginTop','-'+(diff-10));
                        }else{
                            sub.css('marginTop','-='+10);
                        }
                    }
                });
            }
        }();
    </script>
    

<link href="/Public/Uedietor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="/Public/Uedietor/third-party/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Uedietor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Uedietor/umeditor.min.js"></script>
<script type="text/javascript" src="/Public/Uedietor/lang/zh-cn/zh-cn.js"></script>


<script type="text/javascript">
    //实例化编辑器
    var um = UM.getEditor('jieshao');
    var zc = UM.getEditor('zhengce');
    var zc = UM.getEditor('ruzhuqiye');
    var zc = UM.getEditor('peitao');
    
    
    function copyimg(o){
    	 //取出当前行
    	    var current_tr = $(o).parent().parent();
    	    if($(o).html()=='[+]'){
    	            //自我复制
    	            var new_tr  = current_tr.clone();//克隆当前行，
    	            //克隆出的新行，把里面的a标签的内容，变成[-]
    	            new_tr.find('a').html('[-]');
    	            //把新行放到当前行的后面
    	             current_tr.after(new_tr);
    	    }else{
    	            //自我删除当前行
    	            current_tr.remove();
    	    }

    	}
    
 
	function getDiqu(v){
		//ajax 请求当前下的区域数据
		$.ajax({
			type:'post',
			url:"<?php echo U('Think/getQuyu')?>",
			data:'id='+v,
			success:function(msg){
				$("#quyu").html(msg);
			}
		});
		
		
	}



</script>




</body>
</html>