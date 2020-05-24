<?php
	$title = "";
	
	if (!empty($page_name))
	{
		$title = $page_name ." | ". ENVIRONMENT["PROJECT"]["NAME"];
	}
	else
	{
		$title = ENVIRONMENT["PROJECT"]["NAME"];
	}
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title><?php echo($title); ?></title>
<link rel="shortcut icon" type="image/png" href="<?php echo(get_server_host()); ?>/html/img/logos/2016/icon.png">

<link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo(get_server_host()); ?>/html/css/mdb.min.css">
<link rel="stylesheet" href="<?php echo(get_server_host()); ?>/html/css/mdb-plugins-gathered.min.css">
<link rel="stylesheet" href="<?php echo(get_server_host()); ?>/html/css/site.min.css">