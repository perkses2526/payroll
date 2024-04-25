<?php
require_once '../dbcon.php';

$company_name = $_POST['company_name'] ?? '';
$address = $_POST['address'] ?? '';
$tel_no = $_POST['tel_no'] ?? '';
$email_add = $_POST['email_add'] ?? '';

if (isset($_POST['addcompany'])) {
    $sql = "INSERT INTO `companies`(`name`,`address`, `tel_no`, `email_add`, `created_by`) VALUES ('$company_name', '$address', '$tel_no', '$email_add', '" . $_SESSION['account_id'] . "')";
    $res = affected($sql);
    if ($res > 0) {
        echo "success";
    } else {
        echo "error";
    }
}

if (isset($_POST['setcompanyform'])) {
    echo '
    <div class="container">
        <h5 class="text-center">Company Information Form</h5><hr>
        <div class="mb-3">
            <label for="company_name" class="form-label">Company Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="company_name" name="company_name" required>
        </div>
        <div class="mb-3">
            <label for="email_add" class="form-label">Email Address<span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email_add" name="email_add" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="tel_no" class="form-label">Telephone Number</label>
            <input type="tel" class="form-control" id="tel_no" name="tel_no">
        </div>
       
    </div>';
}
