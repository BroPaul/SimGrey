<?php get_header();?>
<?php
/*
Template Name: 留言板，只有评论会显示，如果你想和关于放到一起，选择noshm.php做模板
*/
?>

<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<div class="post-<?php the_id();?> page type-page status-<?php echo get_post_status();?> guestbook" id="post-<?php the_id();?>" itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
<div class="entry">
	<div class="entry-header">
		<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark" itemprop="url"><?php the_title();?></a></h2>
	</div>
</div>
<?php comments_template();?>
<?php endwhile;?>
<?php endif;?>
</div>

<?php get_footer();?>