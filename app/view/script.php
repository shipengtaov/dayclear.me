<?php include 'header.php'; ?>
<body>
<div class="container">
	<?php include 'navigator.php'; ?>

	<div class="about">
		<div style="width:80%;" class="about-row">
			<p>
				<p>
					每天 0点1分 会通过 crontab 执行
				</p>
				<p style="margin-left:20px;">
					`delete from post where published &lt; (今天的00:00:00)`
				</p>
				<p style="margin-left:20px;">
					`delete from discuss where discuss_time &lt; (今天的00:00:00)`
				</p>
				<p style="margin-left:20px;">
					`delete from collection where create_time &lt; (今天的00:00:00)`
				</p>
			</p>
		</div>

		<div class="about-row" style="width:80%;">
			<h5>支持的标签</h5>
			<div style="word-wrap:break-word; border-bottom:1px solid #e2e2e2; width:80%;">
				<h6>url 标签</h6>
				<p>使用 [url][/url] 包裹起来将会自动生成超链接形式</p>
			</div>
			<div style="word-wrap:break-word; border-bottom:1px solid #e2e2e2; width:80%;">
				<h6>img 标签</h6>
				<p>使用 [img][/img] 包裹起来将会显示为图片, 包裹内容需要为图片url; 暂时只有帖子中的[img]生效, 评论内容中[img]无效</p>
				<p>
					图片上传服务: <a href="https://imgur.com/" target="_blank">https://imgur.com/</a>
				</p>
			</div>
		</div>
		<div class="about-row" style="width:80%:">
			<h5>cookie</h5>
			<div style="word-wrap:break-word; border-bottom:1px solid #e2e2e2; width:80%;">
				<h6><?php echo $cookie['publish']['name']; ?></h6>
				<p>说明：<?php echo $cookie['publish']['text'];?></p>
				<p>当前值(原始值)：<?php echo $cookie['publish']['origin_value']; ?></p>
				<p>当前值(格式化后的值)：<?php print_r($cookie['publish']['format_value']); ?></p>
			</div>

			<div style="word-wrap:break-word; border-bottom:1px solid #e2e2e2; width:80%;">
				<h6><?php echo $cookie['notify_post']['name']; ?></h6>
				<p>说明：<?php echo $cookie['notify_post']['text'];?></p>
				<p>当前值(原始值)：<?php echo $cookie['notify_post']['origin_value']; ?></p>
				<p>当前值(格式化后的值)：<?php print_r($cookie['notify_post']['format_value']); ?></p>
			</div>

			<div style="word-wrap:break-word; border-bottom:1px solid #e2e2e2; width:80%;">
				<h6><?php echo $cookie['notify_collection']['name']; ?></h6>
				<p>说明：<?php echo $cookie['notify_collection']['text'];?></p>
				<p>当前值(原始值)：<?php echo $cookie['notify_collection']['origin_value']; ?></p>
				<p>当前值(格式化后的值)：<?php print_r($cookie['notify_collection']['format_value']); ?></p>
			</div>

			<div style="word-wrap:break-word; border-bottom:1px solid #e2e2e2; width:80%;">
				<h6><?php echo $cookie['notification']['name']; ?></h6>
				<p>说明：<?php echo $cookie['notification']['text'];?></p>
				<p>当前值(原始值)：<?php echo $cookie['notification']['origin_value']; ?></p>
				<p>当前值(格式化后的值)：<?php print_r($cookie['notification']['format_value']); ?></p>
			</div>

			<div style="word-wrap:break-word; border-bottom:1px solid #e2e2e2; width:80%;">
				<h6><?php echo $cookie['max_post']['name']; ?></h6>
				<p>说明：<?php echo $cookie['max_post']['text'];?></p>
				<p>当前值(原始值)：<?php echo $cookie['max_post']['origin_value']; ?></p>
				<p>当前值(格式化后的值)：<?php print_r($cookie['max_post']['format_value']); ?></p>
			</div>

			<div style="word-wrap:break-word; border-bottom:1px solid #e2e2e2; width:80%;">
				<h6><?php echo $cookie['username']['name']; ?></h6>
				<p>说明：<?php echo $cookie['username']['text'];?></p>
				<p>当前值(原始值)：<?php echo $cookie['username']['origin_value']; ?></p>
				<p>当前值(格式化后的值)：<?php print_r($cookie['username']['format_value']); ?></p>
			</div>

			<div style="word-wrap:break-word;">
				<h6><?php echo $cookie['PHPSESSID']['name']; ?></h6>
				<p>说明：<?php echo $cookie['PHPSESSID']['text'];?></p>
				<p>当前值(原始值)：<?php echo $cookie['PHPSESSID']['origin_value']; ?></p>
				<p>当前值(格式化后的值)：<?php print_r($cookie['PHPSESSID']['format_value']); ?></p>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>