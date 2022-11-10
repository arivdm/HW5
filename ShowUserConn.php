<?php
$server = "tcp:techniondbcourse01.database.windows.net,1433";
$user = "van0ari";
$pass = "Qwerty12!";
$database = "van0ari";
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
    <head><title>Show User Connections!</title></head>
    <link rel="stylesheet" type="text/css" href="style1.css">



<body style="text-align: center">

<form name="Application_Form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

    <div class="banner">
        <h1>Select User ID:</h1>
    </div>



    <div class="item"  style="text-align: center"> User ID:<span>*</span>

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

    $uID=$_POST['UserID'];

    echo "<center>";
    echo "<table border=\"2\">";
    echo "<tr><th>Friends of User $uID</th></tr>";


    $query = "SELECT U.name AS 'Friend Name'
FROM Follows F RIGHT JOIN Users U on F.ID1 = U.ID
WHERE F.ID2 = $uID AND F.ID1 = (SELECT DISTINCT F1.ID2
        FROM Follows F1
    WHERE F.ID1=F1.ID2 AND F.ID2=F1.ID1)
ORDER BY U.name ASC ";

    $result=sqlsrv_query($conn,$query);
    $var=0;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        $Friend=$row['Friend Name'];
        echo "<tr><td>$Friend</td></tr>";


    }



    echo "<center>";
    echo "<table border=\"2\" >";
    echo "<tr><th>Semi Friends of User $uID</th></tr>";


    $query1 = "SELECT Users.name AS 'Friends friends Name'
FROM
(SELECT DISTINCT ALL_FRIENDS.ID
FROM(SELECT U.ID AS IDFRIEND,F.ID2 AS me
     FROM Follows F RIGHT JOIN Users U on F.ID1 = U.ID
     WHERE F.ID2 = $uID AND F.ID1 = (SELECT DISTINCT F1.ID2
         FROM Follows F1
         WHERE F.ID1=F1.ID2 AND F.ID2=F1.ID1)
     ) AS Friends ,
(
SELECT DISTINCT U.ID AS ID,F.ID2 AS ID2
FROM Follows F RIGHT JOIN Users U on F.ID1 = U.ID
WHERE  F.ID2 = (SELECT DISTINCT F1.ID1
    FROM Follows F1
    WHERE F.ID1=F1.ID2 AND F.ID2=F1.ID1)
) AS ALL_FRIENDS
WHERE  Friends.IDFRIEND = ALL_FRIENDS.ID2 and Friends.me<> ALL_FRIENDS.ID) AS NEWTABLE
   ,Users WHERE Users.ID=NEWTABLE.ID AND  Users.name NOT IN (SELECT U.name AS 'Friend Name'
 FROM Follows F RIGHT JOIN Users U on F.ID1 = U.ID
 WHERE F.ID2 = $uID AND F.ID1 = (SELECT DISTINCT F1.ID2
     FROM Follows F1
     WHERE F.ID1=F1.ID2 AND F.ID2=F1.ID1)  )

ORDER BY Users.name ASC";

    $result=sqlsrv_query($conn,$query1);
    $var=0;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
    {
        $Friend_Friend=$row['Friends friends Name'];
        echo "<tr><td>$Friend_Friend</td></tr>";


    }
    echo "</table>";

}

?>



</body>
</html>
