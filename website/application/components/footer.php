<footer class="page-footer center-on-small-only stylish-color-dark">
	<div class="container pt-3 pb-4">
		<img src="<?= get_server_host() ?>/html/img/logos/2016/full.png" align="center" class="mx-auto d-block mb-3 mt-0 img-fluid" width="200" >
		<hr class="border-light-grey">

		<ul class="nb-ul list-group list-group-horizontal nav">
			<li class="flex-fill text-center"><a href="<?= get_server_host() ?>/about/mission" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">About Us</a></li>
			<li class="flex-fill text-center"><a href="<?= get_server_host() ?>/about/privacy" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Privacy</a></li>
			<li class="flex-fill text-center"><a href="<?= get_server_host() ?>/about/copyright" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Copyright</a></li>
			<li class="flex-fill text-center"><a href="<?= get_server_host() ?>/about/terms-of-service" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Terms of Service</a></li>
			<li class="flex-fill text-center"><a href="<?= get_server_host() ?>/about/statistics" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Statistics</a></li>
			<li class="flex-fill text-center"><a href="<?= REPOSITORY["URL"] ?>" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">GitHub</a></li>
			<li class="flex-fill text-center"><a href="<?= PROJECT["DISCORD"]?>" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Discord</a></li>
		</ul>
	</div>
	<div class="footer-copyright">
		<div class="container-fluid text-center pt-2 pb-2">
			<span>
				<?= PROJECT["NAME"] ?> is made with <i class="material-icons" style="font-size: 1rem" data-toggle="tooltip" title="" data-original-title="lots of love <3">favorite</i> by <a href="<?= REPOSITORY["URL"] ?>/graphs/contributors">many different contributors</a>.
			</span>
		</div>
	</div>
</footer>

<?php
	build_js();
?>

<!-- Begin consent documents -->
<?php
    if (!isset($_COOKIE["consent"]) || empty($_COOKIE["consent"]) || $_COOKIE["consent"] != "true" || $_COOKIE["consent"] != true)
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/components/consent.php");
    }
?>
<!-- End consent documents -->