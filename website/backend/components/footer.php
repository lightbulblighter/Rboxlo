<footer class="page-footer center-on-small-only stylish-color-dark">
	<div class="container pt-0 pb-4">
		<img src="<?php echo(get_server_host()); ?>/html/img/logos/2016/full.png" align="center" class="mx-auto d-block mb-3 mt-0 img-fluid" width="200">
		<hr class="border-light-grey">

		<ul class="nb-ul list-group list-group-horizontal nav">
			<li class="flex-fill text-center"><a href="<?php echo(get_server_host()); ?>/about/mission" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">About Us</a></li>
			<li class="flex-fill text-center"><a href="<?php echo(get_server_host()); ?>/about/privacy" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Privacy</a></li>
			<li class="flex-fill text-center"><a href="<?php echo(get_server_host()); ?>/about/copyright" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Copyright</a></li>
			<li class="flex-fill text-center"><a href="<?php echo(get_server_host()); ?>/about/terms-of-service" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Terms of Service</a></li>
			<li class="flex-fill text-center"><a href="<?php echo(get_server_host()); ?>/about/statistics" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Statistics</a></li>
			<li class="flex-fill text-center"><a href="<?php echo(ENVIRONMENT["REPOSITORY"]["URL"]); ?>" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">GitHub</a></li>
			<li class="flex-fill text-center"><a href="<?php echo(ENVIRONMENT["PROJECT"]["DISCORD"]); ?>" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Discord</a></li>
		</ul>
	</div>
	<div class="footer-copyright">
		<div class="container-fluid text-center pt-2 pb-2">
			<span><?php echo(ENVIRONMENT["PROJECT"]["NAME"]); ?> is made with <i class="material-icons" style="font-size: 1rem" data-toggle="tooltip" title="" data-original-title="lots of love <3">favorite</i> by <a href="<?php echo(ENVIRONMENT["REPOSITORY"]["URL"]); ?>/graphs/contributors">many different contributors</a>.</span>
		</div>
	</div>
</footer>

<?php
	build_js();
?>

<!-- Begin consent documents -->
<!-- Begin cookie consent -->
<script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
<script>
window.cookieconsent.initialise({
	"palette": {
		"popup": {
			"background": "#ffffff",
			"text": "#212529"
		}
    },

    "button": {
    	"background": "#6f42c1",
    	"text": "#ffffff"
    },

	"content": {
		"href": "https://www.cookiepolicygenerator.com/live.php?token=Fdqho7wVRjAStnQAVbUsdIiT8UmsTOzR"
	},

	"theme": "classic",
	"position": "bottom-right",
});
</script>
<!-- End cookie consent -->

<?php
    if (!isset($_COOKIE["consent"]) || empty($_COOKIE["consent"]) || $_COOKIE["consent"] != "true" || $_COOKIE["consent"] != true)
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../backend/components/consent.php");
    }
?>
<!-- End consent documents -->