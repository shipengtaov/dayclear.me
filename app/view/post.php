<?php include 'header.php'; ?>
<body>
<div class="container">
	<?php include 'navigator.php'; ?>

	<?php if ($this->get('error_message')) { ?>
	<div class="error">error: <?php echo $this->get('error_message');?></div>
	<?php } ?>
	<form action="/post.php" method="post" class="post-form">
		<table>
			<tr>
				<td style="text-align:left;">用户名：</td>
				<td style="text-align:left;">
					<input type="text" name="username" placeholder="默认为 Annoymous" value="<?php echo $this->get('username');?>" />
				</td>
			</tr>
			<tr class="height10"></tr>
			<tr>
				<td style="text-align:left;">合集：</td>
				<td style="text-align:left;">
					<input type="text" name="collection" placeholder="合集名称" required="required" value="<?php echo $this->get('collection');?>" style="width:500px;"/>
				</td>
			</tr>
			<tr class="height10"></tr>
			<tr>
				<td style="text-align:left;vertical-align:top;">
					内容：
				</td>
				<td>
					<textarea name="content" id="add-post" cols="60" rows="10" required="required" style="width:500px;"></textarea>
				</td>
			</tr>
			<tr class="height10"></tr>
			<tr>
				<td></td>
				<td style="color:#c8c8c8;">[url]link[/url], [img]image url[/img]</td>
			</tr>
			<tr class="height10"></tr>
			<tr>
				<td style="text-align:left;"></td>
				<td><input type="submit" value="发布" /></td>
			</tr>
		</table>
		<?php echo $this->get('xsrf_form_html'); ?>
	</form>
</div>

<?php include 'footer.php'; ?>