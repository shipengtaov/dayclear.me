<?php include 'header.php'; ?>
<body>
<div class="container">
	<?php include 'navigator.php'; ?>

	<?php if ($this->get('error_message')) { ?>
	<div class="error">error: <?php echo $this->get('error_message');?></div>
	<?php } ?>
	<div class="collection-container">
		<div class="collection-info">
			<div style="line-height:120%;padding:10px;border-left:5px solid #c7e8ff;">
				当前合集: <?php echo htmlspecialchars($collection_info['name']);?>
			</div>
			<div class="follow-collection">
				<span>共: <?php echo $collection_info['count'];?></span>
				<span>
					<a href="/post.php?collection=<?php echo $collection_info['name'];?>">发表</a>
				</span>
				<span>
					<button collection-id="<?php echo $collection_info['id'];?>" status="<?php if(!empty($this->get('has_follow')))echo 'follow';else echo 'unfollow';?>">
						<?php
						if (!empty($this->get('has_follow')))
							echo "正在关注";
						else
							echo "关注";
						?>
					</button>
				</span>
			</div>
		</div>

		<?php if ($this->get('posts')) { ?>
		<table class="content">
			<?php foreach ($posts as $post) { ?>
				<tr><td id="<?php echo $post['id'];?>" 
						<?php 
							if($this->get('highlight_id') == $post['id'])
								echo 'class="highlight-post"';
							else if(!empty($this->get('highlight_from_time')) 
									&& empty($this->get('highlight_id')) 
									&& $post['published'] > $this->get('highlight_from_time'))
								echo 'class="highlight-post"';
						?>
					>
					<table>
						<tr class="height10"></tr>
						<tr>
							<td>
								<a href="/post.php?id=<?php echo $post['id'];?>" style="color:#333;">
									<?php echo $post['content'];?>
								</a>
							</td>
						</tr>
						<tr class="post-info">
							<td>
								<div>
										<span>by: <?php echo $post['user'];?></span>
										<span class="width10"></span>

										<span>
											<a href="/post.php?id=<?php echo $post['id'];?>">
												讨论(<?php echo $post['discuss'];?>)
											</a>
										</span>
										<span class="width10"></span>

										<span>
											<a href="/c/<?php echo $post['collection'];?>">
												to: <?php echo $post['collection'];?>
											</a>
										</span>
										<span class="width10"></span>

<!-- 										<span>发布: <?php echo $post['published'];?></span>
										<span class="width10"></span>
 -->
										<span>最后更新: <?php echo date('H:i:s', strtotime($post['update_time']));?>
										</span>
								</div>
							</td>
						</tr>
						<tr class="height10"></tr>
					</table>
				</td></tr>
			<?php } ?>
		</table>			
		<?php } else { ?>
			<span class="gray">暂无内容</span>
		<?php } ?>
	</div>
	<div class="page">
		<?php echo $pagination_html;?>
	</div>
</div>

<?php include 'footer.php'; ?>
