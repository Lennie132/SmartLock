<?php
include_once("lib.php");

?>

<?php include_once("templates/header.php"); ?>

<?php include_once("templates/alerts.php"); ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <h3 class="card-header">Login</h3>
                    <div class="card-block">
                        <!--                    <h4 class="card-title">Special title treatment</h4>-->
                        <form method="post">

                            <div class="form-group row">
                                <label for="inputUsername" class="col-sm-3 col-form-label">Username</label>
                                <div class="col-sm">
                                    <input type="text" class="form-control" id="inputUsername" name="username"
                                           placeholder="Username"
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
                            <div class="form-group row justify-content-center">
                                <div class="col-sm-auto">
                                    <button type="submit" name="submit-login" class="btn btn-primary">Sign in</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mt-4 text-center">
                    <span>No account? Create a new account <a
                                href="<?= $url_base . $url_folder; ?>register.php">here!</a></span>
                </div>
            </div>
        </div>
    </div>

<?php include_once("templates/footer.php"); ?>