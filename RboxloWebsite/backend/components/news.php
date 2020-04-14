<?php
	include_once(BASE_PATH ."/backend/database.php");

    $statement = $GLOBALS["sql"]->prepare("SELECT * FROM `news` ORDER BY `id`");
    $statement->execute();

    foreach ($statement as $result):
?>

<div class="alert text-center" id="alert" style="background-color: #<?php echo($result["color"]); ?>">
    <?php echo($result["message"]); // I KNOW THIS HAS XSS, BUT IT'S INTENTIONAL FOR STYLING (LIKE BOLDING). I'M NOT IMPLEMENTING MARKDOWN HERE. ?>
</div>

<?php
    endforeach;
?>