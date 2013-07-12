<?php get_header();?>

<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<div <?php post_class(); ?> id="post-<?php the_id();?>" itemprop="blogpost" itemscope itemtype="http://schema.org/BlogPosting">
<div class="entry">
	<div class="entry-header">
		<h2 class="entry-title" itemprop="headline">
			<?php if(get_the_title()):?>
				<a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark" itemprop="url"><?php the_title();?></a>
			<?php else:?>
				<a href="<?php the_permalink();?>" itemprop="url">（无标题）</a>
			<?php endif;?>
		</h2>
		<span class="hidden" itemprop="author"><?php the_author()?></span>
		<meta itemprop="datePublished" content="<?php the_time('Y-m-d');?>">
		<span class="entry-date"><?php echo the_time('Y 年 m 月 d 日');?></span>
	</div>
	<div class="entry-content" itemprop="articleBody">
		<?php the_content('');?>
	</div>
</div>
<div class="entry-nav assistive-text">
	<span class="floatleft"><?php previous_post_link('＜ %link');?></span>
	<span class="floatright"><?php next_post_link('%link ＞');?></span>
	<div class="clearfix"></div>
</div>
<div class="entry-meta">
	<div class="floatleft">
		<span class="category" itemprop="articleSection"><?php the_category(' ') ?></span>
		<span class="tag" itemprop="keywords"><?php the_tags('<span class="spliter"></span>',' ','');?></span>
		<?php edit_post_link('编辑','<span class="spliter"></span>','');?>
	</div>
	<div class="floatright comments-link">
		<?php comments_popup_link('没有评论','1 条评论','% 条评论');?>
		<meta itemprop="interactionCount" content="UserComments:<?php echo get_comments_number()?>" />
	</div>
	<div class="clearfix"></div>
</div>
<?php if(comments_open()):?>
<?php comments_template();?>
<?php endif;?>
<?php endwhile;?>
<?php endif;?>
</div>

<?php get_footer();?>