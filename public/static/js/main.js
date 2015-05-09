
function no_highlight_post(highlight_class, timeout){
	timeout = timeout || 5000;
	var st = setTimeout(function(){
		$("." + highlight_class).removeClass(highlight_class);
	}, timeout);
}

function reply(event){
	var $target = $(event.target).parent(".reply-box");
	$target.parents(".comment").find('#reply-form').remove();
	var post_id = $("#post-id").val();
	var reply_id = $target.attr("d-id");
	var $comment_parent = $target.parents(".comment-parent");
	var html = $("#reply-form-template").html();
	var template = Handlebars.compile(html);
	html = template({"post_id": post_id, "reply_id": reply_id});
	$comment_parent.append(html);
	$("#reply-form textarea").focus();
}

function cancel_reply(event){
	event.preventDefault();
	var $target = $(event.target);
	$target.parents("#reply-form").remove();
	return false;
}

function poll_notify(){
	$.ajax({
		url: "/notify.php",
		dateType: "json",
		type: "post",
		// timeout: 5,
		success: function(data){
			if (data.notify.has_update > 0){
				console.log("有新提醒");
				$("#nav-notify").addClass("global-actions-notify-new");
			} else {
				$("#nav-notify").removeClass("global-actions-notify-new");
			}
			if (data.post.has_update > 0){
				console.log("有新帖子");
				$("#nav-home").addClass("global-actions-post-new");
				$("#nav-home a").attr("href", "/?hlftime=" + data.post.highlight_min);
			} else {
				$("#nav-home").removeClass('global-actions-post-new');
			}
		}
	})
}

function check_submit(event){
	var $form = $(event.target).parents("form");

	if (!$form.find("textarea").val() 
		|| ($form.find("input[name=collection]").length>0 && !$form.find("input[name=collection]").val())
	){
		event.preventDefault();
		return false;
	}
}

function follow_or_unfollow(event, type, callback){
	var $target = $(event.target);
	var status = $target.attr('status');

	var data = new Object();

	if (type == 'post') {
		data['post_id'] = $target.attr('post-id');
		url = "/follow.php";
	} else if (type == 'collection') {
		data['collection_id'] = $target.attr('collection-id');
		url = "/follow/c";
	}

	if (status == 'follow'){
		// 取消关注
		data['action'] = 'unfollow';
		$.ajax({
			url: url,
			type: "post",
			dateType: "json",
			data: data,
			success: function(data){
				if (data.code != 0){
					if (data.msg)
						alert(data.msg);
					else
						alert('出错了');
				} else {
					$target.attr('status', 'unfollow');
					$target.text('关注');
					if (callback){
						callback($target);
					}
				}
			}
		});
	} else if (status == 'unfollow'){
		// 关注
		data['action'] = 'follow';
		$.ajax({
			url: url,
			type: "post",
			dateType: "json",
			data: data,
			success: function(data){
				if (data.code != 0){
					if (data.msg)
						alert(data.msg);
					else
						alert('出错了');
				} else {
					$target.attr('status', 'follow');
					$target.text('正在关注');
					if (callback){
						callback($target);
					}
				}
			}
		});
	}
}

function follow_post_button_mouseover(event){
	var $target = $(event.target);
	var status = $target.attr('status');
	if (status == 'follow'){
		$target.addClass('follow-to-unfollow');
		$target.text('取消关注');
	} else if (status == 'unfollow'){
		$target.addClass('unfollow-to-follow');
		$target.text('添加关注');
	}
}
function follow_post_button_mouseout(event){
	var $target = $(event.target);
	var status = $target.attr('status');
	if (status == 'follow'){
		$target.removeClass('follow-to-unfollow');
		$target.text('正在关注');
	} else if (status == 'unfollow'){
		$target.removeClass('unfollow-to-follow');
		$target.text('关注');
	}
}


$(function(){
	no_highlight_post("highlight-post");
	no_highlight_post("highlight-discuss");

	$(".reply-box a").click(function(event){
		reply(event);
	});
	$(document).on("click", ".cancel-reply", function(event){
		cancel_reply(event);
	});

	poll_notify();
	setInterval(poll_notify, 5000);

	$(".post-form input[type=submit]").click(function(event){
		check_submit(event);
	});
	$(".comment-form input[type=submit]").click(function(event){
		check_submit(event);
	});
	$(document).on("click", "#reply-form", function(event){
		check_submit(event);
	});

	$(".follow-post button").click(function(event){
		follow_or_unfollow(event, 'post', function(target){
			target.removeClass('follow-to-unfollow');
			target.removeClass('unfollow-to-follow');
		});
	});
	$(".follow-post button").mouseover(function(event){
		follow_post_button_mouseover(event);
	});
	$(".follow-post button").mouseout(function(event){
		follow_post_button_mouseout(event);
	});

	$(".follow-collection button").click(function(event){
		follow_or_unfollow(event, 'collection', function(target){
			target.removeClass('follow-to-unfollow');
			target.removeClass('unfollow-to-follow');
		});
	});
	$(".follow-collection button").mouseover(function(event){
		follow_post_button_mouseover(event);
	});
	$(".follow-collection button").mouseout(function(event){
		follow_post_button_mouseout(event);
	});


	$(".fancybox").fancybox({
		openEffect: "none",
		closeEffect: "none",
		padding: 0
	});
});