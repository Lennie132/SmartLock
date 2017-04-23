<?php
/**
 * Created by PhpStorm.
 * User: Lennart
 * Date: 19-04-17
 * Time: 11:31
 */


if (is_file("img/users/{$user->getId()}.jpg")) {
    $url = "img/users/{$user->getId()}.jpg";
} else {
    $url = "img/placeholder.png";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SmartLock</title>
    <link rel="stylesheet" href="style/main.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
          integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
</head>
<body>

<header>
    <nav class="navbar navbar-toggleable-md navbar-inverse bg-primary">
        <div class="container">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand" href="#">
                <img src="img/logo.png" style="height:30px;width:auto;" class="d-inline-block align-top"
                     alt="SmartLock logo">
                SmartLock
            </a>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?= $url_file == "index.php" ? "active" : "" ?>">
                        <a class="nav-link" href="<?= $url_base . $url_folder; ?>index.php">Contacts<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item <?= $url_file == "log.php" ? "active" : "" ?>">
                        <a class="nav-link" href="<?= $url_base . $url_folder; ?>log.php">Log</a>
                    </li>
                </ul>

                <?php if ($user->check_login()) { ?>
                    <div class="nav-text">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown <?= $url_file == "account.php" ? "active" : "" ?>">
                                <a class="nav-link dropdown-toggle" href="http://example.com"
                                   id="navbarDropdownMenuLink"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="<?= $url; ?>" class="" style="height:20px;width:auto;" alt="User account">
                                    Hello, <?= $user->check_login() ? $user->getUsername() : "" ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item <?= $url_file == "account.php" ? "active" : "" ?>"
                                       href="<?= $url_base . $url_folder; ?>account.php">Information</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="?logout=true">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </nav>
</header>