jQuery(function($){
//外部链接新窗口打开
$('a[rel*="external"]').click(function(){
	var href = $(this).attr('href');
	var domain = window.location.host;
	if(href && domain && href.indexOf(domain) < 0){
		$(this).attr('target','_blank');
	}
});
//评论 Ctrl+Enter
$('#comment').keydown(function(e){
	if(e.ctrlKey && (e.keyCode==13 || e.keyCode==10)){
		$('#commentform').submit();
	}
});
//评论点击提交
$('#submit').click(function(e){
	$('#commentform').submit();
});
//页面内锚点跳转
$('.entry-content a[href^="#"],.single .comments-link a[href*="#"],.page .comments-link a[href*="#"],a[href^="#comment-"]').click(function(){
	var _rel=$(this).attr("href").replace(/.*?#(.*?)/,'#$1');
	$('html, body').animate({ scrollTop: $(_rel).offset().top - 20}, 400);
	return false;
});
//静态文章归档
jQuery(document).ready(function(){
	$('#archives ul li ul.archives-monthlisting').hide();
	$('#archives ul li ul.archives-monthlisting:first').show();
	$('#archives ul li span.archives-yearmonth').click(function(){$(this).next().toggle();return false;});
	$('#expand_collapse').toggle(
	function(){
		$('#archives ul li ul.archives-monthlisting').show();
	},
	function(){
		$('#archives ul li ul.archives-monthlisting').hide();
	});
});
//返回顶端
var topLink = $('.back-to-top');
$(window).scroll(function() {
	$(window).scrollTop() > 150 ? topLink.fadeIn(500) : topLink.fadeOut(200)
}),topLink.click(function() {
	return jQuery('html,body').stop().animate({
	scrollTop: 0 },700),!1;
});
});
/**
 * WordPress jQuery-Ajax-Comments v1.3 by Willin Kan.
 * URI: http://kan.willin.org/?p=1271
 */
var edit_mode = '1', // 再編輯模式 ( '1'=開; '0'=不開 )
	ajax_php_url,
	txt1 = '<div id="loading">正在提交评论, 请稍候...</div>',
	txt2 = '<div id="error">#</div>',
	txt3 = '"><p>提交成功',
	edt1 = '，刷新页面之前可以 <a rel="nofollow" class="comment-reply-link" href="#edit" onclick=\'return addComment.moveForm("',
	edt2 = ')\'>重新编辑评论内容</a></p><p>当您的评论有新回复时，系统会向您发送通知邮件</p>',
	cancel_edit = '取消',
	edit, num = 1, comm_array=[]; comm_array.push('');

jQuery(document).ready(function($) {
	$comments = $('#comments-title'); // 評論數的 ID
	$cancel = $('#cancel-comment-reply-link'); cancel_text = $cancel.text();
	$submit = $('#commentform #submit'); $submit.attr('disabled', false);
	$('#comment').after( txt1 + txt2 ); $('#loading').hide(); $('#error').hide();
	$body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');

/** submit */
$('#commentform').submit(function() {
	$('#loading').slideDown();
	ajax_php_url = $('#blog h1 a').attr('href') + 'wp-content/themes/simgrey/comments-ajax.php';
	$submit.attr('disabled', true).fadeTo('slow', 0.5);
	if ( edit ) $('#comment').after('<input type="text" name="edit_id" id="edit_id" value="' + edit + '" style="display:none;" />');

/** Ajax */
	$.ajax( {
		url: ajax_php_url,
		data: $(this).serialize(),
		type: $(this).attr('method'),

		error: function(request) {
			$('#loading').slideUp();
			$('#error').slideDown().html('' + request.responseText);
			setTimeout(function() {$submit.attr('disabled', false).fadeTo('slow', 1); $('#error').slideUp();}, 3000);
			},

		success: function(data) {
			$('#loading').hide();
			comm_array.push($('#comment').val());
			$('textarea').each(function() {this.value = ''});
			var t = addComment, cancel = t.I('cancel-comment-reply-link'), temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId), post = t.I('comment_post_ID').value, parent = t.I('comment_parent').value;

// comments
		if ( ! edit && $comments.length ) {
			n = parseInt($comments.text().match(/\d+/));
			$comments.text($comments.text().replace( n, n + 1 ));
		}

// show comment
		new_htm = '" id="new_comm_' + num + '"></';
		new_htm = ( parent == '0' ) ? ('\n<ol style="clear:both;" class="commentlist' + new_htm + 'ol>') : ('\n<ul class="children' + new_htm + 'ul>');

		ok_htm = '\n<div class="comment_success" id="success_' + num + txt3;
		if ( edit_mode == '1' ) {
			div_ = (document.body.innerHTML.indexOf('div-comment-') == -1) ? '' : ((document.body.innerHTML.indexOf('li-comment-') == -1) ? 'div-' : '');
			ok_htm = ok_htm.concat(edt1, div_, 'comment-', parent, '", "', parent, '", "respond", "', post, '", ', num, edt2);
		}
		ok_htm += '</div>\n';

		$('#respond').before(new_htm);
		$('#new_comm_' + num).hide().append(data);
		$('#new_comm_' + num + ' li').append(ok_htm);
		$('#new_comm_' + num).slideDown(800);

		$body.animate( { scrollTop: $('#new_comm_' + num).offset().top - 200}, 900);
		countdown(); num++ ; edit = ''; $('*').remove('#edit_id');
		cancel.style.display = 'none';
		cancel.onclick = null;
		t.I('comment_parent').value = '0';
		if ( temp && respond ) {
			temp.parentNode.insertBefore(respond, temp);
			temp.parentNode.removeChild(temp)
		}
		}
	}); // end Ajax
  return false;
}); // end submit

/** comment-reply.dev.js */
addComment = {
	moveForm : function(commId, parentId, respondId, postId, num) {
		var t = this, div, comm = t.I(commId), respond = t.I(respondId), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent'), post = t.I('comment_post_ID');
		if ( edit ) exit_prev_edit();
		num ? (
			t.I('comment').value = comm_array[num],
			edit = t.I('new_comm_' + num).innerHTML.match(/(comment-)(\d+)/)[2],
			$new_sucs = $('#success_' + num ), $new_sucs.hide(),
			$new_comm = $('#new_comm_' + num ), $new_comm.hide(),
			$cancel.text(cancel_edit)
		) : $cancel.text(cancel_text);

		t.respondId = respondId;
		postId = postId || false;

		if ( !t.I('wp-temp-form-div') ) {
			div = document.createElement('div');
			div.id = 'wp-temp-form-div';
			div.style.display = 'none';
			respond.parentNode.insertBefore(div, respond)
		}

		!comm ? ( 
			temp = t.I('wp-temp-form-div'),
			t.I('comment_parent').value = '0',
			temp.parentNode.insertBefore(respond, temp),
			temp.parentNode.removeChild(temp)
		) : comm.parentNode.insertBefore(respond, comm.nextSibling);

		$body.animate( { scrollTop: $('#respond').offset().top - 180 }, 400);

		if ( post && postId ) post.value = postId;
		parent.value = parentId;
		cancel.style.display = '';

		cancel.onclick = function() {
			if ( edit ) exit_prev_edit();
			var t = addComment, temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId);

			t.I('comment_parent').value = '0';
			if ( temp && respond ) {
				temp.parentNode.insertBefore(respond, temp);
				temp.parentNode.removeChild(temp);
			}
			this.style.display = 'none';
			this.onclick = null;
			return false;
		};

		try { t.I('comment').focus(); }
		catch(e) {}

		return false;
	},

	I : function(e) {
		return document.getElementById(e);
	}
}; // end addComment

function exit_prev_edit() {
		$new_comm.show(); $new_sucs.show();
		$('textarea').each(function() {this.value = ''});
		edit = '';
}

var wait = 15, submit_val = $submit.val();
function countdown() {
	if ( wait > 0 ) {
		$submit.val(wait); wait--; setTimeout(countdown, 1000);
	} else {
		$submit.val(submit_val).attr('disabled', false).fadeTo('slow', 1);
		wait = 15;
	}
}

});// end jQ