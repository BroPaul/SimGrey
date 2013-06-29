<?php get_header();?>
<?/*
Template Name: 没有评论的独立页面
*/
?>

<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<div <?php post_class(); ?> id="post-<?php the_id();?>">
	<div class="entry">
		<div class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark"><?php the_title();?></a></h2>
		</div>
		<div class="entry-content">
			<?php the_content('');?>
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