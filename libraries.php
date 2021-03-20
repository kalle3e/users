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
    public $iscreate = false;
    public $isdryRun = false;
    public $ishelp = false;

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
                $this->isdryRun = true;
            }
            if ('create_table' == $cLineoptionk){
                $this->iscreate = true;
            }
            if ('help' == $cLineoptionk){
                $this->ishelp = true;
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
        $sql = "DROP TABLE IF EXISTS `$this->tableName`";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

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
                $sql = "INSERT INTO $this->tableName (name,surname,email) values(:name, :surname,:email)";
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
        echo "\n Data Inserted\n";
        return;
    }
}
function help()
{
    echo "\n";
    echo "=========================================================================================";
    echo "\n";
    echo "--file [csv file name] – this is the name of the CSV to be parsed";
    echo "\n";
    echo "--create_table – this will cause the PostgreSQL users table to be built ";
    echo "\n";
    echo "(and no further action will be taken)";
    echo "\n";
    echo "--dry_run – this will be used with the --file directive in case we want ";
    echo "\n";
    echo "to run the script but not insert into the DB.";
    echo "\n";
    echo " All other functions will be executed, but the database won't be altered";
    echo "\n";
    echo "-u – Database username";
    echo "\n";
    echo "-p – Database password";
    echo "\n";
    echo "-h –  host";
    echo "\n";
    echo "--help – which will output the above list of directives with details.";
    echo "\n";
    echo "=========================================================================================";
    echo "\n";
}
?>
