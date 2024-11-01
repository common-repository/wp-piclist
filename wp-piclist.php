<?php
session_start();
/*
Plugin Name: WP PIClist
Plugin URI: http://webcaft.com/wp-piclist-plugin
Description: wordpress自带图片集增强插件
Version: 0.2.1b
Author: 风来西林
Author URI: http://webcaft.com
*/


/*
可配置的内容项目
1、wppl_style 显示方式：1、标签；2、默认文章结束
2、wppl_size 显示类型：1、缩略图thumbnail; 
											 2、中等规格(medium) 
											 3、原始规格(large)
											 4、自定义(custom)
											 		wppl_width 宽度
											 		wppl_height 高度
3、wppl_priority 插件权重
4、是否全局使用插件: 1、使用; 2、不使用
*/

require_once("wp-piclist.class.php");

define('wp_piclist_patch',get_bloginfo('home').'/wp-content/plugins/wp-piclist/'); 

function wp_piclist_defualt($content){
	global $post;
	$meta_s = get_post_meta($post->ID,'wp_piclist_usethis');
	$global_s = (get_option('wppl_global')=='1')?1:2;
	$meta_s = ($meta_s[0]=='on')?3:4;
	$result = $global_s+$meta_s;
//	echo  $result."|"."global_s:".$global_s."|"."meta_s:".$meta_s;
	$wp_obj = new wp_piclist();
	switch($result){
		case 4: $result = $content.$wp_obj->display(); break;	
		case 5: $result = ($meta_s==3)?$content.$wp_obj->display():$content; break;
		case 6: $result = $content;break;
	}
	return $result;
	/*
	if($meta_s && $global_s){
		return $post->post_content.$wp_obj->display();
	}else if($meta_s && !$global_s) return $post->post_content.$wp_obj->display(); 
	else if(!$meta_s && $global_s) return $post->post_content;
	*/
}

function wp_piclsit_tag(){
	$pstyle = (get_option('wppl_style')==2)?true:false;
	if($pstyle){
		$wp_obj = new wp_piclist();
		echo $wp_obj->display();	
	}
}

//用户编辑界面
function wp_piclist_post(){
		add_meta_box( 'wp_piclist_id', __('WP PicList Option', 'myplugin_textdomain' ),'wp_piclist_post_box', 'post', 'side','high',1);
		add_meta_box( 'wp_piclist_id', __('WP PicList Option', 'myplugin_textdomain' ),'wp_piclist_post_box', 'page', 'side','high',1);
}

function wp_piclist_post_box($post_id){
		$chk = get_post_meta($post_id->ID,'wp_piclist_usethis');
		$meta_s = ($chk[0]=='on')?3:4;
		$global_s = (get_option('wppl_global')=='1')?1:2;
		$result = $global_s+$meta_s;
		switch($result){
			case 4: $chked = true; break;	
			case 5: $chked = ($meta_s==3)?true:false; break;
			case 6: $chked = false;break;
		}
		//echo  $result."|"."global_s:".$global_s."|"."meta_s:".$meta_s;
		?>
		
		<input type="hidden" name="wp_piclist_check" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) );?>" />
	  <input type="checkbox" name="wp_piclist_usethis" <?php if($chked===true) echo 'checked'; ?> />
	  <?php echo __('使用WP Piclist管理本文图片');
}

function wp_piclist_post_save($post_id){//存储用户编辑POST的时候对wp piclist的修改
	if ( !wp_verify_nonce( $_POST['wp_piclist_check'], plugin_basename(__FILE__) )) {
    return $post_id;
  }
  
	if ('page' == $_POST['post_type'] || 'post' == $_POST['post_type'] ){
    if ( !current_user_can( 'edit_page', $post_id ))
      return $post_id;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ))
      return $post_id;
  }
  
  $wppiclist_ch = $_POST['wp_piclist_usethis'];
  add_post_meta($post_id,'wp_piclist_usethis',$wppiclist_ch,true);
  update_post_meta($post_id,'wp_piclist_usethis',$wppiclist_ch);
  //return $mydata;
}

function wp_piclist_script(){
	if ( !is_admin() ) {
		wp_enqueue_script('jquery');
		wp_register_script('lightbox',wp_piclist_patch.'lightbox/js/jquery.lightbox-0.5.js',array('jquery'));
		wp_register_script('wp_piclist_script',wp_piclist_patch.'wp-piclist.js',array('jquery'));
		wp_enqueue_script('lightbox');
		wp_enqueue_script('wp_piclist_script');
	}
}

function wp_piclist_style(){
	if ( !is_admin() ) {
		echo '<link rel="stylesheet" type="text/css" media="all" href="'.wp_piclist_patch.'wp-piclist.css" />'; 
		echo '<link rel="stylesheet" type="text/css" media="all" href="'.wp_piclist_patch.'lightbox/css/jquery.lightbox-0.5.css" />'; 
		echo '<script type="text/javascript">
			var wp_piclist_imageLoading = "'.wp_piclist_patch.'lightbox/images/lightbox-ico-loading.gif";
			var wp_piclist_imageBtnPrev = "'.wp_piclist_patch.'lightbox/images/lightbox-btn-prev.gif";
			var wp_piclist_imageBtnNext = "'.wp_piclist_patch.'lightbox/images/lightbox-btn-next.gif";
			var wp_piclist_imageBtnClose = "'.wp_piclist_patch.'lightbox/images/lightbox-btn-close.gif";
			var wp_piclist_imageBlank = "'.wp_piclist_patch.'lightbox/images/lightbox-blank.gif";
		</script>';
	}
}

if(function_exists('wp_piclist_defualt')){
	if(get_option('wppl_style')=='1'){
		add_action('the_content','wp_piclist_defualt',get_option('wppl_priority'),1);
	}
		add_action('admin_menu', 'wp_piclist_post');
		add_action('init','wp_piclist_script');
		add_action('wp_head','wp_piclist_style');
		add_action('save_post', 'wp_piclist_post_save');
}


//管理员界面
add_action('admin_menu','admin_wp_piclist');
function admin_wp_piclist(){
	if ( function_exists('add_submenu_page') ){
		add_submenu_page('options-general.php', __('WPPicList'), __('WP PicList'), 'manage_options', 'wp-piclist', 'wp_piclist_conf_page');
	}
}

function wp_piclist_conf_page(){
	$admin_obj = new wp_piclist();
	echo $admin_obj->config_page();
}

