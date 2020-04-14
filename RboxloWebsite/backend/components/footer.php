<?php
	include_once(BASE_PATH ."/backend/configuration.php");
?>

<footer class="page-footer center-on-small-only stylish-color-dark">
	<div class="container pt-1 pb-1">
		<img src="/core/html/img/full.png" align="center" class="mx-auto d-block" style="max-width: 500px">
		<div class="row">
			<div class="col-md-4">
				<h5 class="title mb-4 mt-3 font-bold">About</h5>
				<p>Use of this site means that you, the user, have agreed to the <a href="/terms-of-service">Terms of Service</a> and have read the <a href="/privacy-policy">Privacy Policy</a>.</p>
				<p>Read more about <?php echo(BASE_NAME); ?> and our mission at our <a href="/about">About</a> page.</p>
			</div>
			<hr class="clearfix w-100 d-md-none">
			<div class="col-md-5 mx-auto" style="text-align: right;">
				<h5 class="title mb-4 mt-3 font-bold">More Links</h5>
				<ul class="nb-ul">
					<li><a href="/about">About</a></li>
					<li><a href="/privacy-policy">Privacy Policy</a></li>
					<li><a href="/copyright">Copyright</a></li>
					<li><a href="/terms-of-service">Terms of Service</a></li>
					<li><a href="<?php echo(GITHUB_URL); ?>">GitHub</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="footer-copyright">
		<div class="container-fluid text-center pt-1 pb-1">
			<span><?php echo(BASE_NAME); ?> is made with <i class="material-icons">favorite</i> by <?php echo(BASE_AUTHORS); ?>.</span>
		</div>
	</div>
</footer>