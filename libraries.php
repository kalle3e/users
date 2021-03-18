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
function insert($users, $conn)
    {
        $table = 'userupload';
        $i=0;
//        $isdryRun = false;  //  Only for testing
        $isdryRun = true;  //  Only for testing
        foreach($users as $user)
        {
            if ($i < count($users)) {
                $name = $user['name'];
                $surname = $user['surname'];
                $email = $user['email'];
                $i++;
            }
            try
            {

                $conn->beginTransaction();
                $sql = "insert into $table (name,surname,email) values(:name, :surname,:email)";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':surname', $surname);
                $stmt->bindValue(':email', $email);
                $stmt->execute();                // TO DO +++++++++ COMMIT ROLLBACK  +++++++++++++++++++=
                if ($isdryRun) {
                    $conn->rollback();
                    echo "\n Dry Run \n";
                    exit;
                }
                $conn->commit();
            }
            catch (\PDOException $e)
            {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return;
     }

?>
