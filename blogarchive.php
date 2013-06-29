<?php get_header();?>
<?php
/*
Template Name: 含分类、标签和归档页面
*/
?>

<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<div <?php post_class('blogarchive'); ?> id="post-<?php the_id();?>">
	<div class="entry">
		<div class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark"><?php the_title();?></a></h2>
		</div>
		<div class="entry-content">
			<h3>标签</h3>
			<div><?php wp_tag_cloud('smallest=13&largest=13&unit=px')?></div>
			<h3>分类</h3>
			<ul><?php wp_list_categories('title_li=')?></ul>
			<h3>文章列表</h3>
			<a id="expand_collapse" rel="nofollow">全部展开/收缩</a>
			<div id="archives"><?php archives_list_SHe();?></div>
		</div>
	</div>
	<?php if(current_user_can('edit_post')):?>
	<div class="entry-meta">
		<div class="floatleft">
			<?php edit_post_link('编辑','','');?>
		</div>
		<div class="clearfix"></div>
	</div>
	<?php endif;?>
</div>
<?php endwhile;?>
<?php endif;?>

<?php get_footer();?>