<?php get_header();?>
<?php
/*
Template Name: 关于
*/
?>

<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<div <?php post_class(); ?> id="post-<?php the_id();?>" itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
<div class="entry">
	<div class="entry-header">
		<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark" itemprop="url"><?php the_title();?></a></h2>
		<span class="hidden" itemprop="author"><?php the_author()?></span>
	</div>
	<div class="entry-content" itemprop="articleBody">
		<blockquote><p>
			<?php bloginfo('name');?>： 已运行 <?php echo floor((time()-1298432520)/86400);?> 天 ，
			共有文章 <?php $count_posts = wp_count_posts(); echo $count_posts->publish;?> 篇 ，
			评论 <?php $count_comments = get_comment_count(); echo $count_comments['approved'];?> 条 。
		</p></blockquote>
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