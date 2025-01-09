<?php
  //$year = date('Y');
  session_start();
  //echo $_SESSION["username"];
  
    //session_destroy();
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    $sessionValue = $_SESSION["username"];
    
  $year = date('Y');
  
  if(isset($_SESSION["username"]))
  {
    //$sess_value = $_SESSION["username"];
   // session_unset();
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



    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        //session_start();
        try
        {
            if(isset($_POST["JUD"]))
             {
                $select = getValue($_POST["JUD"]);
                //session_start();
                //$_SESSION["JUD"] = "fetches";
                if($select == "logOut")
                {
                    header("Location: logout.php");
                    die;
                }
                if($select == "Fetch")
                {
                // session_start();
                 // $_SESSION["JUD"] = "fetches";
                  //  $_SESSION["JUD"] = "fetches";
                    header("Location: fetch.php");

                    die;
                }

                else if($select == "Insert")
                {
                  //  $_SESSION["JUD"] = "fetches";
                    header("Location: insert.php");
                    die;
                }

                else if($select == "Update")
                {
                  //  $_SESSION["JUD"] = "fetches";
                    header("Location: update.php");
                    die;
                }

                else if($select == "Delete")
                {
                  //  $_SESSION["JUD"] = "fetches";
                    header("Location: delete.php");
                    die;
                }


             }
             //unset( $_SESSION["username"]);
        }

        catch(Exception $e){
            $arrayElement = $e->getMessage();
        }
    } 

}

else
{
    //session_destroy();
    header("Location: index.php");
                    die;
}



?>
<!DOCTYPE html>
 <html>
     <head>
         <title>Admin Page</title>
         <meta charset="utf-8">
         <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">



     </head>

     <body class="w3-panel">
         <header class="w3-container w3-khaki"><h1>Honors Program Student's Service Hours Report</h1></header>
         <main class="w3-panel">
            <div>
                
            </div>
            <div class="w3-panel">
                 Welcome admin. You can add, update, and delete records of honors student in this page!
            </div>
            <form class="w3-panel w3-border" action="<?php getPostback(); ?>" method="POST">
            <p>
                        <button class="w3-button w3-teal w3-round" name="JUD" value="Fetch">Fetch a record</button>
            </p>
            <p>
                        <button class="w3-button w3-green w3-round" name="JUD" value="Insert">Insert a record</button>
            </p>

            <p>
                        <button class="w3-button w3-blue w3-round" name="JUD" value="Update">Update a record</button>
            </p>
            <p>
                        <button class="w3-button w3-pink w3-round" name="JUD" value="Delete">Delete a record</button>
            </p>
            <p>
                        <button class="w3-button w3-red w3-round" name="JUD" value="logOut">Logout</button>
            </p>

            </form>


            </main>


    <footer class="w3-panel w3-center w3-text-gray w3-small">
             &copy; <?php echo $year; ?> Riwaz Poudel
        </footer>
</body>


     </html>