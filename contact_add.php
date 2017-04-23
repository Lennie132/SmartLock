<?php

include_once("lib.php");

if (isset($_POST['add-contact']) && $_POST['add-contact'] != '') {
    $id = $sql->quote($_POST['add-contact']);
    $result = $sql->query("INSERT INTO
                              `contacts`
                              (`user_id`, `contact_user_id`)
                          VALUES
                              ({$user->getId()}, {$id}) ");

    if ($result) {
        $alerts[] = ['type' => "success", 'description' => "The contact is added successfully"];
    } else {
        $alerts[] = ['type' => "danger", 'description' => "Something went wrong, try again later"];
    }
}

if (isset($_GET['search']) && $_GET['search'] == '') {
    $alerts[] = ['type' => "warning", 'description' => "Insert a search term to find users"];
}
if (isset($_GET['search']) && $_GET['search'] != '') {
    $search = $sql->quote($_GET['search']);

    $results = $sql->select("SELECT
                                  `id`,
                                  `username`
                              FROM
                                  `users`
                              WHERE 
                                  `username` LIKE '%{$search}%'
                              AND 
                                  `id` != {$user->getId()}
                              AND `id` NOT IN (SELECT
                                              `contact_user_id`
                                          FROM
                                              `contacts`
                                          WHERE 
                                              `user_id` = {$user->getId()})
                               ");

    if (empty($results)) {
        $alerts[] = ['type' => "warning", 'description' => "No users found by this search term"];
    }
}

?>


<?php include_once("templates/header.php"); ?>

<?php include_once("templates/alerts.php"); ?>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-4">
                    <a
                            href="<?= $url_base . $url_folder; ?>login.php"><span><i class="material-icons">keyboard_arrow_left</i>Go back</span></a>
                </div>
            </div>
        </div>

        <div class="row search justify-content-center">

            <div class="col-md-4">
                <form class="form-inline mb-4" method="get">
                    <label class="sr-only" for="inputSearch">Username</label>
                    <input type="text" class="form-control mr-sm-2" id="inputSearch" name="search"
                           placeholder="username"
                           value="<?= isset($_GET['search']) && $_GET['search'] != "" ? $_GET['search'] : "" ?>">


                    <button type="submit" name="" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>

        <div class="row contacts">


            <?php if (!empty($results)) { ?>
                <table class="table">
                    <thead class="thead-default">
                    <tr>
                        <th>Image</th>
                        <th>Username</th>
                        <th>Add</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($results as $result) { ?>

                        <tr>
                            <td><img class="rounded contact__image" style="height 50px"
                                     src="img/users/<?= $result['id']; ?>.jpg" alt=""></td>
                            <td class="align-middle"><?= $result['username']; ?></td>
                            <td class="align-middle">
                                <form action="" method="post">
                                    <button class="btn btn-success" type="submit" name="add-contact"
                                            value="<?= $result['id']; ?>">Add Contact
                                    </button>
                                </form>
                            </td>
                        </tr>

                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>

<?php include_once("templates/footer.php"); ?>