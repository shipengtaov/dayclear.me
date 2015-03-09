<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo !empty($title) ? $title : Config::get('app.title');?></title>
		<link rel="stylesheet" href="/static/css/bootstrap.css"/>
		<link rel="stylesheet" href="/static/js/lib/fancybox/jquery.fancybox.css"/>
		<link rel="stylesheet" type="text/css" href="/static/css/main.css"/>
		<?php echo $this->get("inject_head"); ?>
	</head>