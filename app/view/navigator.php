<div class="container nav">
	<li id="nav-home" <?php if($this->get('has_new_post'))echo 'class="global-actions-post-new"';?>>
		<a href="/">首页</a>
	</li>

	<li>
		<a href="/post.php">发表</a>
	</li>

	<li>
		<a href="/follow.php">关注列表</a>
	</li>

	<li id="nav-notify" <?php if($this->get('has_notify'))echo 'class="global-actions-notify-new"';?>>
		<a href="/notify.php">提醒</a>
	</li>

	<li>
		<a href="/关于">关于</a>
	</li>

	<li>
		<a href="/source.php">脚本</a>
	</li>

	<li>
		<a href="/history.php">历史</a>
	</li>
</div>