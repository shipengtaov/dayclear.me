<?php include 'header.php'; ?>
<body>
<div class="container">
	<?php include 'navigator.php'; ?>

	<div style="width:80%;">
		<?php if ($this->get('collections')) { ?>
			<?php 
			$index = 0;
			foreach($collections as $collection){
				if ($index >0 and $index%50 == 0)
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
		还没有关注任何合集
		<?php } ?>
	</div>

</div>

<?php include 'footer.php'; ?>