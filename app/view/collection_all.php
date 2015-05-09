<?php include 'header.php'; ?>
<body>
<style>
	.order-collection span{
		display: inline-block;
		margin-right: 10px;
	}
	.order-collection a{
		color: #A8A9AD;
	}
	.current-order{
		border-top: 2px solid #f90;
	}
	.order-collection a:hover{
		text-decoration: none;
	}
</style>
<div class="container">
	<?php include 'navigator.php'; ?>

	<div style="width:80%;">
		<?php if ($this->get('collections')) { ?>
			<div class="order-collection">
				<span<?php if($this->get('current_order_by') == 'count')echo ' class="current-order"';?>>
					<a href="/c?order_by=count">按帖子数排序</a>
				</span>
				<span<?php if($this->get('current_order_by') == 'create_time')echo ' class="current-order"';?>>
					<a href="/c?order_by=create_time">按创建时间排序</a>
				</span>
			</div>
			<?php 
			$index = 0;
			foreach($collections as $collection){
				if ($index%50 == 0)
					echo '<div class="sep20"></div>';
				$index++;
			?>
			<span style="margin-right:10px;">
				<a href="/c/<?php echo $collection['name'];?>">
					<?php echo $collection['name'];?>(<?php echo $collection['count'];?>)
				</a>
			</span>
			<?php } ?>
		<?php } else { ?>
		还没有合集
		<?php } ?>
	</div>
</div>

<?php include 'footer.php'; ?>