<?php
session_start();
ini_set('memory_limit', '10024M'); // Set memory limit to 1GB (adjust as needed)
/* date_default_timezone_set('Asia/Manila'); */
class DatabaseConnection
{

    private $server = 'localhost';
    private $uname = 'root';
    private $pass = '';
    private $db = 'chsi';
    /* private $server = 'localhost';
    private $uname = 'id21228883_chsi';
    private $pass = '@ChsiSystem1234@';
    private $db = 'id21228883_chsi'; */
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

    /* 
    public function __construct()
    {
        $this->con = new mysqli($this->server, $this->uname, $this->pass, $this->db);

        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }
    } */

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

$pls = $_POST;
$arrk = array_keys($_POST);
$cl = count($arrk);

for ($i = 0; $i < $cl; $i++) {
    // Modify the original $_POST value with the sanitized value
    $_POST[$arrk[$i]] = cstring($pls[$arrk[$i]], $conn);
}

function cstring($input, $conn)
{
    if (is_array($input)) {
        // If $input is an array, return it without modification
        return $input;
    } else {
        // If $input is not an array, add slashes and escape using mysqli_real_escape_string
        $str = addslashes($input);
        $str = mysqli_real_escape_string($conn, trim($str));
        return $str;
    }
}


function optiontags($res, $id)
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

function setpages($count, $entries)
{
    $pages = '';
    if ($count > 0) {
        $p = $count / $entries;
        $p = ceil($p);

        for ($i = 0; $i < $p; $i++) {
            $pages .= '<option value=\'' . $i . '\' >' . $i + 1 . '</option>';
        }
    } else {
        $pages = '<option value="0">0</option>';
    }
    echo $pages;
}
