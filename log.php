<?php

include_once("lib.php");

if (isset($_POST['clear-log']) && $_POST['clear-log'] != "") {

    $result = $sql->query("DELETE FROM `log` WHERE `lock_id` = {$user->getLockId()}");

    if ($result) {
        $alerts[] = ['type' => "info", 'description' => "The log is cleared successfully"];
    } else {
        $alerts[] = ['type' => "danger", 'description' => "Something went wrong, try again later"];
    }
}

$logs = $sql->select("SELECT
                            log.date, log.time, log.access, log.code, users.username
                        FROM
                            log
                        LEFT JOIN
                            users
                        ON
                            log.code = users.code
                        WHERE
                            log.lock_id = {$user->getLockId()}
                        ORDER BY
                            `date` DESC, `time` DESC
                        ");

if (empty($logs)) {
    $alerts[] = ['type' => "warning", 'description' => "No data found"];
}
?>

<?php include_once("templates/header.php"); ?>

<?php include_once("templates/alerts.php"); ?>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-4">
                    <a href="<?= $url_base . $url_folder; ?>login.php">
                        <span><i class="material-icons">keyboard_arrow_left</i>Go back</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($logs)) { ?>
                <table class="table">
                    <thead class="thead-default">
                    <tr>
                        <th>User</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Access</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($logs as $log) { ?>
                        <tr>
                            <td class="align-middle"><?= !empty($log['username']) ? $log['username'] : $log['code']; ?></td>
                            <td class="align-middle"><?= date('d-m-Y', strtotime($log['date'])); ?></td>
                            <td class="align-middle"><?= $log['time']; ?></td>
                            <td class="align-middle"><?= $log['access'] == 1 ? "<span class=\"badge badge-success ml-3\">Allowed</span>" : "<span class=\"badge badge-warning ml-3\">Denied</span>" ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>

            <div class="col">
                <form action="" method="post">
                <button type="submit" class="btn btn-danger" name="clear-log" value="true">Clear log</button>
                </form>
            </div>


        </div>
    </div>

<?php include_once("templates/footer.php"); ?>