	</div>
	<!--/main-->
	<?php
		get_sidebar( 'sidebar-primary' );
		get_sidebar( 'sidebar-secondary' );
	?>
<div class="clearfix"></div>
</div>
<!--/container-->
<div id="footer" role="contentinfo" class="site-info">
	<div class="floatleft">&copy;<a href="<?php bloginfo('url');?>/"><?php bloginfo('name');?></a>
		. Powered by <a href="http://wordpress.org/" rel="nofollow external">WordPress</a>
		. SimGrey by <a href="http://xiaoxia.de/" rel="external">xiaoxia.de</a>
	</div>
	<div id="footer-button" class="floatright">
		<?php if(is_user_logged_in()):?><a href="<?php echo wp_logout_url(get_bloginfo('url') . $_SERVER["REQUEST_URI"]);?>" rel="nofollow">登出</a><?php endif;?>
		<a href="<?php bloginfo('url');?>/wp-admin/" rel="nofollow">管理</a>
	</div>
	<a class="back-to-top"></a>
	<div class="clearfix"></div>
</div>
<!--/footer-->
<?php wp_footer();?>
<?php if(!is_user_logged_in()):?>
	<?php $blogOption = get_option('SimGrey_options');?>
	<div class="hidden"><?php echo stripslashes($blogOption['code'])?></div>
	<!--放置在 div.hidden 的代码且不会被显示，适合放置统计代码（登入用户不执行该代码）-->
<?php endif;?>
</body>
</html>