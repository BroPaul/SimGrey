<?php get_header();?>


<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<?php if(is_sticky() && $paged<2):?>
<div <?php post_class(); ?> id="post-<?php the_id();?>">
	<div class="entry">
		<div class="entry-header">
			<h2 class="entry-title"><?php if(get_the_title()):?>
				<a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark"><?php the_title();?></a>
				<?php else:?>
				<a href="<?php the_permalink();?>">（无标题）</a>
				<?php endif;?>
			</h2>
			<abbr class="entry-date published hidden" title="<?php the_time('Y-m-d');?>"><?php echo the_time('Y年m月d日');?></abbr>
		</div>
	</div>
</div>
<?php else:?>
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
			<?php the_content('阅读全文');?>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="entry-meta">
		<div class="floatleft">
			<span class="category"><?php the_category(' ') ?></span>
			<span class="tag"><?php the_tags('<span class="spliter"></span>',' ','');?></span>
			<?php edit_post_link('编辑','<span class="spliter"></span>','');?>
		</div>
		<div class="floatright comments-link">
			<span><?php comments_popup_link('没有评论','1 条评论','% 条评论');?></span>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?php
	endif;
	endwhile;
?>
<div class="page-nav">
	<?php echo paginate_links( array('base' => str_replace( 99999, '%#%', get_pagenum_link( 99999 ) ),'format' => '?paged=%#%','current' => max( 1, get_query_var('paged') ),'total' => $wp_query->max_num_pages,'prev_text' => '◄','next_text' => '►') );?>
</div>
<?php endif;?>

<?php get_footer();?>