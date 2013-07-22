<?php

//检测主题更新
require_once(TEMPLATEPATH . '/update/checker.php'); 
$wpdaxue_update_checker = new ThemeUpdateChecker(
	'SimGrey',
	'http://neverweep.googlecode.com/svn/trunk/themes/simgrey-public/update/info.json'
);


//get CSS
function simgrey_css(){
	if(is_active_sidebar('sidebar-primary')){
		if(!is_active_sidebar('sidebar-secondary')){
			echo '-wide';
			
		}else{
			echo '-wider';
		}
	}
	return;
}

//邮件回复评论
function comment_mail_notify($comment_id) {
	$options = get_option('SimGrey_options');
	$blogname = trim(html_entity_decode(get_option('blogname'), ENT_QUOTES));
	$admin_email = $options['mail_address'];
	$comment = get_comment($comment_id);
	$comment_author_email = trim($comment->comment_author_email);
	$parent_id = $comment->comment_parent ? $comment->comment_parent : '';
	$parent_email = trim(get_comment($parent_id)->comment_author_email);
	$parent_comment = get_comment($parent_id);
	global $wpdb;
	$spam_confirmed = $comment->comment_approved;
	if ($parent_id != '' && $spam_confirmed != 'spam' && $parent_email != $comment_author_email  && $parent_email != $admin_email) {
		$wp_email = $admin_email;
		$to = trim(get_comment($parent_id)->comment_author) . ' <' . trim(get_comment($parent_id)->comment_author_email) . '>';
		$subject = '您在 ' . html_entity_decode(get_option('blogname'), ENT_QUOTES) . ' 的评论有了新回复';
		$message = '<div style="font-family:\'Helvetica Neue\',Helvetica,Arial,\'Hiragino Sans GB\',\'Hiragino Sans GB W3\',\'Microsoft YaHei\',SimSun;font-size:13px;width:600px;margin:0 auto"><div style="background:#2AD;color:snow;padding:1em 2em;font-size:15px"><p><b>' . trim($parent_comment->comment_author) . '</b>，您好!</p></div><div style="background:#FAFAFA;border:1px solid #CCC;border-top:none"><div style="color:#333;padding:1em 2em;margin:0 1em"><p>您曾在《' . get_the_title($comment->comment_post_ID) . '》上发表评论：<blockquote style="border:1px dashed #CCC;padding:0 10px">' . trim(apply_filters('comment_text',convert_smilies($parent_comment->comment_content))) . '</blockquote><p><b>' . trim($comment->comment_author) . '</b> 回复您：</p><blockquote style="border:1px dashed #CCC;padding:0 10px">' . trim(apply_filters('comment_text',convert_smilies($comment->comment_content))) . '</blockquote><br/><p>您可以 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '" style="color:#2AD">查看文章和全部回复</a></p></div><div style="text-align:right;padding:1em 2em">欢迎您再次访问 <a href="' . get_option('home') . '" style="color:#2AD">' . $blogname . '</a></div></div></div>';
		$from = 'From: ' . $blogname . " <$wp_email>";
		$headers = "$from\nContent-Type: text/html; charset=\"UTF-8\"\n";
		wp_mail( $to, $subject, $message, $headers );
	}
}
$options = get_option('SimGrey_options');
if($options['mail_notify'] == '1'){
	add_action('comment_post', 'comment_mail_notify');
}


//文章归档
function archives_list_SHe() {
	global $wpdb,$month;
	$lastpost = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_date <'" . current_time('mysql') . "' AND post_status='publish' AND post_type='post' AND post_password='' ORDER BY post_date DESC LIMIT 1");
	$output = get_option('SHe_archives_'.$lastpost);
	if(empty($output)){
		$output = '';
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'SHe_archives_%'");
		$q = "SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month, count(ID) as posts FROM $wpdb->posts p WHERE post_date <'" . current_time('mysql') . "' AND post_status='publish' AND post_type='post' AND post_password='' GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
		$monthresults = $wpdb->get_results($q);
		if ($monthresults) {
			foreach ($monthresults as $monthresult) {
				$thismonth	= zeroise($monthresult->month, 2);
				$thisyear	= $monthresult->year;
				$q = "SELECT ID, post_date, post_title, comment_count FROM $wpdb->posts p WHERE post_date LIKE '$thisyear-$thismonth-%' AND post_date AND post_status='publish' AND post_type='post' AND post_password='' ORDER BY post_date DESC";
				$postresults = $wpdb->get_results($q);
				if ($postresults) {
					$text = sprintf('%d 年 %s 月', $monthresult->year, zeroise($monthresult->month,2));
					$postcount = count($postresults);
					$output .= '<li><span class="archives-yearmonth">' . $text . '（' . count($postresults) . ' 篇文章' . '）</span><ul class="archives-monthlisting">' . "\n";
					foreach ($postresults as $postresult) {
						if ($postresult->post_date != '0000-00-00 00:00:00') {
							$url = get_permalink($postresult->ID);
							$arc_title	= $postresult->post_title;
							if ($arc_title){
								$text = wptexturize(strip_tags($arc_title));
							} else {
								$text = $postresult->ID;
							}
							$title_text = esc_html($text, 1);
							$output .= '<li>' . mysql2date('d 日', $postresult->post_date) . '：' . "<a href='$url' title='$title_text'>$text</a>";
							$output .= '（' . $postresult->comment_count . '）';
							$output .= '</li>' . "\n";
						}
					}
					$output .= '</ul></li>';
				}
			}
			$output = '<ul class="archives-list">' . $output . '</ul>' . "\n";
			update_option('SHe_archives_'.$lastpost,$output);
		} else {
			$output = '<div class="errorbox">'. __('Sorry, no posts matched your criteria.','freephp') .'</div>' . "\n";
		}
	}
	echo $output;
}

//去除 microformats hatom
add_filter('post_class', 'force_remove_hentry', 20);
function force_remove_hentry($classes) {
	if(($key = array_search('hentry', $classes)) !== false)
		unset($classes[$key]);
	return $classes;
}


//主题支持
add_editor_style('css/editor-style.css');
remove_filter('the_content', 'wptexturize');
remove_filter('comment_text', 'wptexturize');
remove_filter('the_excerpt', 'wptexturize');
remove_filter('the_title', 'wptexturize');
register_nav_menu( 'primary', '自定义菜单' );
register_sidebar( array(
	'name' => '主工具栏',
	'id' => 'sidebar-primary',
	'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
	'after_widget' => '</li>',
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>',
));
register_sidebar( array(
	'name' => '副工具栏',
	'id' => 'sidebar-secondary',
	'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
	'after_widget' => '</li>',
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>',
));
function unregister_rss_widget(){
	unregister_widget('WP_Widget_Search');
}
add_action('widgets_init','unregister_rss_widget');


//评论区设置
function f_comment($comment, $args, $depth) {
$GLOBALS['comment'] = $comment;?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID()?>" itemprop="comment" itemscope itemtype="http://schema.org/Comment" <?php if (get_comment_type() == 'comment'):;?> class="this_comment"<?php endif; ?>>
	<div class="comment-author">
		<?php echo get_avatar($comment,$size='32'); ?>
		<div class="comment-meta">
			<span class="meta">
			<?php
				$url = get_comment_author_url( $comment_ID );
				$author = get_comment_author( $comment_ID );
				if(empty($url) || 'http://' == $url){
					echo $author;
				}else{
					echo "<a href='$url' rel='external nofollow' class='url' itemprop='author'>$author</a>";
				}
			?>
			</span>
			<span class="meta date">
				<a href="#comment-<?php comment_ID() ?>">
					<meta itemprop="commentTime" content="<?php comment_date('Y-m-d')?>">
					<?php comment_date('Y 年 m 月 d 日')?>
					<?php comment_time() ?>
				</a>
				<?php edit_comment_link('编辑','<span class="spliter"></span>',''); ?>
				<?php if ($comment->comment_approved == '0') : ?>
					<em>评论正在等待审核...</em>
				<?php endif; ?>
			</span>
		</div>
	</div>
	<div class="comment-content" itemprop="commentText"><?php comment_text() ?></div>
	<?php if (get_comment_type() == 'comment'):;?>
	<div class="reply">
		<?php if ($depth == get_option('thread_comments_depth')) : ?>
			<a onclick="return addComment.moveForm( 'comment-<?php comment_ID() ?>','<?php echo $comment->comment_parent; ?>', 'respond','<?php echo $comment->comment_post_ID; ?>' )" href="?replytocom=<?php comment_ID() ?>#respond" class="comment-reply-link" rel="nofollow">回复</a>
		<?php else: ?>
			<a onclick="return addComment.moveForm( 'comment-<?php comment_ID() ?>','<?php comment_ID() ?>', 'respond','<?php echo $comment->comment_post_ID; ?>' ) " href="?replytocom=<?php comment_ID() ?>#respond" class="comment-reply-link" rel="nofollow">回复</a>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>
<?php
}

//控制面板
class SimGreyOptions {

	/* -- 获取选项组 -- */
	function getOptions() {

		$options = get_option('SimGrey_options');
		// 如果数据库中不存在该选项组, 设定这些选项的默认值, 并将它们插入数据库
		if (!is_array($options)) {
	$options['show_post'] = '-1';
	$options['need_chinese'] = '1';
	$options['mail_notify'] = '1';
	$options['code'] = '';
	$options['nav'] = '0';
	$options['feed'] = get_bloginfo('rss2_url');
	$options['mail_address'] = get_option('admin_email');

			update_option('SimGrey_options', $options);
		}
		// 返回选项组
		return $options;
	}

	/* -- 初始化 -- */
	function init() {

		if(isset($_POST['simgrey_save'])) {

			$options = SimGreyOptions::getOptions();
			$options['show_post'] = $_POST['show_post'];
			$options['code'] = $_POST['code'];
			if(empty($_POST['need_chinese'])) {
				$options['need_chinese'] = '0';
			}else{
				$options['need_chinese'] = '1';
			}
			if(empty($_POST['nav'])) {
				$options['nav'] = '0';
			}else{
				$options['nav'] = '1';
			}
			if(empty($_POST['mail_notify'])) {
				$options['mail_notify'] = '0';
			}else{
				$options['mail_notify'] = '1';
			}
			if($_POST['mail_address']){
				$options['mail_address'] = $_POST['mail_address'];
			}else{
				$options['mail_address'] = get_option('admin_email');
			}
			if(empty($_POST['feed'])){
				$options['feed'] = get_bloginfo('rss2_url');
			}else{
				$options['feed'] = $_POST['feed'];
			}
			update_option('SimGrey_options', $options);

		} else {
			SimGreyOptions::getOptions();
		}

		add_theme_page("SimGrey 主题设置", "SimGrey 主题设置", 'edit_themes', basename(__FILE__), array('SimGreyOptions', 'display'));
	}

	/* -- 标签页 -- */
	function display() {
		$options = SimGreyOptions::getOptions();
?>
<style>
.wrap,.wrap h2{font-size:12px;line-height:1.5}
.wrap h2{font-weight:bold;font-size:26px}
.wrap .settings,.wrap .description{margin-left:2em}
.wrap label{float:left;width:25em}
.wrap .settings{margin:1em}
</style>
<form action="#" method="post" enctype="multipart/form-data" name="simgrey_form" id="simgrey_form">
<div class="wrap">
	<h2>SimGrey 主题设置</h2>

	<div>
		<h3>评论设置</h3>
		<div class="settings">
			<label for="need_chinese">评论中必须含有中文：</label>
			<input name="need_chinese" type="checkbox" id="need_chinese" <?php if($options['need_chinese'] == '1')echo 'checked="checked"'?>>
			<span class="description">可以降低收到垃圾评论的概率</span>
		</div>
		<div class="settings">
			<label for="mail_notify">启用评论通知：</label>
			<input name="mail_notify" type="checkbox" id="mail_notify" <?php if($options['mail_notify'] == '1')echo 'checked="checked"'?>>
			<span class="description">选中启用评论通知功能</span>
		</div>
		<div class="settings">
			<label for="mail_address">评论通知发出邮箱：</label>
			<input name="mail_address" type="email" id="mail_address" style="width:250px" value="<?php echo $options['mail_address']?>" class="code">
			<span class="description">选择发出通知的邮箱，留空则使用管理员邮箱</span>
		</div>
	</div>

	<div>
		<h3>其它设置</h3>
		<div class="settings">
			<label for="nav">博客导航菜单：</label>
			<input id="nav" name="nav" type="checkbox" <?php if($options['nav'] == '1')echo 'checked="checked"'?>>
			<span class="description">选中则启用自定义菜单（请到“主题-&gt;菜单”设置），取消选中则使用页面列表</span>
		</div>
		<div class="settings">
			<label for="show_post">归档页面 (archive.php) 每页显示文章数量：</label>
			<input id="show_post" name="show_post" type="number" min="-1" class="small-text code" value="<?php if($options['show_post'])echo esc_attr($options['show_post']);?>" />
			<span class="description">输入"-1"则不分页，显示全部文章</span>
		</div>
		<div class="settings">
			<label for="feed">博客的 Feed 地址：</label>
			<input id="feed" name="feed" type="url" style="width:250px" value="<?php if($options['feed'])echo esc_attr($options['feed']); ?>" />
			<span class="description">留空则使用博客 Rss 2.0 地址</span>
		</div>
		<div class="settings">
			<label for="code">统计代码：</label>
			<textarea id="code" name="code" style="width:250px;height:100px" /><?php echo stripslashes($options['code'])?></textarea>
			<span class="description">在博客 Footer 插入的统计代码，只有未登录用户才执行</span>
		</div>
	</div>

		<!-- 提交按钮 -->
		<p class="submit">
			<input type="submit" name="simgrey_save" class="button button-primary" value="更新设置" />
		</p>
</div>
</form>

<?php
	}
}

//登记初始化方法
add_action('admin_menu', array('SimGreyOptions', 'init'));

//引用
$blogOption = get_option('SimGrey_options');

?>