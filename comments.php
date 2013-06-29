<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('请不要直接刷新此页，谢谢!');
	
	if ( post_password_required() ) { ?>
	
<p>要查看评论请输入密码</p>
<?php
		return;
	}
if ( comments_open() ) {?>
<div id="comment-template" class="comments-area">
<?php if ( have_comments() ) : ?>
<ol id="comments">
	<?php wp_list_comments('callback=f_comment');?>
</ol>
<div class="page-nav">
	<?php paginate_comments_links('prev_text=◄&next_text=►');?>
</div>
<?php endif;?>
<?php if ( comments_open() ) : ?>
<div id="respond">
	<div id="response" class="respond-in">
	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
	<p>您必须先 <a href="<?php echo wp_login_url( get_permalink() );?>">登录</a> 才允许评论。</p>
	<?php else : ?>
	<form action="<?php echo get_option('siteurl');?>/wp-comments-post.php" method="post" id="commentform">
		<?php if ( is_user_logged_in() ) : ?>
		<div>欢迎回来， <a href="<?php echo get_option('siteurl');?>/wp-admin/profile.php"><?php echo $user_identity;?></a>！&nbsp;(<a href="<?php echo wp_logout_url(get_permalink());?>" title="登出该账户"> 注销 </a>)</div>
		<?php else : ?>
		<div>
			<input type="text" name="author" id="author" onkeypress="return noenter(event)" value="<?php echo esc_attr($comment_author);?>" size="26" tabindex="1"/>
			<label for="author">昵称<?php if ($req) echo " *";?></label>
		</div>
		<div>
			<input type="text" name="email" id="email" onkeypress="return noenter(event)" value="<?php echo esc_attr($comment_author_email);?>" size="26" tabindex="2" />
			<label for="email">邮箱<?php if ($req) echo " *";?></label>
		</div>
		<div>
			<input type="text" name="url" id="url" onkeypress="return noenter(event)" value="<?php echo esc_attr($comment_author_url);?>" size="26" tabindex="3" />
			<label for="url">网址</label>
		</div>
		<?php endif;?>
		<textarea name="comment" id="comment" tabindex="4"></textarea>
		<div id="comment-last-div">
			<div class="floatright">
				<?php cancel_comment_reply_link('取消');?>
				<input type="button" id="submit" class="comment_submit pointer" tabindex="5" value="提交(Ctrl+Enter)" />
				<!--[if IE 9]><style>#cancel-comment-reply-link{padding-top:3px;padding-bottom:2px}</style><![endif]-->
			</div>
			<?php comment_id_fields();?>
			<div class="clearfix"></div>
		</div>
		<?php do_action('comment_form', $post->ID);?>
	</form>
	<?php endif; // If registration required and not logged in ?>
	</div>
</div>
</div>
<?php endif; // if you delete this the sky will fall on your head ?>
<?php } else { ?>
<div id="comment-template" class="solo">
<strong id="comments">已关闭评论...</strong>
</div>
<?php }?>