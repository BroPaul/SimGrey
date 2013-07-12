<?php get_header();?>
<?/*
Template Name: 博客列表
*/
?>

<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<div <?php post_class(); ?> id="post-<?php the_id();?>" itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
<div class="entry">
	<div class="entry-header">
		<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark"><?php the_title();?></a></h2>
	</div>
	<div class="entry-content">
		<ul class="xoxo blogroll">
		<?php wp_list_bookmarks('orderby=id&show_description=true&title_li=0&categorize=0&before=<li itemscope itemtype="http://schema.org/Person"><span class="bglink" itemprop="name">&between=</span><span class="bgdesc" itemprop="description">&after=</span></li>')?>
		</ul>
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