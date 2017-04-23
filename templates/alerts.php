<?php
/**
 * Created by PhpStorm.
 * User: Lennart
 * Date: 19-04-17
 * Time: 15:28
 */

if (!empty($alerts)) {
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php foreach ($alerts as $alert) { ?>
                    <div class="alert alert-<?= $alert['type']; ?>"><?= $alert['description']; ?></div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php
}
?>