<?php include 'header.php'; ?>
<body>
<div class="container">
	<?php include 'navigator.php'; ?>
	<div class="notify">
		<?php if(empty($notification)) {?>
		没有新消息
		<?php } else { ?>
		<?php foreach ($notification as $post_id => $update_info) { ?>
			<div class="notify-row">
				<?php 
					$url = "/post.php?id=" . $post_id . '&hlfid=' . $update_info['from_id'] . '#' . $update_info['from_id'];
				?>
				<a href="<?php echo $url;?>">
					<?php echo $update_info['post_content']; ?>
				</a>
				<span> | <?php echo $update_info['count'];?>条更新</span>
			</div>
		<?php } ?>
		<?php } ?>
	</div>
</div>

<?php include 'footer.php'; ?>