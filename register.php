<?php

include_once("lib.php");

?>

<?php include_once("templates/header.php"); ?>

<?php include_once("templates/alerts.php"); ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card">
                    <h3 class="card-header">Register</h3>
                    <div class="card-block">
                        <form method="post">

                            <div class="form-group row">
                                <label for="inputUsername" class="col-sm-3 col-form-label">Username</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="inputUsername" name="username"
                                           placeholder="Username" pattern=".{4,25}"
                                           value="<?= isset($_POST['username']) ? $_POST['username'] : "" ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm">
                                    <input type="password" class="form-control" id="inputPassword" name="password"
                                           placeholder="Password"
                                           pattern=".{5,25}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputFirstname" class="col-sm-3 col-form-label">First name</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="inputFirstname" name="firstname"
                                           placeholder="First name"
                                           value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : "" ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputLastname" class="col-sm-3 col-form-label">Last name</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="inputLastname" name="lastname"
                                           placeholder="Last name"
                                           value="<?= isset($_POST['lastname']) ? $_POST['lastname'] : "" ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCode" class="col-sm-3 col-form-label">NFC code</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="inputCode" name="code"
                                           placeholder="Code">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputLock" class="col-sm-3 col-form-label">Lock id</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="inputLock" name="lock_id"
                                           placeholder="Lock id">
                                </div>
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-sm-auto">
                                    <button type="submit" name="submit-register" class="btn btn-primary">Sign up
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mt-4 text-center">
                    <span>Do you have an account already? Login <a
                                href="<?= $url_base . $url_folder; ?>login.php">here!</a></span>
                </div>
            </div>
        </div>
    </div>

<?php include_once("templates/footer.php"); ?>