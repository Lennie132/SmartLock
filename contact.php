<?php

include_once("lib.php");

if (!isset($_GET['id']) || $_GET['id'] == "") {
    header("Location: {$url_base}{$url_folder}index.php");
    die();
} else {
    $contact_id = $sql->quote($_GET['id']);
}

if (isset($_POST['submit-period'])) {
    $day = $sql->quote($_POST['day']);
    $time_begin = $sql->quote($_POST['time-begin']);
    $time_end = $sql->quote($_POST['time-end']);

    $result = $sql->query("INSERT INTO
                              `access`
                              (`contact_id`,
                              `day`,
                              `time_begin`,
                              `time_end`,
                              `enabled`)
                          VALUES
                              ({$contact_id},
                              {$day},
                              '{$time_begin}',
                              '{$time_end}',
                              1)
                          ");
    if ($result) {
        $alerts[] = ['type' => "success", 'description' => "The access period is added successfully"];
    } else {
        $alerts[] = ['type' => "danger", 'description' => "Something went wrong, try again later"];
    }
}

if (isset($_POST['edit-period'])) {
    $edit_id = $sql->quote($_POST['edit-id']);
    $day = $sql->quote($_POST['day']);
    $time_begin = $sql->quote($_POST['time-begin']);
    $time_end = $sql->quote($_POST['time-end']);

    $result = $sql->query("UPDATE
                               `access`
                          SET 
                              `day` = {$day},
                              `time_begin` = '{$time_begin}',
                              `time_end` = '{$time_end}'
                          WHERE 
                              `id` = {$edit_id}
                          ");

    if ($result) {
        $alerts[] = ['type' => "success", 'description' => "The access period is edited successfully"];
    } else {
        $alerts[] = ['type' => "danger", 'description' => "Something went wrong, try again later"];
    }
}

if (isset($_POST['enable-id']) && $_POST['enable-id'] != "") {

    if (isset($_POST['enable-period']) && $_POST['enable-period'] == 'true') {
        $enabled = 1;
        $text = "on";
    } else {
        $enabled = 0;
        $text = "off";
    }
    $edit_id = $sql->quote($_POST['enable-id']);

    $result = $sql->query("UPDATE
                               `access`
                          SET 
                              `enabled` = {$enabled}
                          WHERE 
                              `id` = {$edit_id}
                          ");

    if ($result) {
        $alerts[] = ['type' => "success", 'description' => "The access period is turned {$text}"];
    } else {
        $alerts[] = ['type' => "danger", 'description' => "Something went wrong, try again"];
    }
}

if (isset($_POST['enable-contact']) && $_POST['enable-contact'] != "") {

    if (isset($_POST['enable-always']) && $_POST['enable-always'] == 'true') {
        $enabled = 1;
        $text = "on";
    } else {
        $enabled = 0;
        $text = "off";
    }

    $result = $sql->query("UPDATE
                               `contacts`
                          SET 
                              `has_access` = {$enabled}
                          WHERE 
                              `id` = {$contact_id}
                          ");

    if ($result) {
        $alerts[] = ['type' => "success", 'description' => "Always access is turned {$text}"];
    } else {
        $alerts[] = ['type' => "danger", 'description' => "Something went wrong, try again"];
    }
}

if (isset($_POST['delete-period']) && $_POST['delete-period'] != "") {
    $delete_id = $sql->quote($_POST['delete-period']);

    $result = $sql->query("DELETE FROM `access` WHERE `id` = {$delete_id}");

    if ($result) {
        $alerts[] = ['type' => "info", 'description' => "The access period is deleted successfully"];
    } else {
        $alerts[] = ['type' => "danger", 'description' => "Something went wrong, try again later"];
    }
}

$contact = $sql->select("SELECT
                            contacts.id,
                            contact_user_id,
                            has_access,
                            username,
                            firstname,
                            lastname
                        FROM
                            contacts
                        INNER JOIN
                            users
                        ON
                            contacts.contact_user_id = users.id
                        WHERE
                            contacts.id = {$contact_id}")[0];

$periods = $sql->select("SELECT
                  *
              FROM
                  `access`
              WHERE 
                  `contact_id` = {$contact_id}
              ORDER BY
                  day
              ");

if (empty($periods)) {
    $alerts[] = ['type' => "warning", 'description' => "No access periods found for {$contact['username']}"];
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
            <div class="col-md-12">
                <h2 class="mb-4">Manage access for <?= $contact['username']; ?></h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form action="" class="mb-4 inline" method="post">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="hidden" name="enable-contact" value="<?= $contact_id; ?>">
                            <input type="checkbox" value="true" <?= $contact['has_access'] == 1 ? "checked" : "" ?>
                                   name="enable-always" onchange="this.form.submit();"> Always access to your lock
                        </label>
                        <div class="form-text">
                            <small>This will overwrite all created periods. <?= $contact['username']; ?> will always
                                have access.
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row contacts">
            <div class="col-md-12">
                <?php if (!empty($periods)) { ?>
                <table class="table">
                    <thead class="thead-default">
                    <tr>
                        <th>Enable</th>
                        <th>Day</th>
                        <th>Begin time</th>
                        <th>End time</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($periods as $period) { ?>
                        <tr>
                            <td class="align-middle">
                                <form action="" class="mb-0" method="post">
                                    <input type="hidden" name="enable-id" value="<?= $period['id']; ?>">
                                    <input type="checkbox" value="true" <?= $period['enabled'] == 1 ? "checked" : "" ?>
                                           name="enable-period" onchange="this.form.submit();">
                                </form>
                            </td>
                            <td class="align-middle"><?= date('l', strtotime("Sunday + {$period['day']} Days")); ?></td>
                            <td class="align-middle"><?= $period['time_begin']; ?></td>
                            <td class="align-middle"><?= $period['time_end']; ?></td>
                            <td class="align-middle">
                                <button class="btn btn-info" data-toggle="modal"
                                        data-target=".modal-<?= $period['id']; ?>"><i class="material-icons">edit</i>
                                </button>
                            </td>
                            <td class="align-middle">
                                <form action="" class="mb-0" method="post">
                                    <button class="btn btn-danger" type="submit" name="delete-period"
                                            value="<?= $period['id']; ?>"><i class="material-icons">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade modal-<?= $period['id']; ?>" tabindex="-1"
                             role="dialog" aria-labelledby="myLargeModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="" method="post">
                                        <input type="hidden" name="edit-id" value="<?= $period['id']; ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="exampleModalLongTitle">Edit Access Period</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="day">Example select</label>
                                                <select class="form-control" id="day" name="day">
                                                    <?php
                                                    for ($x = 0; $x <= 6; $x++) {
                                                        if ($x != 6) {
                                                            $y = $x + 1;
                                                        } else {
                                                            $y = 0;
                                                        }
                                                        ?>
                                                        <option value="<?= $y ?>" <?= $period['day'] == $y ? "selected" : "" ?> >
                                                            <?= date('l', strtotime("Sunday + {$y} Days")); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="time-begin">Time</label>
                                                <input class="form-control" type="time"
                                                       value="<?= $period['time_begin']; ?>" id="time-begin"
                                                       name="time-begin">
                                            </div>
                                            <div class="form-group">
                                                <label for="time-end">Time</label>
                                                <input class="form-control" type="time"
                                                       value="<?= $period['time_end']; ?>" id="time-end"
                                                       name="time-end">
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary" name="edit-period">Save
                                                Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>

            <div class="col">
                <button class="btn btn-primary" data-toggle="modal"
                        data-target=".modal-add">Add period
                </button>
            </div>

            <div class="modal fade modal-add" tabindex="-1"
                 role="dialog" aria-labelledby="myLargeModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title"
                                    id="exampleModalLongTitle">New Access Period</h5>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="day">Example select</label>
                                    <select class="form-control" id="day" name="day">
                                        <?php
                                        for ($x = 0; $x <= 6; $x++) {
                                            if ($x != 6) {
                                                $y = $x + 1;
                                            } else {
                                                $y = 0;
                                            }
                                            ?>
                                            <option value="<?= $y ?>">
                                                <?= date('l', strtotime("Sunday + {$y} Days")); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="time-begin">Time</label>
                                    <input class="form-control" type="time" value="13:00:00" id="time-begin"
                                           name="time-begin">
                                </div>
                                <div class="form-group">
                                    <label for="time-end">Time</label>
                                    <input class="form-control" type="time" value="15:00:00" id="time-end"
                                           name="time-end">
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-primary" name="submit-period">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>

<?php include_once("templates/footer.php"); ?>