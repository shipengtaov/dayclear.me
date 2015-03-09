<?php include 'header.php'; ?>
<body>
<div class="container">
	<?php include 'navigator.php'; ?>
	<div class="notify">
		<?php if(empty($follow_list)) {?>
		还没有任何关注
		<?php } else { ?>
		<?php foreach ($follow_list as $post_id => $post_info) { ?>
			<div class="notify-row">
				<a href="/post.php?id=<?php echo $post_info['id']; ?>">
					<?php echo $post_info['content']; ?>
				</a>
			</div>
		<?php } /* foreach */ ?>
		<?php } /* else */ ?>
	</div>
</div>

<?php include 'footer.php'; ?>