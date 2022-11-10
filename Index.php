<!DOCTYPE html>
<html lang="English">

<head><title> The Twitter Corona Statistics Website</title>

</head>





<body style="background-color: lightseagreen">

<h1 style="text-align: center">Welcome to Corona Twitter Website!</h1>

<style>
    img {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
</style>
<p style="text-align: center"> <img src=Twitter-Wordmark-01.jpg width="500" height="500" alt="ProImageblem Uploading " ></p>

<p style="text-align: center">

    <a href="AddFiles.php">Add New Files Here!</a><br>
    <a href="AddNewTweet.php">Add New Tweet Here!</a><br>
    <a href="ShowUserConn.php">Show User Connections Here!</a><br>


</p>
<p style="text-align: center">Find Out What People Are Saying On Twitter: <br>
    <?php

    // Connecting to the database
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
    //echo "connected to DB"; //debug
    // In case of success

    echo "<br>";
    echo "<center>";
    echo "<table border=\"2\">";
    echo "<tr><tr><th colspan='4'>Twitter Statistics:</th></tr></tr>";
    echo "<tr><th>Most Active Tweeter</th><th>Most Active Country</th><th>Ratio of Corona Tweets</th>
<th>Most Frequent Hashtag Word</th></tr>";

    $sql1 = "SELECT NumR.uID AS 'Most Active Tweeter'
FROM (SELECT r.uID, COUNT(*) AS 'NumOfReviews'
      FROM Tweets r
      GROUP BY r.uID) AS NumR
WHERE NOT EXISTS (SELECT *
                  FROM (SELECT r.uID, COUNT(*) AS 'NumOfReviews'
                        FROM Tweets r
                        GROUP BY r.uID) AS NumR2
                  WHERE NumR.NumOfReviews < NumR2.NumOfReviews OR
                      (NumR.NumOfReviews = NumR2.NumOfReviews AND NumR.uID > NumR2.uID));
 ";

    $sql2 = "SELECT NUM1.cName AS 'Most Active Country'
FROM (SELECT r.cName, count(t.tID) AS 'Numtweets'
      FROM Users r LEFT JOIN Tweets t ON r.ID = t.uID
      GROUP BY r.cName) AS NUM1
WHERE NOT EXISTS (SELECT *
                  FROM (SELECT r.cName, count(t.tID) AS 'Numtweets'
                        FROM Users r LEFT JOIN Tweets t ON r.ID = t.uID
                        GROUP BY r.cName) AS NUM2
                  WHERE NUM1.Numtweets < NUM2.Numtweets OR
                      (NUM1.Numtweets = NUM2.Numtweets AND
                       NUM1.cName > NUM2.cName));
 ";

    $sql3 = "SELECT  CAST(1.0*CORONA.Corona_Tweets/count(T.tID) as decimal(10,2))  AS 'Ratio of Corona Tweets'
FROM Tweets T,(SELECT count(DISTINCT T.tID) AS Corona_Tweets
      FROM Words W left join Tweets T on W.tID = T.tID
      where W.content LIKE '%Corona%') AS CORONA
GROUP BY CORONA.Corona_Tweets
 ";

    $sql4 = "SELECT NUM1.content AS 'Most Frequent Hashtag Word'
FROM (SELECT W.content,  count( W.content) AS 'Numhash'
      FROM Words W
      WHERE W.content LIKE '#%'
      GROUP BY W.content
      ) AS NUM1
WHERE NOT EXISTS (SELECT *
                  FROM (SELECT W.content, count( W.content) AS 'Numhash'
                        FROM Words W
                        WHERE W.content LIKE '#%'
                        GROUP BY W.content
                        ) AS NUM2
                  WHERE NUM1.Numhash < NUM2.Numhash OR
                      (NUM1.Numhash = NUM2.Numhash AND
                       NUM1.content > NUM2.content))
GROUP BY NUM1.content
 ";

    $result1 = sqlsrv_query($conn, $sql1);
    $result2 = sqlsrv_query($conn, $sql2);
    $result3 = sqlsrv_query($conn, $sql3);
    $result4 = sqlsrv_query($conn, $sql4);



    while($row = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC))
    {
        echo '<td>'.$row['Most Active Tweeter'].'</td>';
    }
    while($row = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC))
    {
        echo '<td>'.$row['Most Active Country'].'</td>';
    }
    while($row = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC))
    {
        echo '<td>'.(float)$row['Ratio of Corona Tweets'].'</td>';
    }
    while($row = sqlsrv_fetch_array($result4, SQLSRV_FETCH_ASSOC))
    {
        echo '<td>'.$row['Most Frequent Hashtag Word'].'</td>';
    }
    echo "</table>";

    ?>

</body>
</html>