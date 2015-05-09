<?php include 'header.php'; ?>
<body>
<div class="container">
	<?php include 'navigator.php'; ?>
	<div class="notify">
		<?php if(empty($notification)) {?>
		没有新消息
		<?php } else { ?>
			<?php if (isset($notification['collection'])){ ?>
				<?php foreach ($notification['collection'] as $collection_id => $update_info) { ?>
					<div class="notify-row">
						<?php 
							$url = "/c/" . $update_info['name'] . '?hlftime=' . $update_info['from_timestamp'];
						?>
						<a href="<?php echo $url;?>">
							<?php echo $update_info['name']; ?>
						</a>
						<span> | <?php echo $update_info['count'];?>条更新</span>
					</div>
				<?php } ?>
			<?php } ?>
			<?php if (isset($notification['post'])){ ?>
				<?php foreach ($notification['post'] as $post_id => $update_info) { ?>
					<div class="notify-row">
						<?php 
							$url = "/post.php?id=" . $post_id . '&hlftime=' . $update_info['from_timestamp'];
						?>
						<a href="<?php echo $url;?>">
							<?php echo $update_info['content']; ?>
						</a>
						<span> | <?php echo $update_info['count'];?>条更新</span>
					</div>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</div>
</div>

<?php include 'footer.php'; ?>