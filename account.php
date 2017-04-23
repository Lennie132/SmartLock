<?php

include_once("lib.php");


if (isset($_POST['submit-account'])) {
    $hash = password_hash($sql->quote($_POST['password']), PASSWORD_DEFAULT);
    $code = $sql->quote($_POST['code']);
    $username = $sql->quote($_POST['username']);
    $firstname = $sql->quote($_POST['firstname']);
    $lastname = $sql->quote($_POST['lastname']);
    $lock_id = $sql->quote($_POST['lock_id']);

    $result = $sql->query("UPDATE
                                  `users`
                               SET 
                                  `username` = '{$username}',
                                  `firstname` = '{$firstname}',
                                  `lastname` = '{$lastname}',
                                  `password` = '{$hash}',
                                  `code` = '{$code}',
                                  `lock_id` = {$lock_id}
                               WHERE
                                  `id` = {$user->getId()}
                               ");

    if ($result) {
        $user->login($user->getId());

        $alerts[] = ['type' => "success", 'description' => "Changes are successfully saved"];

    } else {
        $alerts[] = ['type' => "warning", 'description' => "Something went wrong, try again"];
    }
}


?>


<?php include_once("templates/header.php"); ?>

<?php include_once("templates/alerts.php"); ?>

    <div class="container">
        <div class="row">
            <div class="col-md">
                <form method="post">

                    <div class="form-group row">
                        <label for="inputUsername" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="inputUsername" name="username"
                                   placeholder="Username"
                                   value="<?= $user->getUsername(); ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm">
                            <input type="password" class="form-control" id="inputPassword" name="password"
                                   placeholder="Password" value="">
                            <div class="form-text text-muted">Enter password only if you forgotten</div>
                        </div>


                    </div>
                    <div class="form-group row">
                        <label for="inputFirstname" class="col-sm-3 col-form-label">First name</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="inputFirstname" name="firstname"
                                   placeholder="First name"
                                   value="<?= $user->getFirstname(); ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputLastname" class="col-sm-3 col-form-label">Last name</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="inputLastname" name="lastname"
                                   placeholder="Last name"
                                   value="<?= $user->getLastname(); ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputCode" class="col-sm-3 col-form-label">NFC code</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="inputCode" name="code"
                                   placeholder="Code" value="<?= $user->getCode(); ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputLock" class="col-sm-3 col-form-label">Lock id</label>
                        <div class="col-sm">
                            <input type="text" class="form-control" id="inputLock" name="lock_id"
                                   placeholder="Lock id" value="<?= $user->getLockId(); ?>" required>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-sm-auto">
                            <button type="submit" name="submit-account" class="btn btn-primary">Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php include_once("templates/footer.php"); ?>