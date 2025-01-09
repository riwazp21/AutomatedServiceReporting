<?php
 // $year = date('Y');
  $year = date('Y');

session_start();

  if(isset($_SESSION["username"]))
{
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

    function sqlSelectNumRegQuery()
    {
        return "SELECT COUNT(*)
        FROM registration 
        WHERE username=?;";
    }

    function sqlSelectNumOrgQuery()
    {
        return "SELECT COUNT(*)
        FROM organization 
        WHERE abbrev=?;";
    }

    function sqlSelectOrgId()
    {
        return "SELECT id
        FROM organization 
        WHERE abbrev=?;";

    }

    function sqlSelectRegId()
    {
        return "SELECT id
        FROM registration 
        WHERE username=?;";

    }

    
    function sqlInsertQuery()
{
    return "INSERT INTO service
    (student\$id, org\$id, year, hours, OnOff)
VALUES
    (?, ?, ?, ?,?);";

}

function sqlJoinQuery()
{
    return "SELECT r.name AS 'Name', r.email AS 'Email', o.orgname as 'Organization', s.year as 'Year', s.hours as 'Hours', s.OnOff as 'Where'
    FROM registration as r, organization as o, service as s
    WHERE o.id = s.org\$id AND r.id = s.student\$id
    ORDER BY year, hours, OnOff";

}

function buildTable($pdoStatement)
    {

        $table = "";

        $table .= "<table class = \"w3-table-all\"><caption class = \"w3-large\">Student Service Hour Report</caption>";
        $table .= "<thead><tr><th>#</th><th>Student Name</th><th>Email</th><th>Service Organization</th><th>Year</th><th>Hours</th><th>On/Off Campus</th></tr></thead>";
        $table .= "<tbody>";
        $count = 1;
        foreach ($pdoStatement as $row)
        {
          // $num = number_format($row['Population']);
            $table .= "<tr><td>$count</td><td>$row[Name]</td><td>$row[Email]</td><td>$row[Organization]</td><td>$row[Year]</td><td>$row[Hours]</td><td>$row[Where]</td></tr>";
            $count = $count + 1;
        }
        $table .= "</tbody></table>";
        return $table;

    }






function displayRecords()
{
    if ($_SERVER['REQUEST_METHOD']== 'POST')
        {
            try{
                if(isset($_POST["insertButton"]))
             {     
                $select = getValue($_POST["insertButton"]);

                if($select == "goBack")
                {
                    header("Location: admin.php");
                    die;
                }

                else
                {
                   

                   
                        $pdo = getPDO();
                       // $pdoStatement = $pdo->prepare(sqlInsertQuery());
                       // $pdoStatement->execute($params);

                        $pdoStatement = $pdo->query(sqlJoinQuery());
                        echo buildTable($pdoStatement);

                       // echo "<p> Your account has been created. Click Login to go back to login page</p><p><a href='login.php'>Login</a></p>";
                        $pdo = null;

                    




                }
                


             }

                }

                catch(PDOException $e)
                {
                    echo "getMessage($e)";
                }


        }

}


}

else
{
    header("Location: index.php");
                    die;
} 


    ?>




<!DOCTYPE html>
 <html>
     <head>
         <title>Insert Record Page</title>
         <meta charset="utf-8">
         <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">



     </head>

     <body class="w3-panel">
         <header class="w3-container w3-khaki"><h1>Record Fetch</h1></header>
         <main class="w3-panel">
            
            <div class="w3-panel">
                 Welcome admin. You can fetch all records of student's service hours in this page
            </div>
            <form class="w3-panel w3-border" action="<?php getPostback(); ?>" method="POST">
            
            <p>
                        <button class="w3-button w3-green w3-round" name="insertButton" value="insert">Fetch Record</button>
            </p>
            </form>





            
           <form class="w3-panel w3-border" action="<?php getPostback(); ?>" method="POST">
                <p>
                    <button class="w3-button w3-lime w3-round" name="insertButton" value="goBack">Head to home page</button>
                </p>
            </form>

            <div>
            <?php echo displayRecords(); ?>
            </div>


            </main>


    <footer class="w3-panel w3-center w3-text-gray w3-small">
             &copy; <?php echo $year; ?> Riwaz Poudel
        </footer>
</body>


     </html>