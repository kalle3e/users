<?php
include 'connectDB.php';
//require_once
class FileOptions
{
    public $cLineOptions;
    public $fileName;
    public $host;
    public $name;
    public $password;
    public $iscreate = 0;
    public $isdryRun = 0;
    public $ishelp = 0;

    public function __construct()
    {

    }
    public function init()
    {
        $shortopts = "";
        $shortopts .= "h:";
        $shortopts .= "u:";
        $shortopts .= "p:";

        $longopts = array(
            "file:",           // Means insert so need to input host, username and password
            "create_table",
            "dry_run",        // Also need to input csv file name
            "help",
        );
        $cLineOptions = getopt($shortopts, $longopts);

        foreach ($cLineOptions as $cLineoptionk => $cLineOptionv) {

            if ('help' == $cLineoptionk){
                $this->ishelp = true;
            }
            if ('file' == $cLineoptionk){
                $this->fileName = $cLineOptionv;
            }
            if ('h' == $cLineoptionk){
                $this->host = $cLineOptionv;
            }
            if ('u' == $cLineoptionk){
                $this->name = $cLineOptionv;
            }
            if ('p' == $cLineoptionk){
                $this->password = $cLineOptionv;
            }
            if ('dryRun' == $cLineoptionk){
                $this->isdryRun = 1;
            }
            if ('create_table' == $cLineoptionk){
                $this->iscreate = 1;
            }
            if ('help' == $cLineoptionk){
                $this->ishelp= 1;
            }
        }
    }
}
function readCSV($filename)
{
    //Read in to array
//    $filename = 'users.csv';
    $lines = file($filename);
    $i = 1;
    foreach ($lines as $line) // Ignore heading line at $i=0
    {
        if ($i == count($lines))
        {
            return $usersArr;
        }
        $line = explode(',', $lines[$i]);
        $usersArr[$i]['name'] = ucfirst(trim($line[0]));
        $usersArr[$i]['surname'] = ucfirst(trim($line[1]));
        if (!( filter_var($email = $usersArr[$i]['email'] = strtolower(trim($line[2])), FILTER_VALIDATE_EMAIL)))
        {
            echo("$email is not a valid email address");
            exit;
         }
        $i++;
    }
}
class Db
{
    public $tableName ="userupload";
    public $conn;
    public $fileOptions;
    public $usersData;


    public function __construct($fileoptions,$usersdata,$conn)
    {
        //$this["user"] = $user;
        $this->users = $fileoptions;
        $this->fileoptions = $fileoptions;
        $this->usersData = $usersdata;
        $this->conn = $conn;
    }
    public function createTable()
    {
        // create table if not exits syntax ************* TO DO
        $sql = "CREATE TABLE IF NOT EXISTS $this->tableName (
        name 		varchar(40) NOT NULL,
        surname 	varchar(80) NOT NULL,
        email		varchar(100)  PRIMARY KEY,
        UNIQUE(email))";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        echo "\nCreate table successful\n";
        return;

    }
    public function insert()
    {
        $i = 0;
        foreach ($this->usersData as $user) {
            if ($i < count($this->usersData)) {
                $name = $user['name'];
                $surname = $user['surname'];
                $email = $user['email'];
                $i++;
            }
            try {

                $this->conn->beginTransaction();
                $sql = "insert into $this->tableName (name,surname,email) values(:name, :surname,:email)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':surname', $surname);
                $stmt->bindValue(':email', $email);
                $stmt->execute();
                if ($this->fileOptions->isdryRun) {
                    $this->conn->rollback();
                    echo "\n Dry Run \n";
                    exit;
                }
                $this->conn->commit();
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return;
    }
}
?>
