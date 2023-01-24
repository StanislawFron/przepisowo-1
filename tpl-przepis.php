<?php
include('database.php');
    $sql = "SELECT * FROM przepisy WHERE nazwa=:name";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':name', $_GET['id'], PDO::PARAM_STR);
    $statement->execute();
    $databaseData = $statement->fetchAll();
    
    if($databaseData==[]){
        echo '<script>location.replace("http://przepisowo.epizy.com/");</script>';
    }

    foreach($databaseData as $row){
        $recipeName = $row['nazwa'];
        $recipeDescription = $row['description'];
        $recipeType = $row['type'];
        $recipeTime = $row['time'];
        $recipePreparing = nl2br($row['preparing']);
        $recipeImage = $row['image'];
        $tips = nl2br($row['tips']);
    }

if(isset($_POST["recipeData"])){
    for($r=1; $r<=20; $r++){
        if($r==20){
            $g = $g."skl".$r.","."skl".$r."Am";
        }else{
            $g = $g."skl".$r.","."skl".$r."Am,";
        }
    }

    $shoppingListIngredients = [];
    $shoppingListAmount = [];
    $amountGrammature = [];
    $databaseAmounts = [];
    $numberOfShoppingList = 0;

    $sql = "SELECT ".$g." FROM przepisy WHERE nazwa=:name";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':name', $_GET['id'], PDO::PARAM_STR);
    $statement->execute();
    $databaseData = $statement->fetchAll();

    foreach ($databaseData as $row){
        for($r=1; $r<=20; $r++){
            if($row['skl'.$r]!="empty"){
                $numberOfShoppingList++;
                array_push($shoppingListIngredients, $row['skl'.$r]);
                array_push($shoppingListAmount, $row['skl'.$r."Am"]);
            }
        }
    }
    
    for ($i=0; $i <= sizeof($shoppingListAmount); $i++) { 
        for($o=0; $o < strlen($shoppingListAmount[$i]); $o++){
            if($shoppingListAmount[$i][$o]!= '0' && $shoppingListAmount[$i][$o] != '1' && $shoppingListAmount[$i][$o] != '2' && $shoppingListAmount[$i][$o] != '3' &&   $shoppingListAmount[$i][$o] != '4' && $shoppingListAmount[$i][$o] != '5' && $shoppingListAmount[$i][$o] != '6' && $shoppingListAmount[$i][$o] != '7' && $shoppingListAmount[$i][$o] != '8' && $shoppingListAmount[$i][$o] != '9' && $shoppingListAmount[$i][$o] != '.' && $shoppingListAmount[$i][$o] != ' '){
                array_push($amountGrammature, substr($shoppingListAmount[$i], $o, 20));
                break;
            }
            if(strlen($shoppingListAmount[$i])==1){
                array_push($amountGrammature, "");
                break;
            }
        }
    }

    $sql = "SELECT * FROM listaZakupow WHERE name=:name";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':name',$_SESSION['result'], PDO::PARAM_STR);
    $statement->execute();
    $databaseData = $statement->fetchAll();

    $t=0;
    foreach ($databaseData as $row){
        for ($i=0; $i < sizeof($shoppingListIngredients); $i++) { 
            array_push($databaseAmounts, $row[strtolower($shoppingListIngredients[$i])]);
        } 
    }

    for ($i=0; $i <sizeof($shoppingListIngredients) ; $i++) { 
        $grammature = $amountGrammature[$i];
        $value = $shoppingListAmount[$i];

        if($grammature == ' łyżki'){
            $grammature = ' g';
            $value = $value * 15;
        }
        else if($grammature == ' łyżeczki' || $grammature == 'łyżeczki'){
            $grammature = ' g';
            $value = $value * 5;
        }
        else if($grammature == ' dag'){
            $grammature = ' g';
            $value = $value * 10;
        }
        else if($grammature == ''){
            $grammature = '';
        }

        if($i==0){
            $k = $k."`".$shoppingListIngredients[$i]."`="."'".($value+$databaseAmounts[$i].$grammature)."'";
        }else{
            $k = $k.',`'.$shoppingListIngredients[$i]."`="."'".($value+$databaseAmounts[$i].$grammature)."'";
        }
    }


        $sql = "UPDATE listaZakupow SET ".$k." WHERE name=:name";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':name',$_SESSION['result'], PDO::PARAM_STR);
        $statement->execute();
        echo "Dodano przepis";

        $sql = "INSERT INTO kalendarz (user, data, przepis) VALUES (:result, :recipeData, :id)";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':result', $_SESSION['result'], PDO::PARAM_STR);
        $statement->bindValue(':recipeData', $_POST["recipeData"], PDO::PARAM_STR);
        $statement->bindValue(':id', $_GET['id'], PDO::PARAM_STR);
        $statement->execute();
        echo " do kalendarza";

}

if($recipeImage==null){
    $url = get_home_url().'/wp-content/themes/przepisy/assets/img/goulash-ga36df7a9d_1920.jpg';
}else{
    $url = get_home_url().'/'.$recipeImage;
}
?>

<style>
        .recipe-type-div-img{
            background-image: url("<?php echo get_home_url()?>/wp-content/themes/przepisy/assets/img/bar-chart-<?= $recipeType ?>.svg");
        }
        .recipe-img{
            background-image: url("<?php echo $url ?>");
        }
        </style>
    <div class="container">
        <div class="recipe-info">
            <div class="row d-flex justify-content-around">
                <div class="recipe-name-div text-center d-flex justify-content-center align-items-center p-2 col-md-3 col-sm-12">
                    <h1><?= $recipeName ?></h1>
                </div>
                <div class="recipe-time-div text-center d-flex justify-content-center align-items-center p-2 col-md-3 col-sm-12">
                    <?= $recipeTime ?> min&nbsp;<div class="recipe-time-div-img ml-2"></div>
                </div>
                <div class="recipe-type-div text-center d-flex justify-content-center align-items-center p-2 col-md-3 col-sm-12">
                    <?php
                    if($recipeType!=1){
                        echo 'Typ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="recipe-type-div-img"></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row mt-5  d-flex justify-content-center">
        <div class="recipe-img col-lg-6 col-md-12 mb-5"></div>
            <div class="recipe-ingredients col-lg-6 col-md-12 d-inline-flex justify-content-center">
                <div class="mb-5 col-12">
                    <div class="d-flex justify-content-center mb-2">Składniki:</div>
                    <?php 
                    $sql = "SELECT * FROM przepisy WHERE nazwa=:name";
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':name', $_GET['id'], PDO::PARAM_STR);
                    $statement->execute();
                    $databaseData = $statement->fetchAll();

                    foreach($databaseData as $row){
                        for($x=9; $x<22; $x+=2){
                            if($row['skl'+$x] != "empty"){
                                echo "<div class='d-flex'><div class='mr-2 col-6 d-flex justify-content-center'>".$row['skl'+$x]."</div><div class='col-6 d-flex justify-content-center'>".$row['skl'+($x+1)+"Am"]."</div></div>";
                            };
                        }
                    } ?>
                </div>
            </div>
        </div>
        <div class="recipe-preparing d-flex justify-content-center mb-5">
        <?= $recipePreparing ?>
        </div>
        <div class="recipe-tips d-flex justify-content-center mb-5">
        <?= $tips ?>
        </div>
        <?php 
        if(isset($_SESSION['result'])){
            echo '<div class="d-flex flex-column"><form method="POST"><input type="datetime-local" name="recipeData"/><button>Dodaj</button></div>';
        }
        ?>
    </div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/menuScript.js"?>></script>