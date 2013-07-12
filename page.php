<?php get_header();?>

<?php if(have_posts()):?>
<?php while(have_posts()):the_post();?>
<div <?php post_class(); ?> id="post-<?php the_id();?>" itemprop="blogpost" itemscope itemtype="http://schema.org/BlogPosting">
<div class="entry">
	<div class="entry-header">
		<h2 class="entry-title" itemprop="headline"><a href="<?php the_permalink();?>" title="<?php the_title();?>" rel="bookmark" itemprop="url"><?php the_title();?></a></h2>
		<span class="vcard hidden" itemprop="author"><span class="author fn nickname"><?php the_author()?></span></span>
	</div>
	<div class="entry-content" itemprop="articleBody">
		<?php the_content('');?>
	</div>
</div>
<div class="entry-meta">
	<div class="floatleft">
		<?php edit_post_link('编辑','','');?>
	</div>
	<div class="floatright comments-link">
		<?php comments_popup_link('没有评论','1 条评论','% 条评论');?>
		<meta itemprop="interactionCount" content="UserComments:<?php echo get_comments_number()?>" />
	</div>
	<div class="clearfix"></div>
</div>
<?php if ( comments_open() ) : ?>
<?php comments_template();?>
<?php endif;?>
<?php endwhile;?>
<?php endif;?>
</div>

<?php get_footer();?>