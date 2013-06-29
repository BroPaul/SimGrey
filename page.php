<?php get_header();?>

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
	<div class="entry-meta">
		<div class="floatleft">
			<?php edit_post_link('编辑','','');?>
		</div>
		<div class="floatright comments-link">
			<?php comments_popup_link('没有评论','1 条评论','% 条评论');?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?php if ( comments_open() ) : ?>
<?php comments_template();?>
<?php endif;?>
<?php endwhile;?>
<?php endif;?>

<?php get_footer();?>