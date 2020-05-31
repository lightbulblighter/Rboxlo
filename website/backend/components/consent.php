<!-- Consent form begin -->
<script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
<script type="text/javascript">
    function agreeToConsent()
    {
        Cookies.set("consent", true, { expires: 2147483647 });
    }
</script>

<div class="modal fade" id="consentModal" tabindex="-1" role="dialog" aria-labelledby="consentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
			    <div class="modal-title mx-auto">
                    <img src="/html/img/logos/2016/full.png" class="img-fluid" width="200"/>
                </div>
			</div>

            <div class="modal-body">
                <h3 class="font-weight-bold">It looks like you're new here!</h3>

                <p class="text-justify">
                    Before playing on <?php echo(ENVIRONMENT["PROJECT"]["NAME"]); ?>, you should know that <?php echo(ENVIRONMENT["PROJECT"]["NAME"]); ?> is a fan-made revival of Roblox, created using publicly available downloads and information made freely available to the general public.
                </p>
                
                <p class="text-justify">
                    By accessing the <?php echo(ENVIRONMENT["PROJECT"]["NAME"]); ?> website, you certify that you understand that <?php echo(ENVIRONMENT["PROJECT"]["NAME"]); ?> is not affiliated with Roblox Corporation and you hereby release Roblox Corporation, as well as any employees or agents of Roblox Corporation, from any and all liability, corporate, or personal loss caused to you or others by the use of <?php echo(ENVIRONMENT["PROJECT"]["NAME"]); ?>.
                </p>
                
                <p class="text-justify">
                    You also understand that <?php echo(ENVIRONMENT["PROJECT"]["NAME"]); ?> is dedicated to the public domain, and that <?php echo(ENVIRONMENT["PROJECT"]["NAME"]); ?> is an entirely free game, funded out of pocket by its staff members. This game contains no subscriptions, advertisements, donations, or any other forms of revenue.
                </p>
            </div>

			<div class="modal-footer">
			    <a role="button" class="btn btn-link mr-auto waves-effect waves-light" tabindex="-1" href="https://www.roblox.com/games">
                    <span>I want to leave.</span>
                </a>

                <button type="button" class="btn btn-success waves-effect waves-light" onclick="agreeToConsent()" data-dismiss="modal">
                    Sounds good!
                </button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $("#consentModal").modal("show");
</script>
<!-- Consent form end -->