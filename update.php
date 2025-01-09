<?php
 // $year = date('Y');
session_start();
 if(isset($_SESSION["username"]))
 {
  $year = date('Y');

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
    return "SELECT s.id as 
    'id', r.name AS 'Name', r.email AS 'Email', o.orgname as 'Organization', s.year as 'Year', s.hours as 'Hours', s.OnOff as 'Where'
    FROM registration as r, organization as o, service as s
    WHERE o.id = s.org\$id AND r.id = s.student\$id
    ORDER BY id, Name, year, hours;";

}

function buildTable($pdoStatement)
    {

        $table = "";

        $table .= "<table class = \"w3-table-all\"><caption class = \"w3-large\">Student Service Hour Report</caption>";
        $table .= "<thead><tr><th>Record Id</th><th>Student Name</th><th>Email</th><th>Service Organization</th><th>Year</th><th>Hours</th><th>On/Off Campus</th></tr></thead>";
        $table .= "<tbody>";
        $count = 1;
        foreach ($pdoStatement as $row)
        {
          // $num = number_format($row['Population']);
            $table .= "<tr><td>$row[id]</td><td>$row[Name]</td><td>$row[Email]</td><td>$row[Organization]</td><td>$row[Year]</td><td>$row[Hours]</td><td>$row[Where]</td></tr>";
            $count = $count + 1;
        }
        $table .= "</tbody></table>";
        return $table;

    }



    function idDropdown() {
        try {
            $pdo = getPDO();
            $pdoStatement = $pdo->query(sqlJoinQuery());

            $select = "<select class='w3-input w3-border w3-light-gray' name='id' style='width: 130px;' autofocus required>";
            $count = 1;
            foreach ($pdoStatement as $rowData) {
                $select .= "<option>$rowData[id]</option>";
                $count = $count + 1;
            }
            $select .= "</select>";
            $pdo = null;

            return $select;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function sqlUpdateQuery()
    {
        return "UPDATE service
        SET hours=?
        WHERE id=?;";
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
                if($select == "fetch")
                {
                    $pdo = getPDO();
                    $pdoStatement = $pdo->query(sqlJoinQuery());
                    $pdoStatement = $pdo->query(sqlJoinQuery());
                    echo buildTable($pdoStatement);
                    $pdo = null;

                }

                else
                {
                   

                   
                        $pdo = getPDO();
                       // $pdoStatement = $pdo->prepare(sqlInsertQuery());
                       // $pdoStatement->execute($params);
                        $id = getValue($_POST["id"]);
                        $newHour = getValue($_POST["hours"]);

                        $param = array($newHour, $id);
                        $pdoStatement1 = $pdo->prepare(sqlUpdateQuery());
                        $pdoStatement1->execute($param);

                        $pdoStatement = $pdo->query(sqlJoinQuery());
                        echo "Record has been updated";
                        echo buildTable($pdoStatement);
                        $pdo = null;
                       // echo "<p> Your account has been created. Click Login to go back to login page</p><p><a href='login.php'>Login</a></p>";


                    




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
         <header class="w3-container w3-khaki"><h1>Record Update</h1></header>
         <main class="w3-panel">
            
            <div class="w3-panel">
                 Welcome admin. You can update any service hour records of student in this page
            </div>
            <form class="w3-panel w3-border" action="<?php getPostback(); ?>" method="POST">
            <p>
                <label>Change service hours to: </label>
                <input class="w3-input w3-border w3-light-gray" name="hours" autofocus required>
                <label>for record id: <?php echo idDropDown(); ?></label>
            </p>

            <p>
                        <button class="w3-button w3-green w3-round" name="insertButton" value="insert">Update Record</button>
                       
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