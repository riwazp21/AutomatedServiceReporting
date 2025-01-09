<?php

$year = date('Y');
//$year = 2020;
$errorMessage = "";
//();
error_reporting(E_ALL);
ini_set('display_errors', '1');
function getDSN()
    {

        return "mysql:host=localhost;port = 8889;dbname=project";
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

function getPostback()
    {
        return $_SERVER['PHP_SELF'];
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

    function sqlSelectNumQuery()
    {
        return "SELECT COUNT(*)
        FROM registration 
        WHERE username=?";
    }

    function sqlSelectQuery()
    {
        return "SELECT username, password
        FROM registration
        WHERE username=?;";
    }


    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        session_start();
       // echo $_SESSION["username"];
        try{
             if(isset($_POST["accountbutton"]))
             {
                $select = getValue($_POST["accountbutton"]);
                // echo $select;
                if($select == "login")
                {
                //session_start();
                    $username = getValue($_POST["username"]);
                    $password = getValue($_POST["password"]);
                    if ($username == "admin" && $password == "admin") {
                      //  session_start();
                        $_SESSION["username"] = $username;
                        //session_destroy();
                        header("Location: admin.php");
                        die;
                     }
                     else
                     {
                        $pdo = getPDO();
                        $pdoStatement1 = $pdo->prepare(sqlSelectNumQuery());
                        $pdoStatement1->execute([$username]);
                        $counts = $pdoStatement1->fetchColumn();

                        if($counts == 0)
                        {
                            $errorMessage = "<p>Incorrect username or password. Please try again.</p>";
                        }
                        else if($counts == 1)
                        {
                            //session_start();
                            $_SESSION["username"] = $username;
                            $pdoStatement = $pdo->prepare(sqlSelectQuery());
                            $pdoStatement->execute([$username]);

                            $row = $pdoStatement->fetch();
                            //echo $row["password"];
                            if (password_verify($password, $row["password"])) {
                                $_SESSION["username"] = $username;
                                header("Location: user.php");
                                //die;
                             } 

                             else
                             {
                                
                                $errorMessage="<p>Incorrect username or passord. Please try again</p>";
                             }
                             
                            
                        }


                       // $numRows = $pdoStatement->num_rows;
                        




                       // $_SESSION["username"] = $username;
                        //header("Location: user.php");
                        //die;
                     }
                    
                }
                else if($select = "creatAcc")
                {
                    header("Location: createAccount.php");
                    die;
                }
             }
             
    
    
    
        }
        catch(Exception $e){
            $arrayElement = $e->getMessage();
        }
    } 






?>


<!DOCTYPE html>
 <html>
     <head>
         <title>Honors Program Student Report</title>
         <meta charset="utf-8">
         <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">



     </head>

     <body class="w3-panel">
         <header class="w3-container w3-khaki"><h1>Honors Program Service Hour Report Management</h1></header>
         <main class="w3-panel">
            <div class="w3-panel">
                 Welcome to the login page!
            </div>
           
            <form class="w3-panel w3-border" action="<?php getPostback(); ?>" method="POST">
                    <p>
                        <label>Username</label>
                        <input class="w3-input w3-border w3-khaki" type="text" name="username" required>
                    </p>

                    <p>
                        <label>Password</label>
                        <input class="w3-input w3-border w3-khaki" type="password" name="password" required>
                    </p>

                    

                    <p>
                        <button class="w3-button w3-green w3-round" name="accountbutton" value="login">Log In</button>
                    </p>
                    


            </form>
                
            <form class="w3-panel w3-border" action="<?php getPostback(); ?>" method="POST">
                <p>
                    <button class="w3-button w3-red w3-round" name="accountbutton" value="createAcc">Create Account</button>
                </p>

               
            </form>

            <p>
                     <?php echo $errorMessage ?>
                </p>
    </main>
    <footer class="w3-panel w3-center w3-text-gray w3-small">
             &copy; <?php echo $year; ?> Riwaz Poudel
        </footer>
     </body>
   </html>  
