<?php
	if (!empty($page_name))
	{
		$title = $page_name ." | ". BASE_NAME;
	}
	else
	{
		$title = BASE_NAME;
	}
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title><?php echo($title); ?></title>
<link rel="shortcut icon" type="image/png" href="https://<?php echo(BASE_URL); ?>/core/html/img/logo.png">

<link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://<?php echo(BASE_URL); ?>/core/html/css/mdb.min.css">
<link rel="stylesheet" href="https://<?php echo(BASE_URL); ?>/core/html/css/mdb-plugins-gathered.min.css">
<link rel="stylesheet" href="https://<?php echo(BASE_URL); ?>/core/html/css/custom/global.css?v=<?php echo(get_version()); ?>">
<style>
	@import url("/core/html/css/custom/dark.css") (prefers-color-scheme: dark);
</style>