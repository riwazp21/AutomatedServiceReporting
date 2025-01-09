<?php

session_start();
if(isset($_SESSION["username"]))
{
  $year = date('Y');
  $year = 2012;



  function idDropdown() {
    try {
        $pdo = getPDO();
        $pdoStatement = $pdo->query(sqlOrgName());

        $select = "<select class='w3-input w3-border w3-light-gray' name='orgId' style='width: 130px;' autofocus required>";
        $count = 1;
        foreach ($pdoStatement as $rowData) {
            $select .= "<option>$rowData[abbrev]</option>";
            $count = $count + 1;
        }
        $select .= "</select>";
        $pdo = null;

        return $select;
    } 
    
    catch (PDOException $e) {
        echo $e->getMessage();
    }
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

function sqlOrgname()
{
    return "SELECT abbrev from organization ORDER by abbrev;";
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
                    if(preg_match('/^\d+$/',$_POST["year"])&&preg_match('/^\d+$/',$_POST["hours"]))
                    {
                    $studentName = getValue($_POST["studentId"]);
                    $orgName =  getValue($_POST["orgId"]);

                    $pdo = getPDO();

                    $pdoStatement1 = $pdo->prepare(sqlSelectNumRegQuery());
                    $pdoStatement1->execute([$studentName]);
                    $stuCounts = $pdoStatement1->fetchColumn();

                    $pdoStatement2 = $pdo->prepare(sqlSelectNumOrgQuery());
                    $pdoStatement2->execute([$orgName]);
                    $orgCounts = $pdoStatement2->fetchColumn();

                    $pdoStatement3 = $pdo->prepare(sqlSelectOrgId());
                    $pdoStatement3->execute([$orgName]);
                    $orgId = $pdoStatement3->fetch();


                    $pdoStatement4 = $pdo->prepare(sqlSelectRegId());
                    $pdoStatement4->execute([$studentName]);
                    $stuId = $pdoStatement4->fetch();

                  //  echo "$orgId<br>$stuId";
                   


                   // $orgId[0];
                   // echo $stuCounts;

                    if($stuCounts == 0 || $orgCounts == 0)
                    {
                        echo "Invalid username or organization name";
                    }

                    else
                    {
                        $term = getValue($_POST["term"]);
                        $year = getValue($_POST["year"]);
                        $hours = getValue($_POST["hours"]);
                        $where = getValue($_POST["where"]);
                        $params = array($stuId[0],$orgId[0],$year,$hours,$where);
                        $pdoStatement = $pdo->prepare(sqlInsertQuery());
                        $pdoStatement->execute($params);

                        $pdoStatement = $pdo->query(sqlJoinQuery());
                        echo buildTable($pdoStatement);
                        $pdo = null;
//echo "<p> Your account has been created. Click Login to go back to login page</p><p><a href='login.php'>Login</a></p>";


                    }




                }

                else
                {
                    echo "Please make sure you enter a valid number for years or hours.";
                }
                
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
         <header class="w3-container w3-khaki"><h1>Record Insertion</h1></header>
         <main class="w3-panel">
            
            <div class="w3-panel">
                 Welcome admin. You can insert new records of student service hours in this page
            </div>
            <form class="w3-panel w3-border" action="<?php getPostback(); ?>" method="POST">
            <p>
                    <label>Student Username</label>
                    <input class="w3-input w3-border w3-light-gray" name="studentId" autofocus required>
            </p>

            <p>
                    <label>Organization Abbreviation</label>
                    <?php echo idDropDown(); ?>
            </p>

            <p>
                    <label>Year</label>
                    <input class="w3-input w3-border w3-light-gray" name="year" autofocus required>
            </p>

            <p>
                    <label>Hours</label>
                    <input class="w3-input w3-border w3-light-gray" name="hours" autofocus required>
            </p>

            <p>
                    <label>On/Off Campus Service</label>
                    <select class="w3-input w3-border w3-light-gray" name="where" style="width: 200px;" autofocus required>
                        <option value = "On">On-campus</option>
                        <option value = "Off">Off-campus</option>
                    </select>
            </p>




            <p>
                        <button class="w3-button w3-green w3-round" name="insertButton" value="insert">Insert Record</button>
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