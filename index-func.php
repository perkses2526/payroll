<?php
require_once 'dbcon.php';

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (isset($_POST['signin'])) {
    $sql = "SELECT * FROM `coordinator` WHERE `email` = '$username' or `username` = '$username'";
    $res = execquery($sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_array($res);
        $dbpass = $row['password'];
        $emails = $row['email'];
        if (password_verify($password, $dbpass)) {
            setSession($emails);
            echo "1";
        } else {
            echo "2";
        }
    } else {
        echo "0";
    }
}

function setSession($email)
{
    $sql = "SELECT c.coordinator_id, c.email, concat(c.last_name,', ', c.first_name), c.username, c.user_type FROM `coordinator` c WHERE c.`email` = '$email'";
    $res = execquery($sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_array($res);
        $_SESSION['user_id'] = $row[0];
        $_SESSION['email'] = $row[1];
        $_SESSION['full_name'] = $row[2];
        $_SESSION['username'] = $row[3];
        $_SESSION['usertype'] = $row[4];
        $_SESSION['active'] = 1;
    }
}
