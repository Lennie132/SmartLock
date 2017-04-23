<?php
/**
 * Created by PhpStorm.
 * User: Lennart
 * Date: 12-04-17
 * Time: 12:00
 */

require("settings.php");

$access = false;

if (isset($_GET['lock_id']) && $_GET['rfid_code']) {

    $stmt = $connection->prepare("SELECT `id` FROM `users` WHERE `lock_id` = ?");
    $stmt->bind_param("s", $_GET['lock_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $owner = mysqli_fetch_assoc($result);

    $stmt = $connection->prepare("SELECT
                                      contacts.id, `has_access`
                                  FROM
                                      `contacts`
                                  LEFT JOIN
                                      `users`
                                  ON
                                     users.id = contacts.contact_user_id
                                  WHERE
                                      `user_id` = ?
                                  AND
                                      `code` = ?
                                  ");

    $stmt->bind_param("ss", $owner['id'], $_GET['rfid_code']);
    $stmt->execute();
    $result = $stmt->get_result();

    $contact = mysqli_fetch_assoc($result);

    if (!empty($contact)) {

        if ($contact['has_access'] == 1) {
            $access = true;
        } else {

            $current_day = date('w');
            $current_time = date('H:i:s');

            $stmt = $connection->prepare("SELECT
                                      *
                                  FROM
                                      `access`
                                  WHERE
                                      `contact_id` = ?
                                  AND
                                      `day` = ?
                                  AND
                                      `time_begin` < ?
                                  AND
                                      `time_end` > ?
                                  AND
                                      `enabled` = 1
                                  ");

            $stmt->bind_param("ssss", $contact['id'], $current_day, $current_time, $current_time);
            $stmt->execute();
            $result = $stmt->get_result();

            $periods = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $periods[] = $row;
            }

            if (!empty($periods)) {
                $access = true;
            }
        }
    }

    if ($access) {
        echo "access:true";
        $access = 1;
    } else {
        echo "access:false";
        $access = 0;
    }

        $id = $_GET['lock_id'];
        $rfid = $_GET['rfid_code'];

    $stmt = $connection->prepare("INSERT INTO
                                      `log`
                                      (`lock_id`,
                                      date,
                                      `time`,
                                      `code`,
                                      `access`)
                                  VALUES (
                                      ?,
                                      CURDATE(),
                                      CURTIME(),
                                      ?,
                                      ?)
                                  ");

    $stmt->bind_param("sss", $id, $rfid, $access);
    $stmt->execute();


}
mysqli_close($connection);