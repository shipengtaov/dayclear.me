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
			</p>
		</div>
		<div class="about-row" style="width:80%;">
			<h5>数据库设计</h5>
			<p>
				<code>
					CREATE TABLE `post`(<br>
						<span class="code-indent"></span>`id` int(11) NOT NULL auto_increment PRIMARY KEY,<br>
						<span class="code-indent"></span>`user` varchar(30) NOT NULL DEFAULT '' COMMENT 'user name',<br>
						<span class="code-indent"></span>`content` text NOT NULL DEFAULT '',<br>
						<span class="code-indent"></span>`ip` varchar(128) NOT NULL DEFAULT '0.0.0.0',<br>
						<span class="code-indent"></span>`published` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'<br>
					) ENGINE=MyISAM AUTO_INCREMENT=10000 CHARSET=utf8;<br>
				</code>
			<p>
				<code>
					CREATE TABLE `discuss`(<br>
						<span class="code-indent"></span>`id` int(11) NOT NULL auto_increment,<br>
						<span class="code-indent"></span>`post_id` int(11) NOT NULL DEFAULT 0,<br>
						<span class="code-indent"></span>`reply_id` int(11) not null default 0,<br>
						<span class="code-indent"></span>`user` varchar(30) NOT NULL DEFAULT '' COMMENT '评论用户名',<br>
						<span class="code-indent"></span>`content` text NOT NULL DEFAULT '' COMMENT '讨论内容',<br>
						<span class="code-indent"></span>`ip` varchar(128) NOT NULL DEFAULT '0.0.0.0',<br>
						<span class="code-indent"></span>`discuss_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',<br>
						<span class="code-indent"></span>PRIMARY KEY(`id`),<br>
						<span class="code-indent"></span>INDEX (`post_id`)<br>
					) ENGINE=MyISAM AUTO_INCREMENT=10000 CHARSET=utf8;<br>
				</code>
			</p>
			<p>
				<code>
					CREATE TABLE `history`(<br>
						<span class="code-indent"></span>`id` int(11) NOT NULL auto_increment,<br>
						<span class="code-indent"></span>`post` int(11) NOT NULL DEFAULT 0 COMMENT "帖子数",<br>
						<span class="code-indent"></span>`discuss` int(11) NOT NULL DEFAULT 0 COMMENT "评论数",<br>
						<span class="code-indent"></span>`date` date NOT NULL DEFAULT '0000-00-00' COMMENT "时间",<br>
						<span class="code-indent"></span>PRIMARY KEY(`id`),<br>
						<span class="code-indent"></span>UNIQUE `date` (`date`)<br>
					) ENGINE=MyISAM AUTO_INCREMENT=10000 CHARSET=utf8;<br>
				</code>
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
					可以使用 <a href="http://www.mftp.info/" target="_blank">http://www.mftp.info/</a>，上传自己的图片; 注意：这个网站是不是可以信任需要你自己判断, 我也是从网上搜来的; 
				</p>
				<p>
					本来想用 <a href="https://imgur.com/" target="_blank">https://imgur.com/</a>, 但是提示匿名上传在中国不可用
				</p>
			</div>
		</div>
		<div class="about-row" style="width:80%:">
			<h5>cookie</h5>
			<div style="word-wrap:break-word; border-bottom:1px solid #e2e2e2; width:80%;">
				<h6><?php echo $cookie['notify_post']['name']; ?></h6>
				<p>说明：<?php echo $cookie['notify_post']['text'];?></p>
				<p>当前值(原始值)：<?php echo $cookie['notify_post']['origin_value']; ?></p>
				<p>当前值(格式化后的值)：<?php print_r($cookie['notify_post']['format_value']); ?></p>
			</div>
			<div style="word-wrap:break-word; border-bottom:1px solid #e2e2e2; width:80%;">
				<h6><?php echo $cookie['publish']['name']; ?></h6>
				<p>说明：<?php echo $cookie['publish']['text'];?></p>
				<p>当前值(原始值)：<?php echo $cookie['publish']['origin_value']; ?></p>
				<p>当前值(格式化后的值)：<?php print_r($cookie['publish']['format_value']); ?></p>
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