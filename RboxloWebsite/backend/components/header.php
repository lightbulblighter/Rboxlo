<?php
    include_once(BASE_PATH ."/backend/configuration.php");
    
	$title = BASE_NAME;
	if (!empty($page_name))
	{
		$title .= " | " . $page_name;
	}
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title><?php echo($title); ?></title>
<link rel="shortcut icon" type="image/png" href="/core/html/img/logo.png">

<link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap">
<link rel="stylesheet" href="/core/html/css/bootstrap.min.css">
<link rel="stylesheet" href="/core/html/css/mdb.min.css">
<link rel="stylesheet" href="/core/html/css/mdb-plugins-gathered.min.css">
<style type="text/css">/* Chart.js */
@-webkit-keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}@keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s;}</style>
<link rel="stylesheet" href="/core/html/css/custom/global.css?v=<?php echo(BASE_VERSION); ?>">
