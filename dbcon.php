<?php
session_start();
ini_set('memory_limit', '10024M'); // Set memory limit to 1GB (adjust as needed)
/* date_default_timezone_set('Asia/Manila'); */
class DatabaseConnection
{

    private $server = 'localhost';
    private $uname = 'root';
    private $pass = '';
    private $db = 'payroll';
    /* 
    private $server = 'localhost';
    private $uname = 'id21228883_chsi';
    private $pass = '@ChsiSystem1234@';
    private $db = 'id21228883_chsi'; 
    */
    private $con;

    public function __construct()
    {
        try {
            $this->con = @new mysqli($this->server, $this->uname, $this->pass, $this->db);
            if ($this->con->connect_error) {
                throw new Exception("Initial connection failed: " . $this->con->connect_error);
            }
        } catch (Exception $e) {
            $this->uname = 'u360274517_chsi';
            $this->pass = '3!0i/dBy';
            $this->db = 'u360274517_chsi';

            $this->con = new mysqli($this->server, $this->uname, $this->pass, $this->db);

            if ($this->con->connect_error) {
                die("Reconnection failed: " . $this->con->connect_error);
            }
        }
    }

    public function getConnection()
    {
        return $this->con;
    }

    public function executeQuery($sql)
    {
        $con = $this->getConnection();

        $result = $con->query($sql);

        if (!$result) {
            die($con->error);
        }

        return $result;
    }

    public function getNewId($sql)
    {
        $con = $this->getConnection();
        $this->executeQuery($sql);
        return $con->insert_id;
    }

    public function getAffectedRows($sql)
    {
        $con = $this->getConnection();
        $this->executeQuery($sql);
        return $con->affected_rows;
    }

    public function executeMultiQuery($sql)
    {
        $con = $this->getConnection();
        $res = $con->multi_query($sql);

        if (!$res) {
            die("Error executing multi-query: " . $con->error);
        }

        return $res;
    }
}

global $db;
$db = new DatabaseConnection();

function execquery($sql)
{
    global $db;
    return $db->executeQuery($sql);
}

function affected($sql)
{
    global $db;
    return $db->getAffectedRows($sql);
}

function getnewid($sql)
{
    global $db;
    return $db->getNewId($sql);
}

function multiquery($sql)
{
    global $db;
    return $db->executeMultiQuery($sql);
}

global $db;
$conn = $db->getConnection();

foreach ($_POST as $key => $value) {
    $_POST[$key] = sanitizeInput($value, $conn);
}

function sanitizeInput($input, $conn)
{
    // If $input is an array, return it without modification
    if (is_array($input)) {
        return $input;
    } else {
        // Validate and sanitize the input
        $input = trim($input);
        // Basic email validation
        if ($input === filter_var($input, FILTER_SANITIZE_EMAIL)) {
            $input = filter_var($input, FILTER_SANITIZE_EMAIL);
        } else {
            $input = $input;
        }
        // Escape the input
        $input = mysqli_real_escape_string($conn, $input);
        return $input;
    }
}


function optiontags($res, $id = "")
{
    $opt = '';
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            $opt .= '<option value="' . $row[0] . '" ' . (($row[0] === $id) ? "selected" : "") . '>' . $row[1] . '</option>';
        }
    } else {
        $opt = '<option value="" disabled>No Data Found</option>';
    }
    echo $opt;
}

function pages($sql, $entries)
{
    $count = 0;

    if (is_string($sql)) {
        $res = execquery($sql);
        $count = mysqli_fetch_array($res)[0];
    } else {
        $count = $sql;
    }

    $pages = '';
    if ($count > 0) {
        $p = ceil($count / $entries);

        for ($i = 0; $i < $p; $i++) {
            $pages .= '<option value="' . $i . '">' . ($i + 1) . '</option>';
        }
    } else {
        $pages = '<option value="0">0</option>';
    }

    echo $pages;
}

function plarr($cl)
{
    $pls = $_POST;
    $arrk = array_keys($_POST);
    $arr = [];
    for ($i = 0; $i < $cl; $i++) {
        array_push($arr, $pls[$arrk[$i]]);
    }
    return $arr;
}

function createFile($originalFile, $destination)
{
    if (!copy($originalFile, $destination)) {
    } else {
    }
}

function createDirectory($path)
{
    if (!is_dir($path)) {
        if (mkdir($path, 0777, true)) {  // 0777 grants full permissions, but use a more restrictive value in production.
            return "Folder Created";
        } else {
            return "Failed to Create Folder";
        }
    } else {
        return "Folder Already Existing";
    }
}

function nores($colspan)
{
    $d = '
    <tr>
        <td colspan="' . $colspan . '">No data found</td>
    </tr>';
    return $d;
}

function sadmin()
{
    if ($_SESSION['usertype'] === "superadmin") {
        return true;
    } else {
        return false;
    }
}

function crud($sql)
{
    $res = affected($sql);
    if ($res > 0) {
        return "success";
    } else {
        return "error";
    }
}


$search = $_POST['search'] ?? '';
$entries = $_POST['entries'] ?? '';
$page = $_POST['page'] ?? '';
