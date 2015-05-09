<?php include 'header.php'; ?>
<body>
<div class="container">
	<?php include 'navigator.php'; ?>

	<?php if ($this->get('error_message')) { ?>
	<div class="error">error: <?php echo $this->get('error_message');?></div>
	<?php } ?>
	<div class="comment-post">
		<div class="post-content">
			<div class="post-content-holder">
				<?php echo $post['content'];?>
			</div>
			<div class="follow-post">
				<button post-id="<?php echo $post['id'];?>" status="<?php if(!empty($this->get('has_follow')))echo 'follow';else echo 'unfollow';?>">
					<?php
					if (!empty($this->get('has_follow')))
						echo "正在关注";
					else
						echo "关注";
					?>
				</button>
			</div>
		</div>
		<div class="post-info">
			<span>by: <?php echo $post['user'];?></span>
			<span class="width10"></span>
			<span>
				to: 
				<a href="/c/<?php echo $post['collection'];?>"><?php echo $post['collection'];?></a>
			</span>
			<span class="width10"></span>
			<span>发布于: <?php echo date('H:i:s', strtotime($post['published']));?></span>
			<span class="width10"></span>
			<span>最后更新: <?php echo date('H:i:s', strtotime($post['update_time']));?></span>

		</div>
		<?php if ($this->get('images')){ ?>
		<div class="post-image">
			<?php foreach ($this->get('images') as $image) { ?>
				<div class="post-image-item">
					<a href="<?php echo $image;?>" class="fancybox" rel="gallery">
						<img src="<?php echo $image;?>">
					</a>
				</div>
			<?php } ?>
		</div>
		<?php } ?>
		<div class="comment-form">
			<form action="/discuss.php" method="post">
				<div class="comment-line">
					<span class="comment-desc">用户名:</span>
					<input type="text" name="username" placeholder="默认为Annoymous" value="<?php echo $this->get('username');?>">
				</div>
				<div class="comment-line" style="vertical-align:top;display:table-cell;">
					<span class="comment-desc">评论内容:</span>
					<textarea name="content" id="add-discuss" cols="60" rows="5" required="required" placeholder="评论内容"></textarea>
				</div>
				<input type="hidden" name="post_id" value="<?php echo $post['id'];?>">
				<?php echo $this->get('xsrf_form_html'); ?>
				<div class="comment-line">
					<span class="comment-desc"></span>
					<span style="color:#c8c8c8;">[url]link[/url], [img]image url[/img]</span>
				</div>
				<div class="comment-line">
					<span class="comment-desc"></span>
					<input type="submit" value="提交">
				</div>
			</form>
		</div>
		<div class="comment">
			<?php if (!empty($discuss)) { ?>
				<div class="comment-title">
					评论
				</div>
				<?php foreach ($discuss as $key => $row) { ?>
					<div class="comment-row">
						<div class="comment-parent <?php if($this->get('highlight_id') == $row['id']) echo "highlight-discuss";else if(!empty($this->get('highlight_from_id')) && $this->get('highlight_from_id')<=$row['id'])echo "highlight-discuss";?>"  id="<?php echo $row['id'];?>">
							<span class="comment-floor">
								<a href="/post.php?id=<?php echo $post['id'];?>&hlid=<?php echo $row['id'];?>#<?php echo $row['id'];?>">
									#<?php echo $key+1;?>
								</a>
							</span>
							<div class="comment-content">
								<?php echo $row['content']; ?>
							</div>
							<div class="comment-info">
								<span>by: <?php echo $row['user'];?></span>
								<span><?php echo $row['discuss_time'];?></span>
								<span class="reply-box" d-id="<?php echo $row['id'];?>">
									<a>回复</a>
								</span>
							</div>
						</div>
						<?php 
							if (!empty($reply[$row['id']])){
								foreach ($reply[$row['id']] as $key2 => $reply_row) { ?>
								<div class="comment-reply <?php if ($this->get('highlight_id')==$reply_row['id'])echo "highlight-discuss";else if( !empty($this->get('highlight_from_id')) && $this->get('highlight_from_id')<=$reply_row['id'])echo "highlight-discuss";?>" id="<?php echo $reply_row['id'];?>">
									<span class="comment-floor">
										<a href="/post.php?id=<?php echo $post['id'];?>&hlid=<?php echo $reply_row['id'];?>#<?php echo $reply_row['id'];?>">
											<?php echo $key2+1;?>单元
										</a>
									</span>
									<div class="comment-content">
										<?php echo $reply_row['content'];?>
									</div>
									<div class="comment-info">
										<span>by: <?php echo $reply_row['user'];?></span>
										<span><?php echo $reply_row['discuss_time'];?></span>
									</div>
								</div>
						<?php
								}
							} ?>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<input type="hidden" id="post-id" value="<?php echo $post['id'];?>">
	</div>
</div>
<div class="hidden" id="reply-form-template">
	<div id="reply-form" class="comment-reply">
		<form action="/discuss.php" method="post">
			<div class="comment-line">
				<span class="comment-desc">用户名:</span>
				<input type="text" name="username" placeholder="默认为Annoymous" value="<?php echo $this->get('username');?>">
			</div>
			<div class="comment-line">
				<span class="comment-desc">回复内容:</span>
				<textarea name="content" cols="60" rows="2" required="required" placeholder="回复内容"></textarea>
			</div>
			<input type="hidden" name="post_id" value="{{post_id}}">
			<input type="hidden" name="reply_id" value="{{reply_id}}">
			<div class="comment-line">
				<span class="comment-desc"></span>
				<button class="cancel-reply">取消</button>
				<input type="submit" value="提交">
			</div>
		</form>
	</div>
</div>

<?php include 'footer.php'; ?>