<?php get_header();?>

<?php $blogOption = get_option('simgrey_options');?>
<?php $posts = query_posts($query_string . '&orderby=date&showposts=' . $blogOption['show_post']);?>

<div class="page archive">
<h2 class="title">
	<?php if(is_tag()):?><?php $tags = get_tags("slug=$tag");?>您正在查看标签为 <?php echo $tags[0]->name;?> 的文章列表<?php endif;?>
	<?php if(is_category()):?><?php $category = get_the_category();?>您正在查看分类为 <?php echo $category[0]->name;?> 的文章列表<?php endif;?>
	<?php if(is_time()):?>您正在查看 <?php the_time();?> 的文章列表<?php endif;?>
	<?php if(is_day()):?>您正在查看 <?php the_time('Y 年 m 月 d 日');?> 的文章列表<?php endif;?>
	<?php if(is_month()):?>您正在查看 <?php the_time('Y 年 m 月');?> 的文章列表<?php endif;?>
	<?php if(is_year()):?>您正在查看 <?php the_time('Y 年');?> 的文章列表<?php endif;?>
	<?php if(is_author()):?>您正在查看 <?php if(get_query_var('author_name')){$user = get_user_by('slug', get_query_var('author_name'));}else{$user = get_userdata(get_query_var('author'));};echo $user->display_name;?> 的文章列表<?php endif;?>
</h2>
<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<div class="entry hentry" itemprop="blogpost" itemscope itemtype="http://schema.org/BlogPosting">

	<div class="floatleft">
		<span class="vcard hidden" itemprop="author"><span class="author fn nickname"><?php the_author()?></span></span>
		<meta itemprop="datePublished" content="<?php the_time('Y-m-d');?>">
		<abbr class="entry-date published" title="<?php the_time('Y-m-d');?>"><?php echo the_time('Y 年 m 月 d 日');?></abbr>
		<h3 class="entry-title" itemprop="headline"><?php if(get_the_title()):?>
				<a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark" itemprop="url"><?php the_title();?></a>
			<?php else:?>
				<a href="<?php the_permalink();?>" itemprop="url">（无标题）</a>
			<?php endif;?>
		</h3>
	</div>
	<div class="floatright comments-link">
		<span><?php comments_number('没有评论','1 条评论','% 条评论');?></span>
		<meta itemprop="interactionCount" content="UserComments:<?php echo get_comments_number()?>" />
		<?php edit_post_link('编辑','<span class="spliter"></span>','');?>


	</div>
	<div class="clearfix"></div>
</div>
<?php endwhile;?>
<div class="page-nav">
	<?php echo paginate_links( array('base' => str_replace( 99999, '%#%', get_pagenum_link( 99999 ) ),'format' => '?paged=%#%','current' => max( 1, get_query_var('paged') ),'total' => $wp_query->max_num_pages,'prev_text' => '◄','next_text' => '►') );?>
</div>
<?php endif;?>
</div>

<?php get_footer();?>