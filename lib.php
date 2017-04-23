<?php
/**
 * Created by PhpStorm.
 * User: Lennart
 * Date: 19-04-17
 * Time: 10:53
 */

include_once("classes/Database.php");
include_once("classes/User.php");

session_start();


$url_base = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$url_folder = dirname($_SERVER['SCRIPT_NAME']) . "/";
$url_file = pathinfo($url_base . $_SERVER['SCRIPT_FILENAME'])['basename'];
$alerts = [];

$user = User::get_instance();
$sql = new Database();

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    $user->logout();
    header("Location: {$url_base}{$url_folder}index.php");
    die();
}

if (!$user->check_login() && $url_file != 'login.php' && $url_file != 'register.php') {
    header("Location: {$url_base}{$url_folder}login.php");
    die();
}

if ($user->check_login() && ($url_file == 'login.php' || $url_file == 'register.php')) {
    header("Location: {$url_base}{$url_folder}index.php");
    die();
}

if (isset($_POST['submit-login'])) {
    $username = $sql->quote($_POST['username']);
    $password = $sql->quote($_POST['password']);
    $account = $sql->select("SELECT `id`, `password` FROM `users` WHERE `username`='{$username}'")[0];
    if (password_verify($password, $account['password'])) {
        $id = $account['id'];
        $account = $sql->select("SELECT `id`, `username`, `code` FROM `users` WHERE `id`={$id}")[0];
        $user->login($account['id']);
        header("Location: {$url_base}{$url_folder}index.php");
        die();
    } else {
        $alerts[] = ['type' => "danger", 'description' => "Username and password combination is incorrect"];
    }
}

if (isset($_POST['submit-register'])) {
    $username = $sql->quote($_POST['username']);

    $account = $sql->select("SELECT `id`, `password` FROM `users` WHERE `username`='{$username}'");
    if (!isset($account[0]['id'])) {
        $hash = password_hash($sql->quote($_POST['password']), PASSWORD_DEFAULT);
        $code = $sql->quote($_POST['code']);
        $firstname = $sql->quote($_POST['firstname']);
        $lastname = $sql->quote($_POST['lastname']);
        $lock_id = $sql->quote($_POST['lock_id']);

        $result = $sql->query("INSERT
                                  INTO `users` (
                                    `username`,
                                    `firstname`,
                                    `lastname`,
                                    `password`,
                                    `code`,
                                    `lock_id`
                                    )
                                  VALUES (
                                  '{$username}',
                                  '{$firstname}',
                                  '{$lastname}',
                                  '{$hash}',
                                  '{$code}',
                                  '{$lock_id}'
                                  )");

        if ($result) {
            $id = $sql->get_last_id();

            $user->login($id);
            header("Location: {$url_base}{$url_folder}index.php");

        } else {
            $alerts[] = ['type' => "warning", 'description' => "Something went wrong, try again"];
        }
    } else {
        $alerts[] = ['type' => "warning", 'description' => "Account already exists"];
    }
}
