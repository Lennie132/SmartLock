<?php

include_once("lib.php");

if (isset($_GET['delete']) && $_GET['delete'] != '') {
    $delete_id = $_GET['delete'];
    $result = $sql->query("DELETE FROM `contacts` WHERE `id`= {$delete_id}");

    if ($result) {
        $alerts[] = ['type' => "success", 'description' => "The contact is deleted successfully"];
    } else {
        $alerts[] = ['type' => "danger", 'description' => "Something went wrong, try again later"];
    }
}

$contacts = $sql->select("SELECT
                              contacts.id,
                              username,
                              firstname,
                              lastname,
                              contact_user_id,
                              has_access
                          FROM
                              contacts
                          INNER JOIN users
                              ON contacts.contact_user_id = users.id
                          WHERE `user_id`={$user->getId()}");

if (empty($contacts)) {
    $alerts[] = ['type' => "info", 'description' => "You do not have any contacts"];
}
?>


<?php include_once("templates/header.php"); ?>

<?php include_once("templates/alerts.php"); ?>

    <div class="container">
        <div class="row contacts">
            <?php
            if (!empty($contacts)) {
                foreach ($contacts as $contact) {
                    $contact_id = $contact['id'];

                    if (is_file("img/users/$contact[contact_user_id].jpg")) {
                        $url = "img/users/$contact[contact_user_id].jpg";
                    } else {
                        $url = "img/placeholder.png";
                    }

                    $periods = $sql->select("SELECT
                                                  *
                                              FROM
                                                  `access`
                                              WHERE 
                                                  `contact_id` = {$contact_id}
                                              AND
                                                  `enabled` = 1
                                              ");
                    ?>

                    <div class="col-md-4">
                        <div class="card contact mb-5">
                            <div class="card-header text-center text-muted">
                                <img class="rounded contact__image"
                                     src="<?= $url; ?>" alt="">
                                <span><?= $contact['username']; ?></span>
                            </div>
                            <div class="card-block">
                                <h4 class="card-title"><?= $contact['firstname']; ?></h4>
                                <h6 class="card-subtitle mb-2 text-muted"><?= $contact['lastname']; ?></h6>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    Always access:
                                    <?= $contact['has_access'] == 1 ? "<span class=\"badge badge-success ml-3\">On</span>" : "<span class=\"badge badge-warning ml-3\">Off</span>" ?>
                                </li>
                                <li class="list-group-item">Periods enabled:
                                    <?= !empty($periods) ? "<span class=\"badge badge-success ml-3\">Yes</span>" : "<span class=\"badge badge-warning ml-3\">No</span>" ?>
                                </li>

                            </ul>
                            <div class="card-block">
                                <a class="btn btn-primary"
                                   href="<?= $url_base . $url_folder; ?>contact.php?id=<?= $contact['id']; ?>">Manage
                                    Access</a>

                                <a class="btn btn-danger" href="?delete=<?= $contact['id']; ?>">Delete</a>

                            </div>
                        </div>
                    </div>

                    <?php
                }
            }
            ?>
            <div class="col-md-4 mb-5">
                <a href="<?= $url_base; ?><?= $url_folder; ?>contact_add.php">
                    <button class="btn btn-primary contacts__add"><i class="material-icons">add</i> Add a contact
                    </button>
                </a>
            </div>

        </div>
    </div>

<?php include_once("templates/footer.php"); ?>