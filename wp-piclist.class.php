<?
session_start();
if (!class_exists("wp_piclist")) {
	class wp_piclist{
		function wp_piclist(){
			
		}
		
		public function get_image_size($argsize){
			switch($argsize){
				case 1:	$argsize = 'thumbnail';break;
				case 2: $argsize = 'medium';break;
				//case 4: $arg = 'wppl_size_custom';break;
			}	
			return $argsize;
		}
		
		//插件输出
		function display(){
			if(is_single() || is_page()){
				$wpl_size = '';
				global $post;
				//获取初始化数据
				$wppl_size = $this->get_image_size(get_option('wppl_size','1'));
				$wppl_priority = get_option('wppl_priority','1');
		
				$args = array(
				    'post_type' => 'attachment',
				    'numberposts' => -1, // bring them all
				    'post_status' => null,
				    'post_parent' => $post->ID // post id with the gallery
				    ); 
				
				$atts = get_posts($args);
				$ns = count($atts);
				for($i=0;$i<$ns;$i++){ 
					$li .= '<li><a href="'.$atts[$i]->guid.'" title="" class="thickbox" rel="gallery-plants">'.wp_get_attachment_image($atts[$i]->ID,$wppl_size,0,array("alt"=>"Plant ".($i+1))).'</a></li>';
				}
				$html = '<div id="wp-piclist"><ul>'.$li.'</ul></div>';
				return $html;
			}
		}
		
		//管理员界面
		function config_page(){
			//检查是否存在提交数据，如果提交则进行修改
			if($_POST['flag']){
				update_option('wppl_style', $_POST['show_type']);//显示模式
				update_option('wppl_size', $_POST['show_size']);
				if($_POST['show_size']=='4'){
					add_image_size('wppl_size_custom',$_POST['c_width'],$_POST['c_height'],true);
					update_option('wppl_width', $_POST['c_width']);
					update_option('wppl_height', $_POST['c_height']);
				}
				update_option('wppl_priority', $_POST['quanzhong']);
				update_option('wppl_global',$_POST['gtype']);
			}
			//获取管理界面模板数据
			//现实方式
			$admin_style = get_option('wppl_style','1');
			$admin_size = get_option('wppl_size','1');
			$admin_priority = get_option('wppl_priority','1');
			$admin_global = get_option('wppl_global','1');
			
			$html_style ='<div class="bor"><input name="show_type" type="radio" value="1" '.(($admin_style==1)?'checked="checked"':'').' /> <b>默认位置</b> <span class="msg" id="am_show_type_n">默认位置一般为文章页的正文底部。Default in the text at the bottom.</span></div>
						        <div class="bor"><input name="show_type" type="radio" value="2" '.(($admin_style==2)?'checked="checked"':'').'/> <b>自定义</b> <span class="msg" id="am_show_type_n">请 <b><&#63; wp_piclsit_tag(); &#63;></b> 将标签放置在你模版需要展示的地方。The tag into your template file</span></div>';
		
			$html_size = '
					<div class="bor">
						<input name="show_size" type="radio" value="1" '.(($admin_size==1)?'checked="checked"':'').' /> 小图规格(thumbnail) 
						<input name="show_size" type="radio" value="2" '.(($admin_size==2)?'checked="checked"':'').'/> 中等规格(medium) 
					</div>';
					
			$html_global1 = ($admin_global=='1')?"checked":'';
			$html_global2 = ($admin_global=='2')?"checked":'';
			
			/* 暂时关闭自定义图片规格
			$html_size = '
					<div class="bor">
						<input name="show_size" type="radio" value="1" '.(($admin_size==1)?'checked="checked"':'').' /> 小图规格(thumbnail) 
						<input name="show_size" type="radio" value="2" '.(($admin_size==2)?'checked="checked"':'').'/> 中等规格(medium) 
					</div>
					<div class="c_size bor">
						<input name="show_size" type="radio" value="4" id="c_cc" '.(($admin_size==4)?'checked="checked"':'').' /> 自定义(custom)
						<span id="c_frame" '.(($admin_size!=4)?'style="display:none;"':'').'>
							<input name="c_width" type="text" size="4" '.(($admin_size==4)?'value="'.get_option('wppl_width','150').'"':'').'" /> 宽(width)/PX 
							<input name="c_height" type="text" size="4" '.(($admin_size==4)?'value="'.get_option('wppl_height','150').'"':'').'" /> 高(height)/PX
						</span>
					</div>';
			*/		
			$html_priority = '<input type="text" size=3 name="quanzhong" value="'.$admin_priority.'" />';
			$str = file_get_contents(dirname(__FILE__).'/tpl/admin.tpl');
			$str = str_replace(array('~style~','~size~','~priority~','~global1~','~global2~'), array($html_style,$html_size,$html_priority,$html_global1,$html_global2), $str);
			return '<link rel="stylesheet" type="text/css" media="all" href="'.wp_piclist_patch.'wp-piclist-admin.css" />'.$str; 
		}
	}
}