<div class="am_wp_piclist">
	<h3>WP PicList</h3>
	<div class="am_con">
	<form name='update-name' method="post" action="">
		<div class="item">
			<h4>全局设置 Global setting</h4>
			<table width="100%" border="1" class="gtable">
				<tr>
					<td class="fs"><b>自动应用插件</b><br/>automatically :</td>
					<td class="fs"><input type="radio" name="gtype" value="1" ~global1~ /></td>
					<td class="fs">默认情况下在每一篇文章中应用该插件。<br/>
					    By default, every article in the application of the plug-in.</td>
				</tr>
				<tr>
					<td width="138"><b>手动开启插件</b>
					<br/>Plugins manually:</td>
					<td width="32" align="top"><input type="radio" name="gtype" value="2" ~global2~ /></td>
					<td>默认不加载,需要用户自己在编写文章时自己设置该文章是否使用<br/> 
					    By default, do not use WP PicList.You must publish the article to set the time.</td>
				</tr>
			</table>
			<div class="gitem"></div>
			<div class="gitem"></div>
		</div>
		<div class="item">
			<h4>显示方式(Show type)：</h4>
			<div id="am_show_type">
				~style~
			</div>
		</div><!--item end-->
		<div class="item" id="c_size">
			<h4>图片规格 pictrue size：</h4> 
			~size~
		</div><!--item end-->
		<div class="item">
			<div class="bor">
				<b>权重设置：</b> 
				~priority~ (默认为10，你可以根据你的需要进行调整。)
			</div>
		</div><!--item end-->
		<div class="submit-btn">
		<input type="submit" value="修改 Update" class="button-primary" /> 更多帮助请访问 Get more help visit my site: <a href="http://webcaft.com/wp-piclist-plugin">http://webcaft.com/wp-piclist-plugin</a>
		<input type="hidden" name="flag" value="update-tpl" />
		</div>
		</form>
	</div>
</div>