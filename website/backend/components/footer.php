<footer class="page-footer center-on-small-only stylish-color-dark">
	<div class="container pt-1 pb-4">
		<img src="/core/html/img/full.png" align="center" class="mx-auto d-block mb-3 mt-2 img-fluid" style="width: 400px">
		<hr class="border-light-grey">

		<ul class="nb-ul list-group list-group-horizontal nav">
			<li class="flex-fill text-center"><a href="/about/mission" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">About Us</a></li>
			<li class="flex-fill text-center"><a href="/about/privacy" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Privacy</a></li>
			<li class="flex-fill text-center"><a href="/about/copyright" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Copyright</a></li>
			<li class="flex-fill text-center"><a href="/about/terms-of-service" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Terms of Service</a></li>
			<li class="flex-fill text-center"><a href="/about/statistics" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Statistics</a></li>
			<li class="flex-fill text-center"><a href="https://www.github.com/lighterlightbulb/Rboxlo" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">GitHub</a></li>
			<li class="flex-fill text-center"><a href="https://discord.gg/W5eCJk9" class="text-white font-weight-light h5 text-fluid nav-item px-3 py-3">Discord</a></li>
		</ul>
	</div>
	<div class="footer-copyright">
		<div class="container-fluid text-center pt-2 pb-2">
			<span><?php echo(BASE_NAME); ?> is made with <i class="material-icons" style="font-size: 1rem" data-toggle="tooltip" title="" data-original-title="lots of love <3">favorite</i> by <a href="<?php echo(GITHUB_URL); ?>/graphs/contributors">many different contributors</a>.</span>
		</div>
	</div>
</footer>

<?php
	// Close database connection
	$statement = null;
	$GLOBALS["sql"] = null;
?>