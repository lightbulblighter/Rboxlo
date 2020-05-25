<?php
    // this does nothing for now
    // todo: switch this to some really annoying html thingy
    /*

    open_database_connection($sql);

    $statement = $sql->prepare("SELECT * FROM `news` ORDER BY `id`");
    $statement->execute();

    foreach ($statement as $result):
?>

<div class="news text-center pt-2 pb-2" id="alert" style="background-color: #<?php echo($result["color"]); ?>">
    <?php echo($result["message"]); /* I KNOW THIS HAS XSS, BUT IT'S INTENTIONAL FOR STYLING (LIKE BOLDING). I'M NOT IMPLEMENTING MARKDOWN HERE.  ?>
</div>

<?php
    endforeach;
?>
*/
?>