<?php include 'header.php'; ?>
<body>
<div class="container">
	<?php include 'navigator.php'; ?>
	<div class="notify">
		<?php if(empty($history)) {?>
		还没有历史记录
		<?php } else { ?>
		<?php foreach ($history as $history_row) { ?>
			<div class="notify-row">
				<h5><?php echo $history_row['date']; ?></h5>
				<p>
					帖子数: <?php echo $history_row['post'];?>
					, 
					讨论数: <?php echo $history_row['discuss'];?>
				</p>
			</div>
		<?php } /* foreach */ ?>
		<?php } /* else */ ?>
	</div>
	<div class="page">
		<?php echo $pagination_html;?>
	</div>
</div>

<?php include 'footer.php'; ?>