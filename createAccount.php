
<?php
    // Turn on error reporting.
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    session_start();
    
    // Type your code here
    //$year = date('Y');
    $year = date('Y');
    $count = 0;
    function getDSN()
    {

        return "mysql:host=localhost;port = 8889;dbname=project";
    }

    function getPostback()
    {
        return $_SERVER['PHP_SELF'];
    }

    function getUserName()
    {
        return "root";
    }
    
    function getPassword()
    {
        return "root";

    }

    function getPDO()
    {
        $pdo = new PDO(getDSN(),getUserName(),getPassword());
        return $pdo;
    }

    function getValue($key)
{
    if (!isset($key))
    {
       return "";
    }
    else{
        htmlspecialchars($key);
        trim($key);
        return $key;
    }
}

function sqlInsertQuery()
{
    return "INSERT INTO registration
    (username, password, name, email)
VALUES
    (?, ?, ?, ?);";

}


function sqlSelectQuery()
    {
        return "SELECT COUNT(*)
        FROM registration 
        WHERE username=?;";
    }




function insertData()
{
    if ($_SERVER['REQUEST_METHOD']== 'POST')
        {
            
                try{
                    if(preg_match('/\d/',$_POST["password"]))
                    {
                    $pdo = getPDO();

                    $username = getValue($_POST["username"]);
                    $pdoStatement1 = $pdo->prepare(sqlSelectQuery());
                   // $pdoStatement1->bindParam(":UserName", $username);
                    $pdoStatement1->execute([$username]);
                    $counts = $pdoStatement1->fetchColumn();
                    //echo $counts;
                    if ($counts == 0){

                    $password = getValue($_POST["password"]);
                    $passwordhash = password_hash($password, PASSWORD_BCRYPT);
                    $name = getValue($_POST["name"]);
                    $email = getValue($_POST["email"]);
                   // echo $passwordhash;
                    $params = array($username,$passwordhash,$name,$email);


                    $pdoStatement = $pdo->prepare(sqlInsertQuery());
                    $pdoStatement->execute($params);
                    
                    echo "<p> Your account has been created. Click Login to go back to login page</p><p><a href='index.php'>Login</a></p>";

                   // $id = $pdo->lastInsertId();
                    }

                    else
                    {
                        echo "The username already exists. Please use another unique username.";
                    }
                   // echo "<br>Inserted record with id $id";
                    $pdo = null;

                }

                else
                {
                    echo "Please enter atleast one digit in your password for security reasons.";
                }
                    


                    


                }

                catch(PDOException $e)
                {
                    echo "getMessage($e)";
                }

        }
}
    
    
?>



<!-- HTML rendered as is. Note the PHP drop-ins, i.e. PHP tags. -->
 <!DOCTYPE html>
 <html>
     <head>
         <title>Account Setup</title>
         <meta charset="utf-8">
         <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
     </head>

     <body class="w3-panel">
         <header class="w3-container w3-khaki"><h1>Honors Student Account Creation</h1></header>
        
        <main class="w3-container">
            <div>
                We are glad that you are joining the Honors Program. You can create your account in this site.
            </div>
            <?php echo insertData(); ?>
            <form action="<?php getPostback(); ?>" method="POST">
                <p>
                    <label>username</label>
                    <input class="w3-input w3-border w3-light-gray" name="username" autofocus required>
                </p>

                <p>
                    <label>Password</label>
                    <input class="w3-input w3-border w3-light-gray" type = "password" name="password" required>
                </p>

                <p>
                    <label>Name</label>
                    <input class="w3-input w3-border w3-light-gray" name="name" required>
                </p>

                <p>
                    <label>Email</label>
                    <input class="w3-input w3-border w3-light-gray" name="email" required>
                </p>

               
                <p>
                    <button class="w3-button w3-blue w3-round">Create Account</button>
                </p>
            </form>

        </main>

         <footer class="w3-panel w3-center w3-text-gray w3-small">
             &copy; <?php echo $year; ?> Riwaz Poudel
        </footer>
     </body>
 </html>



