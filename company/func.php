<?php
require_once '../dbcon.php';

$cid = $_POST['cid'] ?? '';
$company_name = $_POST['company_name'] ?? '';
$address = $_POST['address'] ?? '';
$tel_no = $_POST['tel_no'] ?? '';
$email_add = $_POST['email_add'] ?? '';
$department = $_POST['department'] ?? '';
$did = $_POST['did'] ?? '';



if (isset($_POST['dept_tb'])) {
    $d = '
    <thead>
        <tr>
            <th>#</th>
            <th>Department</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    ';

    $sql = "SELECT * FROM `departments` WHERE `company_id` = '$cid' and `name` like '%$search%'";
    $res = execquery($sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            $d .= '
        <tr>
            <td>' . $row[0] . '</td>
            <td>' . $row[1] . '</td>
            <td>
            <button class="btn btn-warning btn-sm" onclick="editdepartment(this);" did="' . $row[0] . '"><i class="bi bi-pencil-square"></i></button>
            ' .
                (sadmin() ? '<button class="btn btn-danger btn-sm" did="' . $row[0] . '" onclick="removedept(this);"><i class="bi bi-trash-fill"></i></button>' : '')
                .
                '
            </td>
        </tr>
        ';
        }
    } else {
        $d .= nores(3);
    }
    echo $d .= '</tbody>';
}

if (isset($_POST['deptpages'])) {
    $sql = "SELECT count(*) FROM `departments` WHERE `company_id` = '$cid'";
    pages($sql, $entries);
}

if (isset($_POST['updatedept'])) {
    $sql = "UPDATE `departments` SET `name` = '$department' WHERE `department_id` = '$did'";
    echo crud($sql);
}

if (isset($_POST['removedept'])) {
    $sql = "DELETE FROM `departments` WHERE `department_id` = '$did'";
    echo crud($sql);
}

if (isset($_POST['newdept'])) {
    $sql = "INSERT INTO `departments`(`name`, `company_id`,`created_by`) VALUES ('$department', '$cid', '" . $_SESSION['account_id'] . "')";
    echo crud($sql);
}

if (isset($_POST['managedepartment'])) {
    echo '
        <input type="hidden" name="cid" id="cid" value="' . $cid . '">
        <div class="row mb-2 mt-3">
            <div class="col">
            <select name="entries" id="entries_dept" class="form-control form-control-sm w-auto">
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
                <option value="500">500</option>
            </select>
            </div>
            <div class="col-6 text-end" id="manage_dept_div">
                <button class="btn btn-primary btn-sm" title="Add department to this company" onclick="setnewdept(this);"><i class="bi bi-building-add"></i></button>
            </div>
            <div class="col-3 text-end">
            <input type="text" name="search" id="search_dept" class="form-control form-control-sm" placeholder="Search...">
            </div>
            <div class="col-auto text-center">
            <div class="input-group btn-group-sm text-end">
                <button name="nextbtn" class="btn btn-sm btn-secondary prev">
                <i class="bi bi-arrow-left"></i></button>
                <select name="page" id="page_dept" class="form-control">
                </select>
                <button name="prevbtn" class="btn btn-sm btn-secondary next">
                <i class="bi bi-arrow-right"></i></button>
            </div>
            </div>
            <div class="text-right col-auto">
            <button class="btn btn-success btn-sm refresh-tbdp" title="Refresh table"><i class="bi bi-arrow-clockwise"></i></button>
            </div>
        </div>
        <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover text-center" id="dept_tb">
        </table>
    ';
}

if (isset($_POST['editcompany'])) {
    $sql = "SELECT * FROM `companies` c where `company_id` = '$cid'";
    $res = mysqli_fetch_array(execquery($sql));
    echo '
    <div class="container">
        <h5 class="text-center">Company Information Form</h5><hr>
        <div class="mb-3">
            <input type="hidden" value="' . $cid . '" name="cid" id="cid">
            <label for="company_name" class="form-label">Company Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="company_name" name="company_name" value="' . $res[1] . '" required>
        </div>
        <div class="mb-3">
            <label for="email_add" class="form-label">Email Address<span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email_add" name="email_add" value="' . $res[4] . '" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" value="' . $res[2] . '"></textarea>
        </div>
        <div class="mb-3">
            <label for="tel_no" class="form-label">Telephone Number</label>
            <input type="tel" class="form-control" id="tel_no" name="tel_no" value="' . $res[3] . '">
        </div>
    </div>';
}

if (isset($_POST['settb'])) {
    $d = '
    <thead>
        <tr>
            <th>#</th>
            <th>Company name</th>
            <th>Company address</th>
            <th>Company tel no</th>
            <th>Company email address</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
  ';
    $sql = "SELECT *, (SELECT count(*) FROM `departments` WHERE `company_id` = c.company_id) as dpt FROM `companies` c where concat(c.name, c.address, c.tel_no, c.email_add) like '%$search%'";
    $res = execquery($sql);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            $d .= '
            <tr>
                <td>' . $row[0] . '</td>
                <td>' . $row[1] . '</td>
                <td>' . $row[2] . '</td>
                <td>' . $row[3] . '</td>
                <td>' . $row[4] . '</td>
                <td>' . ($row[5] === "1" ? "Active" : "Not active") . '</td>
                <td>                
                    <button class="btn btn-secondary btn-sm" onclick="managedepartment(this);" cid="' . $row[0] . '"><i class="bi bi-building-gear"></i></button>
                    <button class="btn btn-warning btn-sm" onclick="editcompany(this);" cid="' . $row[0] . '"><i class="bi bi-pencil-square"></i></button>
                ' . (sadmin() ?
                (
                    (int)$row['dpt'] > 0 ? '' :
                    '<button class="btn btn-danger btn-sm" cid="' . $row[0] . '" onclick="removecompany(this);"><i class="bi bi-trash-fill"></i></button>')

                : '') .
                '
                </td>
            </tr>
            ';
        }
    } else {
        $d .= nores(7);
    }

    echo $d .= "</tbody>";
}

if (isset($_POST['setpages'])) {
    $sql = "SELECT count(*) FROM `companies` c where concat(c.name, c.address, c.tel_no, c.email_add) like '%$search%'";
    pages($sql, $entries);
}

if (isset($_POST['removecompany'])) {
    $sql = "DELETE FROM `companies` WHERE `company_id` = '$cid'";
    echo crud($sql);
}

if (isset($_POST['updatecompany'])) {
    $sql = "UPDATE `companies` SET `name`='$company_name',`address`='$address',`tel_no`='$tel_no',`email_add`='$email_add', `last_updated_by`='" . $_SESSION['account_id'] . "' WHERE `company_id` = '$cid'";
    echo crud($sql);
}

if (isset($_POST['addcompany'])) {
    $sql = "INSERT INTO `companies`(`name`,`address`, `tel_no`, `email_add`, `created_by`) VALUES ('$company_name', '$address', '$tel_no', '$email_add', '" . $_SESSION['account_id'] . "')";
    echo crud($sql);
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
