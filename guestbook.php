<?php get_header();?>
<?php
/*
Template Name: 留言板，只有评论会显示，如果你想和关于放到一起，选择noshm.php做模板
*/
?>

<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<div class="post-<?php the_id();?> page type-page status-<?php echo get_post_status();?> guestbook" id="post-<?php the_id();?>">
<div class="entry">
	<div class="entry-header">
		<h2 class="entry-title"><a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark" itemprop="url"><?php the_title();?></a></h2>
	</div>
	<?php comments_template();?>
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