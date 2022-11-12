<?php
$server "";
$user = "";
$pass = "";
$database = "";
$c = array("Database" => $database, "UID" => $user, "PWD" => $pass);
sqlsrv_configure('WarningsReturnAsErrors', 0);
$conn = sqlsrv_connect($server, $c);
if($conn === false)
{
    echo "error";
    die(print_r(sqlsrv_errors(), true));
}
?>
<!DOCTYPE html>
<html lang="en">
<head><title>Add New Tweet!</title></head>
<link rel="stylesheet" type="text/css" href="style1.css">



<body>

<form name="Application_Form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

    <div class="banner">
        <h1>Fill Tweet Details:</h1>
    </div>

    <div class="item"> Content:<span>*</span>           <input type="text"   name="Content"     title="Content" maxlength="280" required></div>

    <div class="item"> User ID:<span>*</span>

        <label>
            <select required name="UserID" ><option disabled selected value> Choose User ID... </option>
            <?php

            $sql = "SELECT ID FROM Users";
            $result = sqlsrv_query($conn,$sql);
            while ($data=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                echo '<option value="'.$data['ID'].'">';
                echo $data['ID'];
                echo "</option>";
            }

            ?>
                    </select>
        </label>
    </div>

    <div class="btn-block">
        <button name="Send" type="submit">Send</button><br><br>
        <button name="reset" type="reset">Reset Page</button><br><br>
        <p> <a href="Index.php">Back to main page</a> </p>
    </div>

</form>

<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["Name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["Name"]);
    }
}

if (isset($_POST["Send"])) {

    if($conn === false)
    {
        echo "error";
        die(print_r(sqlsrv_errors(), true));
    }

    $sql_max = "SELECT max(tID) AS tID FROM Tweets";

    $result_max = sqlsrv_query($conn, $sql_max);
    $row = sqlsrv_fetch_array($result_max, SQLSRV_FETCH_ASSOC);

    $tID=$row['tID']+1;


    $uID=$_POST['UserID'];
    $time= date("Y-m-d h:i:s");
    $content=$_POST['Content'];
    $con_arr=explode(' ',$content);

    $sql = "SELECT count(*)
      FROM Tweets";

    $result=sqlsrv_query($conn,$sql);
    $row_count = sqlsrv_fetch_array( $result );
    $var =$row_count[0]++;
    $counter2 = 0;

    $sql="INSERT INTO Tweets(tID,uID,time) 
          VALUES ('{$tID}','{$uID}' , '{$time}')";
    $result=sqlsrv_query($conn,$sql);
    if ($result===false){
        $counter2 = 1;

    }

    $sql1 = "SELECT count(*)
      FROM Words";

    $result=sqlsrv_query($conn,$sql1);
    $row_count = sqlsrv_fetch_array( $result );
    $var =$row_count[0]++;
    $con_arr=explode(' ',$content);
    $i=0;
    $counter1 = 0;
    while($con_arr[$i])
    {
        $con_arr[$i]= addslashes($con_arr[$i]);

        $sql="INSERT INTO Words(tID,idx,content) 
          VALUES ('{$tID}','{$i}' , '{$con_arr[$i]}')";
        $result=sqlsrv_query($conn,$sql);
        if ($result===false){
            echo "<center>";
            $counter1 = 1;


        }

        $i=$i+1;
    }
    if($counter1 ){
        echo "<center>";
        echo "Something went wrong! Please check your information and try again<br>";
    }
    if($counter1 == 0 && $counter2 == 0) {
        echo "<center>";
        echo "The Application was added to the database successfully.<br>";
    }
}

?>



</body>
</html>
