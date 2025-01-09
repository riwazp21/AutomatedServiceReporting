<?php
 $year = date('Y');
session_start();
$username = $_SESSION["username"];

 if(isset($_SESSION["username"]))
 {
   // $username = $_SESSION["username"];

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

    function sqlJoinQuery()
    {
    return "SELECT r.name AS 'Name', r.email AS 'Email', o.orgname as 'Organization', s.year as 'Year', s.hours as 'Hours', s.OnOff as 'Where'
    FROM registration as r, organization as o, service as s
    WHERE o.id = s.org\$id AND r.id = s.student\$id AND r.username = ?
    ORDER BY year, hours, OnOff;";

    }



   function buildTable($pdoStatement) {
        $table = "";

        $table .= "<table class = \"w3-table-all\"><caption class = \"w3-large\">Service Hour Report</caption>";
        $table .= "<thead><tr><th>#<th>Service Organization</th><th>Year</th><th>Hours</th><th>On/Off Campus</th></tr></thead>";
        $table .= "<tbody>";
        $count = 1;
        $hours = 0;
        foreach ($pdoStatement as $row)
        {
          // $num = number_format($row['Population']);
            $table .= "<tr><td>$count</td><td>$row[Organization]</td><td>$row[Year]</td><td>$row[Hours]</td><td>$row[Where]</td></tr>";
            $hours = $hours + "$row[Hours]";
            $count = $count + 1;
        }
       // $table = "<tr><td>Total Hours</td><td>$hours</td><td> </td><td> </td><td> </td></tr>";
        $table .= "</tbody></table><div><p>You have $hours hrs of Service Hours.</p></div>";
        return $table;
        //echo $table;
 }
    
 
 





function displayRecords(){

    if(isset($_POST["logout"]))
    {
        header("Location: logout.php");
        die;
    }

    else
    {

        try{
            $bro = $_SESSION["username"];
            echo "$bro, Welcome to the Honors Program Service Report Page. Here are your overall service hours detail";
            $pdo = getPDO();
           $pdoStatement = $pdo->prepare(sqlJoinQuery());

  //echo $bro;
 // echo $bro;
            $pdoStatement->execute([$bro]);

          echo buildTable($pdoStatement);
           $pdo = null;
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
         <title>Student Page</title>
         <meta charset="utf-8">
         <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">



     </head>

     <body class="w3-panel">
         <header class="w3-container w3-khaki"><h1>Honors Program Student Report</h1></header>
         <main class="w3-panel">
            <div class="w3-panel">
               
                <?php echo displayRecords(); ?>
            </div>
                
            <div>
            <form action="<?php getPostback(); ?>" method="POST">
            <p>
                    <button class="w3-button w3-red w3-round" name="logout">Logout</button>
                </p>
            </div>

            </main>
    <footer class="w3-panel w3-center w3-text-gray w3-small">
             &copy; <?php echo $year; ?> Riwaz Poudel
        </footer>
</body>


</html>