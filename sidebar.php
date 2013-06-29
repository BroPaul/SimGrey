<div id="sidebar" role="complementary">
<?php if(is_active_sidebar('sidebar-primary')):?>
<div id="sidebar-primary">
	<div class="widget-area">
		<ul class="sidebar-list">
			<?php dynamic_sidebar('sidebar-primary')?>
		</ul>
	</div>
</div>
<?php endif;?>
<?php if(is_active_sidebar('sidebar-secondary')):?>
<div id="sidebar-secondary">
	<div class="widget-area">
		<ul class="sidebar-list">
			<?php dynamic_sidebar('sidebar-secondary')?>
		</ul>
	</div>
</div>
<?php endif;?>
</div>
<!--/sidebar-->
