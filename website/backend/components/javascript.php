<!-- Begin javascript includes -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.3.3/umd/popper.min.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo(get_server_host()); ?>/html/js/mdb.min.js"></script>
<script type="text/javascript" src="<?php echo(get_server_host()); ?>/html/js/mdb-plugins-gathered.min.js"></script>
<script type="text/javascript" src="<?php echo(get_server_host()); ?>/html/js/site.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo(ENVIRONMENT["GOOGLE"]["ANALYTICS_TAG"]); ?>"s></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?php echo(ENVIRONMENT["GOOGLE"]["ANALYTICS_TAG"]); ?>');
</script>
<!-- End javascript includes -->