<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/../application/includes.php");
	
	if (!isset($_SESSION["user"]))
	{
	    redirect("/login");
    }
    
    if ($_SESSION["user"]["permissions"]["admin"]["see_panel"])
    {
        include_page("/error/403.php"); // Forbidden
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <?php
            build_header("Admin Panel");
        ?>
    </head>

    <body>
        <?php
            build_js();
            build_navigation_bar();
        ?>

        <div class="jumbotron card card-image mb-0" style="background-image: url(/html/img/backdrops/admin.png)">
            <div class="text-white text-center">
                <h1 class="card-title h1-responsive font-weight-bold">Admin Panel</h1>
            </div>
        </div>

        <div class="news text-center pt-2 pb-2 news-red">
            It should be noted that all actions here, whether administrator or moderator, are logged with a timestamp, IP, and user ID of who performed the action as part of our transparency initiative.
			Please don't admin abuse, and set a good role model.
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-5">
                    <div class="card">
                        <div class="card-header purple accent-3 white-text">Administration</div>
                        
                        <div class="card-body-mx-4 py-4 px-4">
                            <div class="card gradient-card">
                                <a href="/admin/adminer">
                                    <div class="text-white mask purple-gradient-rgba">
                                        <div class="first-content align-self-center p-3">
                                            <span class="card-title font-weight-bold h4">Adminer</span>
                                            <p class="mb-0 h6 font-weight-light">Database management in a single PHP file</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <br>
                            <div class="card gradient-card">
                                <a href="/admin/award">
                                    <div class="text-white mask purple-gradient-rgba">
                                        <div class="first-content align-self-center p-3">
                                            <span class="card-title font-weight-bold h4">Award user</span>
                                            <p class="mb-0 h6 font-weight-light">Award user cash, badges, hats, gears, anything!</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <br>
                            <div class="card gradient-card">
                                <a href="/admin/staff-management">
                                    <div class="text-white mask purple-gradient-rgba">
                                        <div class="first-content align-self-center p-3">
                                            <span class="card-title font-weight-bold h4">Staff management</span>
                                            <p class="mb-0 h6 font-weight-light">Hire or retire staff or new users</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-7 mb-4">
                    <div class="card">
                        <div class="card-header purple accent-3 white-text">Moderation</div>
                        
                        <div class="card-body-mx-4 py-4 px-4">
                            <div class="card gradient-card">
                                <a href="/admin/moderate-user">
                                    <div class="text-white mask purple-gradient-rgba">
                                        <div class="first-content align-self-center p-3">
                                            <span class="card-title font-weight-bold h4">Moderate user</span>
                                            <p class="mb-0 h6 font-weight-light">Ban, warn, or purge any user below your rank</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <br>

                            <?php
                                if (ENVIRONMENT["PROJECT"]["INVITE_ONLY"]):
                            ?>
                            <div class="card gradient-card">
                                <a href="/admin/invite">
                                    <div class="text-white mask purple-gradient-rgba">
                                        <div class="first-content align-self-center p-3">
                                            <span class="card-title font-weight-bold h4">Generate invite key</span>
                                            <p class="mb-0 h6 font-weight-light">Generate an invite key for new users to sign up with</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <br>
                            <?php
                                endif;
                            ?>
                            
                            <div class="card gradient-card">
                                <a href="/admin/reports">
                                    <div class="text-white mask purple-gradient-rgba">
                                        <div class="first-content align-self-center p-3">
                                            <span class="card-title font-weight-bold h4">View reports</span>
                                            <p class="mb-0 h6 font-weight-light">See all reports submitted by the Report Abuse function, past or present, submitted by the glorious community of <?php echo(BASE_NAME); ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <br>
                            <div class="card gradient-card">
                                <a href="/admin/reports">
                                    <div class="text-white mask purple-gradient-rgba">
                                        <div class="first-content align-self-center p-3">
                                            <span class="card-title font-weight-bold h4">Asset moderation</span>
                                            <p class="mb-0 h6 font-weight-light">Moderate assets such as games, thumbnails, images, decals, audios, hats, shirts, pants, all types of assets!</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            build_footer();
        ?>
    </body>
</html>