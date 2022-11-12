<!DOCTYPE html>
<html lang="English">
<head>
    <title>Add New File</title>
    <link rel="stylesheet" type="text/css" href="styleNew.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <style>
        html, body {
            min-height: 100%;
        }
        body, div, form, input, select, textarea, label, p {
            padding: 0;
            margin: 0;
            outline: none;
            font-family: Roboto, Arial, sans-serif;
            font-size: 14px;
            color: #666;
            line-height: 22px;
        }
        h1 {
            position: absolute;
            margin: 0;
            font-size: 34px;
            color: #fff;
            z-index: 2;
            line-height: 83px;
        }
        textarea {
            width: calc(100% - 12px);
            padding: 5px;
        }


        form {
            width: 100%;
            padding: 20px;
            border-radius: 6px;
            background: #fff;
            box-shadow: 0 0 8px  #669999;
        }
        .banner {
            position: relative;
            height: 300px;
            background-image: url("https://media.netflix.com/dist/img/meta-image-netflix-symbol-black.png");
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .banner::after {
            content: "";
            background-color: rgba(0, 0, 0, 0.2);
            position: absolute;
            width: 100%;
            height: 100%;
        }
        input, select, textarea {
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input {
            width: calc(100% - 10px);
            padding: 5px;
        }
        input[type="date"] {
            padding: 4px 5px;
        }
        textarea {
            width: calc(100% - 12px);
            padding: 5px;
        }
        .item:hover p, .item:hover i, .question:hover p, .question label:hover, input:hover::placeholder {
            color:  #669999;
        }
        .item input:hover, .item select:hover, .item textarea:hover {
            border: 1px solid transparent;
            box-shadow: 0 0 3px 0  #669999;
            color: #669999;
        }
        .item {
            position: relative;
            margin: 10px 0;
        }
        .item span {
            color: red;
        }

        input[type=radio], input[type=checkbox]  {
            display: none;
        }

        .question span {
            margin-left: 30px;
        }
        .question-answer label {
            display: block;
        }

        input[type=radio]:checked + label:after {
            opacity: 1;
        }

        .btn-block {
            margin-top: 10px;
            text-align: center;
        }
        button {
            width: 150px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background:  #669999;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background:  #a3c2c2;
        }

        .name-item input, .name-item div {
            width: calc(50% - 20px);
        }
        .name-item div input {
            width:97%;}
        .name-item div label {
            display:block;
            padding-bottom:5px;
        }
        }
    </style>
</head>

<body>
<div class="textbox">

    <div class="banner">
        <h1>Add File</h1>
    </div>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>"
          method="POST" enctype="multipart/form-data">
        <h2 style="text-align: center">Please add a Users file</h2>
        <input name="csv" type="file" id="csv" />
        <input type="submit" name="submit_Users" value="submit" />
    </form>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>"
        method="POST" enctype="multipart/form-data">
        <h2 style="text-align: center">Please add a Follows file</h2>
        <input name="csv" type="file" id="csv" />
        <input type="submit" name="submit_Follows" value="submit" />

    </form>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>"
        method="POST" enctype="multipart/form-data">
        <h2 style="text-align: center">Please add a Tweets file</h2>
        <input name="csv" type="file" id="csv" />
        <input type="submit" name="submit_Tweets" value="submit" />
    </form>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>"
        method="POST" enctype="multipart/form-data">
        <h2 style="text-align: center">Please add a Words file</h2>
        <input name="csv" type="file" id="csv" />
        <input type="submit" name="submit_Words" value="submit" />
    </form>

        <div class="item">

            <div class="btn-block">

                <p> <a href="Index.php">Back to main page</a> </p>

            </div>
        </div>
</div>


<?php
    // Connecting to the database - Users

if (isset($_POST["submit_Users"])) {
    $server = "";
    $user = "";
    $pass = "";
    $database = "";
    $c = array("Database" => $database, "UID" => $user, "PWD" => $pass);
    sqlsrv_configure('WarningsReturnAsErrors', 0);
    $conn = sqlsrv_connect($server, $c);

    if ($conn === false) {
        echo "error";
        die(print_r(sqlsrv_errors(), true));
    }

    $file = $_FILES['csv']['tmp_name'];
    echo "<br>";

    $row = 0;
    $count_total = 0;
    $count_unsuccessful = 0;

    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            if ($row == 0) {
                $row++;
                continue;
            }

            $sql = "INSERT INTO Users(ID,name,cName) 
                        VALUES ( '".addslashes($data[0])."', 
        '".addslashes($data[1])."', 
        '".addslashes($data[2])."' )";

            $result = sqlsrv_query($conn, $sql);
            if ($result === false) {
                $count_unsuccessful++;
                continue;
            }
            $count_total++;
        }
        echo "Import completed. Thank you! Total data successfully entered: ";
        echo $count_total;
        echo "<br>Total data unsuccessfully entered: ";
        echo $count_unsuccessful;
        fclose($handle);
    }
}

// Connecting to the database - Follows
if (isset($_POST["submit_Follows"])) {
    $server = "tcp:techniondbcourse01.database.windows.net,1433";
    $user = "van0ari";
    $pass = "Qwerty12!";
    $database = "van0ari";
    $c = array("Database" => $database, "UID" => $user, "PWD" => $pass);
    sqlsrv_configure('WarningsReturnAsErrors', 0);
    $conn = sqlsrv_connect($server, $c);

    if ($conn === false) {
        echo "error";
        die(print_r(sqlsrv_errors(), true));
    }

    $file = $_FILES['csv']['tmp_name'];
    echo "<br>";
    $row = 0;
    $count_total = 0;
    $count_unsuccessful = 0;

    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            if ($row == 0) {
                $row++;
                continue;
            }



            $sql = "INSERT INTO Follows(ID1,ID2) 
                        VALUES ( '".addslashes($data[0])."', 
        '".addslashes($data[1])."' )";

            $result = sqlsrv_query($conn, $sql);
            if ($result === false) {
                $count_unsuccessful++;
                continue;
            }
            $count_total++;
        }
        echo "Import completed. Thank you! Total data successfully entered: ";
        echo $count_total;
        echo "<br> Total data unsuccessfully entered: ";
        echo $count_unsuccessful;
        fclose($handle);
    }
}

// Connecting to the database - Tweets
if (isset($_POST["submit_Tweets"])) {
    $server = "";
    $user = "";
    $pass = "";
    $database = "";
    $c = array("Database" => $database, "UID" => $user, "PWD" => $pass);
    sqlsrv_configure('WarningsReturnAsErrors', 0);
    $conn = sqlsrv_connect($server, $c);

    if ($conn === false) {
        echo "error";
        die(print_r(sqlsrv_errors(), true));
    }

    $file = $_FILES['csv']['tmp_name'];
    echo "<br>";
    $row = 0;
    $count_total = 0;
    $count_unsuccessful = 0;

    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            if ($row == 0) {
                $row++;
                continue;
            }
            $data[0] =  ltrim($data[0], '0');


            $sql = "INSERT INTO Tweets(tID,uID,time) 
                            VALUES ( '".addslashes($data[0])."', 
        '".addslashes($data[1])."', 
        '".addslashes($data[2])."' )";

            $result = sqlsrv_query($conn, $sql);
            if ($result === false) {
                $count_unsuccessful++;
                continue;
            }
            $count_total++;
        }
        echo "Import completed. Thank you! Total data successfully entered: ";
        echo $count_total;
        echo "<br>Total data unsuccessfully entered: ";
        echo $count_unsuccessful;
        fclose($handle);
    }
}


// Connecting to the database - Tweets
if (isset($_POST["submit_Words"])) {
    $server = "";
    $user = "";
    $pass = "";
    $database = "";
    $c = array("Database" => $database, "UID" => $user, "PWD" => $pass);
    sqlsrv_configure('WarningsReturnAsErrors', 0);
    $conn = sqlsrv_connect($server, $c);

    if ($conn === false) {
        echo "error";
        die(print_r(sqlsrv_errors(), true));
    }

    $file = $_FILES['csv']['tmp_name'];
    echo "<br>";
    $row = 0;
    $count_total = 0;
    $count_unsuccessful = 0;

    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            if ($row == 0) {
                $row++;
                continue;
            }
            $data[0] =  ltrim($data[0], '0');


            $sql = "INSERT INTO Words(tID,idx,content) 
                            VALUES (  '".addslashes($data[0])."', 
        '".addslashes($data[1])."', 
        '".addslashes($data[2])."'  )";

            $result = sqlsrv_query($conn, $sql);
            if ($result === false) {
                $count_unsuccessful++;
                continue;
            }
            $count_total++;
        }
        echo "Import completed. Thank you! Total data successfully entered: ";
        echo $count_total;
        echo "<br>Total data unsuccessfully entered: ";
        echo $count_unsuccessful;
        fclose($handle);
    }
}


?>
</body>
</html>
