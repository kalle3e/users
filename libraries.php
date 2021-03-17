<?php

class FileOptions
{
    public $fileName;
    public $host;
    public $name;
    public $password;
    public $csvfile;
    public $optionAction;
    public $iscreate = false;
    public $isfile = false;
    public $isdryRun = false;

    public function __construct()
    {
        $shortopts = "";
        $shortopts .= "h::";  // : optional
        $shortopts .= "u::";
        $shortopts .= "p::";

        $longopts = array(
            "file:",           // Means insert so need to input host, username and password
            "create_table::",     // Optional value. Need to input host, username and password
            "dry_run",        // Also need to input csv file name
            "help",
        );
        $options = getopt($shortopts, $longopts);

        switch ($options)
        {
            case "help":
                break;
            case "create_table":
                break;
            case "file":
                break;
            case "dryRun":
                break;
            case "h":
            case "u":
            case "p":
                break;
        }
    }
}
function readCSV()
{
    //Read in to array
    $file = 'users.csv';
    $lines = file($file);
    $i = 1;
    foreach ($lines as $line) // Ignore heading line at $i=0
    {
        $line = explode(',', $lines[$i]);
        $name = $usersArr[$i]['name'] = ucfirst(trim($line[0]));
        $surname = $usersArr[$i]['surname'] = ucfirst(trim($line[1]));
        if (! ( filter_var($email = $usersArr[$i]['email'] = strtolower(trim($line[2])), FILTER_VALIDATE_EMAIL)) )
        {
            echo("$email is not a valid email address");
            exit;
        }
        $i++;
    }
}
$servername = "localhost";
$username = "username";
$password = "password";
$db = "dbname";
try {
    $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password, $db);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}
?>
