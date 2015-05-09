<?php include 'header.php'; ?>
<body>
<div class="container">
	<?php include 'navigator.php'; ?>

	<div class="about">
		<div style="width:80%;" class="about-row">
			只能看当天发布的内容，每天 00:01分钟 清空以前所有内容，然后统计前一天的帖子和评论数量（只保存数量, 内容会全部删除）
		</div>
		<div style="width:80%;" class="about-row">
			无需注册直接发布内容
		</div>
		<div style="width:80%;" class="about-row">
			<p>
				每个 ip 每天最多发布 50 篇内容 (数据库中会存储ip, 为了防止有人用程序自动发帖)
			</p>
			<p>
				帖子和评论长度文字限制在了500，合集最大长度为20
			</p>
		</div>
		<div class="about-row" style="width:80%;">
			也可以在这里实时分享你正在做的事
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>