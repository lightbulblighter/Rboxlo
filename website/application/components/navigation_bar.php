<nav class="navbar navbar-expand-lg navbar-dark purple accent-4">
    <a class="navbar-brand" href="/">
		<img src="/html/img/logos/2016/full.png" class="img-fluid" width="150">
    </a>
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto">
			<?php if (isset($_SESSION["user"])): ?>

            <li class="nav-item">
                <a class="nav-link" href="/my/dashboard"><i class="material-icons">dashboard</i><span>Dashboard</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/games/"><i class="material-icons">gamepad</i><span>Games</span></a>
            </li>

            <li class="nav-item">
				<a class="nav-link" href="/catalog/"><i class="material-icons">shopping_cart</i><span>Catalog</span></a>
            </li>

            <?php if ($_SESSION["user"]["permissions"]["admin"]["see_panel"]): ?>
            
            <li class="nav-item">
				<a class="nav-link" href="/admin/"><i class="material-icons">build</i><span>Admin</span></a>
            </li>
            
            <?php endif; ?>
            
            <?php else: ?>

            <li class="nav-item">
				<a class="nav-link" href="/"><i class="material-icons">home</i><span>Home</span></a>
            </li>

            <?php endif; ?>
            
            <?php
                // so m any if statements call me yanderedev
                if (!PROJECT["PRIVATE"]["LOCKDOWN"]):
            ?>

            <li class="nav-item">
				<a class="nav-link" href="/forums/"><i class="material-icons">forum</i><span>Forums</span></a>
            </li>
			
			<li class="nav-item">
				<a class="nav-link" href="/blog/"><i class="material-icons">message</i><span>Blog</span></a>
            </li>

            <?php
                endif;
            ?>
		</ul>

		<?php if (isset($_SESSION["user"])): ?>

		<ul class="navbar-nav ml-auto nav-flex-icons">
            <li class="nav-item mr-1" data-toggle="tooltip" data-placement="top" title="<?= PROJECT["CURRENCY"] ?>">
               <a href="/my/money" class="nav-link">
                    <i class="material-icons">attach_money</i>
                    
                    <?php
                        // get money
                        // Ryelow COOL
                        open_database_connection($sql);

                        $statement = $sql->prepare("SELECT `money` FROM `users` WHERE `id` = ?");
                        $statement->execute([$_SESSION["user"]["id"]]);
                        $money = $statement->fetch(PDO::FETCH_ASSOC)["money"];

                        close_database_connection($sql, $statement);
                    ?>

                    <span class="money-text"><?= $money ?></span>
                </a>
            </li>
            
			
            <li class="nav-item avatar dropdown">
				<a class="nav-link dropdown-toggle waves-effect waves-light user-text" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="user-headshot mr-1" style="background-image: url(<?= get_server_host() ?>/html/img/thumbnails/users/<?= $_SESSION["user"]["id"] ?>.png)">
                    <b><?= $_SESSION["user"]["username"] ?></b>
                </a>
				
                <div class="dropdown-menu dropdown-menu-right dropdown-dark" aria-labelledby="navbarDropdownMenuLink-5">
					<a class="dropdown-item waves-effect waves-light" href="/my/settings">
                        <i class="material-icons" style="font-size: 1rem">settings</i>
                        <span>Settings</span>
                    </a>
					
                    <a class="dropdown-item waves-effect waves-light" data-toggle="modal" data-target="#logoutModal">
                        <i class="material-icons" style="font-size: 1rem">exit_to_app</i>
                        <span>Logout</span>
                    </a>
				</div>
			</li>
        </ul>

        <?php else: ?>
        
        <ul class="navbar-nav ml-auto">
            <?php
                if (PROJECT["PRIVATE"]["IMPLICATION"]):
            ?>

			<li class="nav-item">
				<a class="nav-link" href="/register">
					<span>Sign Up</span>
				</a>
            </li>
            
            <?php
                endif;
            ?>
            
            <li class="nav-item">
				<a class="nav-link" href="/login">
					<span>Login</span>
				</a>
			</li>
		</ul>

        <?php endif; ?>
    </div>
</nav>

<?php if (isset($_SESSION["user"]) && !$_SESSION["user"]["email_verified"]): ?>

<div class="news text-center pt-2 pb-2 news-red">
    Hey <b><?= $_SESSION["user"]["username"] ?></b>, in order to access some features on <?= PROJECT["NAME"] ?>, you need to <a href="<?= get_server_host() ?>/my/verify" class="font-weight-bold" style="color: #41bbf4">verify your E-Mail address!</a>
</div>

<?php endif; ?>

<?php if (isset($_SESSION["user"])): ?>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			    <h5 class="modal-title" id="logoutModalLabel">Logout</h5>
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			        <span aria-hidden="true">&times;</span>
			    </button>
			</div>

			<div class="modal-body">
			    Are you sure you want to log out?
			</div>

			<div class="modal-footer">
			    <a role="button" class="btn btn-danger" href="/my/logout">Logout</a>
			</div>
		</div>
	</div>
</div>

<?php endif; ?>