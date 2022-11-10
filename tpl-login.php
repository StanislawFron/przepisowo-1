<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container d-flex justify-content-center">
        <form method="post">
            <input type="text" name="login" /> <br/>
            <input type="password" name="password" /> <br/>
            <input type="submit" value="ZALOGUJ" id="xd"/> <br/>
        <form>
    </div>
</body>
</html>

<?php 
include_once('database.php');
$sql = 'SELECT * FROM users WHERE name=:nazwa AND password=:haslo';
$statement = $pdo->prepare($sql);
$statement->bindValue(':nazwa', $_POST["login"], PDO::PARAM_STR);
$statement->bindValue(':haslo', $_POST["password"], PDO::PARAM_STR);
$statement->execute();
$databaseData = $statement->fetchAll();

if($databaseData>1){
    foreach($databaseData as $row){
        $_SESSION['result'] = $row['name'];
        echo "<script>window.location.reload(true);</script>";
    }
}
?>
