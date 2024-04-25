<?php
require_once '../dbcon.php';

$id = $_POST['id'] ?? '';
$val = $_POST['val'] ?? '';
$password = $_POST['password'] ?? '';

if (isset($_POST['updatePass'])) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    if ($_SESSION['usertype'] === "superadmin" || $_SESSION['usertype'] === "admin") {
        $sql = "UPDATE
        `accounts`
    SET
        `password` = '$hashed_password'
    WHERE
        `user_id` = '" . $_SESSION['user_id'] . "'";
        echo affected($sql);
        exit();
    } else if ($_SESSION['usertype'] === "Coordinator") {
        $sql = "UPDATE
        `coordinator`
    SET
        `password` = '$hashed_password'
    WHERE
        `coordinator_id` = '" . $_SESSION['user_id'] . "'";
        echo affected($sql);
        exit();
    }
}

if (isset($_POST['submitChanges'])) {
    if ($_SESSION['usertype'] === "superadmin" || $_SESSION['usertype'] === "admin") {
        $sql = "UPDATE
        `accounts`
    SET
        `$id` = '$val'
    WHERE
        `user_id` = '" . $_SESSION['user_id'] . "'";
        echo affected($sql);
        exit();
    } else if ($_SESSION['usertype'] === "Coordinator") {
    }
}

if (isset($_POST['setProfile'])) {
    $d = '';
    if ($_SESSION['usertype'] === "superadmin" || $_SESSION['usertype'] === "admin") {
        $sql = "SELECT * FROM `accounts` WHERE `user_id` = '" . $_SESSION['user_id'] . "'";
        $res = execquery($sql);
        $rr = mysqli_fetch_array($res);
        $d = '
            <div class="row g-3 mt-3">
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="user_id" class="form-label">User ID</label>
                            <h6>' . $rr[0] . '</h6>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <h6>' . $rr[1] . '</h6>
                        </div>
                        <div class="col">
                            <button class="btn btn-warning btn-sm" onclick="editData(this)"><i class="bi bi-pencil-square"></i></button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="full_name" class="form-label">Full Name</label>
                            <h6>' . $rr[2] . '</h6>
                        </div>
                        <div class="col">
                            <button class="btn btn-warning btn-sm" onclick="editData(this)"><i class="bi bi-pencil-square"></i></button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="username" class="form-label">Username</label>
                            <h6>' . $rr[3] . '</h6>
                        </div>
                        <div class="col">
                            <button class="btn btn-warning btn-sm" onclick="editData(this)"><i class="bi bi-pencil-square"></i></button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="user_type" class="form-label">User Type</label>
                            <h6>' . $rr[5] . '</h6>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="password" class="form-label">Update Password</label>
                        </div>
                        <div class="col">
                            <button class="btn btn-warning btn-sm" onclick="editPass(this)"><i class="bi bi-pencil-square"></i></button>
                        </div>
                    </div>
                </div>
            </div>';
    } else if ($_SESSION['usertype'] === "Coordinator") {
        $sql = "SELECT * FROM `coordinator` WHERE `coordinator_id` = '" . $_SESSION['user_id'] . "'";
        $res = execquery($sql);
        $rr = mysqli_fetch_array($res);
        $d = '
        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="emp_id" class="form-label">Employee ID:</label><br>
                    <span id="emp_id" class="form-text text-dark">' . $rr[0] . '</span>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name:</label><br>
                    <span id="first_name" class="form-text text-dark">' . $rr[1] . '</span>
                </div>
                <div class="mb-3">
                    <label for="middle_name" class="form-label">Middle Name:</label><br>
                    <span id="middle_name" class="form-text text-dark">' . $rr[2] . '</span>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name:</label><br>
                    <span id="last_name" class="form-text text-dark">' . $rr[3] . '</span>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label><br>
                    <span id="email" class="form-text text-dark">' . $rr[4] . '</span>
                </div>
                <div class="mb-3">
                    <label for="user_type" class="form-label">User type:</label><br>
                    <span id="user_type" class="form-text text-dark">' . $rr[12] . '</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender:</label><br>
                    <span id="gender" class="form-text text-dark">' . $rr[5] . '</span>
                </div>
                <div class="mb-3">
                    <label for="contact" class="form-label">Contact:</label><br>
                    <span id="contact" class="form-text text-dark">' . $rr[6] . '</span>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label><br>
                    <span id="address" class="form-text text-dark">' . $rr[7] . '</span>
                </div>
                <div class="mb-3">
                    <label for="birthdate" class="form-label">Birthdate:</label><br>
                    <span id="birthdate" class="form-text text-dark">' . $rr[8] . '</span>
                </div>
                <div class="mb-3">
                    <label for="user_type" class="form-label">Username:</label><br>
                    <span id="user_type" class="form-text text-dark">' . $rr[10] . '</span>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="password" class="form-label">Update Password</label>
                    </div>
                    <div class="col">
                        <button class="btn btn-warning btn-sm" onclick="editPass(this)"><i class="bi bi-pencil-square"></i></button>
                    </div>
                </div>
            </div>
        </div>
        ';
    }
    echo $d;
}
