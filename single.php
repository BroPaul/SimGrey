<?php get_header();?>

<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<div <?php post_class(); ?> id="post-<?php the_id();?>">
	<div class="entry">
		<div class="entry-header">
			<h2 class="entry-title"><?php if(get_the_title()):?>
				<a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark"><?php the_title();?></a>
				<?php else:?>
				<a href="<?php the_permalink();?>">（无标题）</a>
				<?php endif;?>
			</h2>
			<abbr class="entry-date published" title="<?php the_time('Y-m-d');?>"><?php echo the_time('Y年m月d日');?></abbr>
		</div>
		<div class="entry-content">
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
			<span class="category"><?php the_category(' ') ?></span>
			<span class="tag"><?php the_tags('<span class="spliter"></span>',' ','');?></span>
			<?php edit_post_link('编辑','<span class="spliter"></span>','');?>
		</div>
		<div class="floatright comments-link">
			<?php comments_popup_link('没有评论','1 条评论','% 条评论');?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?php if(comments_open()):?>
<?php comments_template();?>
<?php endif;?>
<?php endwhile;?>
<?php endif;?>

<?php get_footer();?>