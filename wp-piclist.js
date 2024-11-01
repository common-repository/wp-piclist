jQuery(document).ready(function($){
	wp_piclist_object = $('#wp-piclist');
	wp_piclist_count = wp_piclist_object.find('li').length;
	wp_piclist_width = wp_piclist_object.find('li').outerWidth();
	wp_piclist_height = wp_piclist_object.find('li').height();

	//wp_piclist_object.height(wp_piclist_height);
	$('ul',wp_piclist_object).width(wp_piclist_width*wp_piclist_count);

	$("ul li a",wp_piclist_object).lightBox({fixedNavigation:true});
});