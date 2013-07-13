<?php
/**
 * WordPress 內置嵌套評論專用 Ajax comments >> WordPress-jQuery-Ajax-Comments v1.3 by Willin Kan.
 *
 * 說明: 這個文件是由 WP 3.0 根目錄的 wp-comment-post.php 修改的, 修改的地方有注解. 當 WP 升級, 請注意可能有所不同.
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

/** Sets up the WordPress Environment. */
require(dirname(__FILE__) . '/../../../wp-load.php'); // 此 comments-ajax.php 位於主題資料夾,所以位置已不同

$options = get_option('SimGrey_options');// 获取主题设置

nocache_headers();

$comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;
$post            = get_post($comment_post_ID);

//判断评论状态
if (empty($post->comment_status)) {
	do_action('comment_id_not_found', $comment_post_ID);
	err('无效评论状态'); // 將 exit 改為錯誤提示
}

// get_post_status() will get the parent status for attachments.
$status     = get_post_status($post);
$status_obj = get_post_status_object($status);

if (!comments_open($comment_post_ID)) {
	do_action('comment_closed', $comment_post_ID);
	err('已关闭评论'); // 將 wp_die 改為錯誤提示
} elseif ('trash' == $status) {
	do_action('comment_on_trash', $comment_post_ID);
	err('无效评论状态'); // 將 exit 改為錯誤提示
} elseif (!$status_obj->public && !$status_obj->private) {
	do_action('comment_on_draft', $comment_post_ID);
	err('无效评论状态'); // 將 exit 改為錯誤提示
} elseif (post_password_required($comment_post_ID)) {
	do_action('comment_on_password_protected', $comment_post_ID);
	err('要评论请输入密码'); // 將 exit 改為錯誤提示
} else {
	do_action('pre_comment_on_post', $comment_post_ID);
}

//获取评论信息
$comment_author       = (isset($_POST['author'])) ? trim(strip_tags($_POST['author'])) : null;
$comment_author_email = (isset($_POST['email'])) ? trim($_POST['email']) : null;
$comment_author_url   = (isset($_POST['url'])) ? trim($_POST['url']) : null;
$comment_content      = (isset($_POST['comment'])) ? trim($_POST['comment']) : null;
$_comment_content     = trim(preg_replace('/^\<a href=.*?comment-(\d+).*?\<\/a\>/si', '', $comment_content)); //把评论内容中的@回复去掉
$edit_id              = (isset($_POST['edit_id'])) ? $_POST['edit_id'] : null; // 提取 edit_id

// If the user is logged in
$user = wp_get_current_user();
if ($user->ID) {
	if (empty($user->display_name))
		$user->display_name = $user->user_login;
	$comment_author       = $wpdb->escape($user->display_name);
	$comment_author_email = $wpdb->escape($user->user_email);
	$comment_author_url   = $wpdb->escape($user->user_url);
	if (current_user_can('unfiltered_html')) {
		if (wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment']) {
			kses_remove_filters(); // start with a clean slate
			kses_init_filters(); // set up the filters
		}
	}
} else {
	if (get_option('comment_registration') || 'private' == $status)
		err('您必须先登陆才允许评论'); // 將 wp_die 改為錯誤提示
}

$comment_type = '';

if (get_option('require_name_email') && !$user->ID) {
	if (6 > strlen($comment_author_email) || '' == $comment_author){
		err('请填写必要的内容（昵称、邮箱）');
	} elseif (!is_email($comment_author_email)){
		err('请填写格式正确的邮箱地址');
	}
}

//禁止空评论
if ('' == $_comment_content) {
	err('请输入评论内容');
}
//禁止没有中文的评论
if($options['need_chinese'] == '1'){
	if(!preg_match_all('/[一-龥]/u', $_comment_content, $match)){
		err('请在评论中输入一些汉字 Type/copy some Chinese characters in it');
	}
}

// 增加: 錯誤提示功能
function err($ErrMsg) {
	header('HTTP/1.1 405 Method Not Allowed');
	echo $ErrMsg;
	exit;
}

// 增加: 檢查重覆評論功能
$dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
if ($comment_author_email)
	$dupe .= "OR comment_author_email = '$comment_author_email' ";
$dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
if ($wpdb->get_var($dupe)) {
	err('看起来您已经发表过同样内容');
}

// 增加: 檢查評論太快功能
if ($lasttime = $wpdb->get_var($wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author))) {
	$time_lastcomment = mysql2date('U', $lasttime, false);
	$time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
	$flood_die        = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
	if ($flood_die) {
		err('您发表评论的速度太快了');
	}
}

$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;

$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

// 增加: 檢查評論是否正被編輯, 更新或新建評論
function _user_can_edit_comment($new_cmt_data, $comment_ID = 0) {
	if (current_user_can('edit_comment', $comment_ID)) {
		return true;
	}
	$comment       = get_comment($comment_ID);
	$old_timestamp = strtotime($comment->comment_date);
	$new_timestamp = current_time('timestamp');
	$rs            = $comment->comment_author_email === $new_cmt_data['comment_author_email'] && $comment->comment_author_IP === $_SERVER['REMOTE_ADDR'] && $new_timestamp - $old_timestamp < 1800;
	return $rs;
}
if ($edit_id) {
	$comment_id = $commentdata['comment_ID'] = $edit_id;
	if (_user_can_edit_comment($commentdata, $comment_id)) {
		wp_update_comment($commentdata);
	} else {
		err('You are not allowed to edit this comment!');
	}
} else {
	$comment_id = wp_new_comment($commentdata);
}

$comment = get_comment($comment_id);
if (!$user->ID) {
	$comment_cookie_lifetime = apply_filters('comment_cookie_lifetime', 30000000);
	setcookie('comment_author_' . COOKIEHASH, $comment->comment_author, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_email_' . COOKIEHASH, $comment->comment_author_email, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
	setcookie('comment_author_url_' . COOKIEHASH, esc_url($comment->comment_author_url), time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
}

//$location = empty($_POST['redirect_to']) ? get_comment_link($comment_id) : $_POST['redirect_to'] . '#comment-' . $comment_id; //取消原有的刷新重定向
//$location = apply_filters('comment_post_redirect', $location, $comment);

//wp_redirect($location);

$comment_depth = 1; //为评论的 class 属性准备的
$tmp_c         = $comment;
while ($tmp_c->comment_parent != 0) {
	$comment_depth++;
	$tmp_c = get_comment($tmp_c->comment_parent);
}

//以下是評論式樣, 不含 "回覆". 要用你模板的式樣 copy 覆蓋.
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
<div id="comment-<?php comment_ID(); ?>">
	<div class="comment-author vcard">
		<?php echo get_avatar($comment,$size='32'); ?>
		<div class="comment-meta commentmetadata"><span class="meta fn nickname"><?php comment_author_link()?></span><span class="meta date"><a href="#comment-<?php comment_ID() ?>" title="" rel="nofollow">
		<?php comment_date('Y年m月d日') ?>
		<?php comment_time() ?>
		</a>
		<?php edit_comment_link('编辑','<span class="spliter"></span>',''); ?>
		<?php if ($comment->comment_approved == '0') : ?>
		<em>评论正在等待审核...</em>
		<?php endif; ?>
		</span></div>
	</div>
	<div class="comment-content"><?php comment_text() ?></div>
</div>