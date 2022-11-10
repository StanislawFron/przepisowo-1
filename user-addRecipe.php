<?php
include_once('database.php');
include_once('phpFunctions.php');

$ingredientsFullData = getFullIngredients();
$recipeIngredients = $ingredientsFullData[1];
$shoppingListIngredients = $ingredientsFullData[0];


$ingredientsValue_1 = [];
$ingredientsValue_2 = [];

    $newIngredients = $_POST["ingredientArea"];
    $newIngredientsExp = explode(chr(10), $newIngredients);
    for($t=0; $t<sizeof($newIngredientsExp);$t++){
        for($r=0; $r<sizeof($recipeIngredients); $r++){
            if(str_contains($newIngredientsExp[$t], $recipeIngredients[$r])){
                $value_1 = str_replace($recipeIngredients[$r],"",$newIngredientsExp[$t]);
                $value_2 = $shoppingListIngredients[$r];
                array_push($ingredientsValue_1, $value_1);
                array_push($ingredientsValue_2, $value_2);
                break;
            };
        }
    }


for($n=1; $n<21; $n++){
    if(isset($ingredientsValue_1[$n-1])){
        $ingredientLength++;
        if($n!=1){
            $ingredientValue = $ingredientValue.",'".$ingredientsValue_2[$n-1]."','".$ingredientsValue_1[$n-1]."'";
        }else{
            $ingredientValue = $ingredientValue."'".$ingredientsValue_2[$n-1]."','".$ingredientsValue_1[$n-1]."'";
        }
    }else{
        $ingredientValue = $ingredientValue.",'empty',''";
    }
}
for($i=1; $i<20; $i++){
    $preparingStep = "preparingStep".$i;
    if(isset($_POST[$preparingStep])){
        if($i!=1){
            $preparingStepValue = $preparingStepValue.",".$_POST[$preparingStep];
        }else{
            $preparingStepValue = $preparingStepValue.$_POST[$preparingStep];
        }
    }
}


    for($p=0; $p<20; $p++){
        $sqlSkl = $sqlSkl.","." skl".($p+1).","." skl".($p+1)."Am";
        $sqlSkl_values = $sqlSkl_values.","." :skl".($p+1).","." :skl".($p+1)."Am";
    }


    $image = $_FILES['image'] ?? null;
    if($image){
        $path = randomString(8);
        mkdir('wp-content/images/'.$path );
        $imagePath = 'wp-content/images/'.$path.'/'.$image['name'];
        move_uploaded_file($image['tmp_name'], $imagePath);
    }

     
    $sql = "INSERT INTO przepisy (nazwa, description, type, time".$sqlSkl.",preparing,image, author, tips)
    VALUES (:recipeName, :recipeDescription, :type, :time".$sqlSkl_values.",:preparing,:image, :author, :tips)";

    try {
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':recipeName', $_POST['recipeName'], PDO::PARAM_STR);
        $statement->bindValue(':recipeDescription', $_POST["recipeDescription"],PDO::PARAM_STR);
        $statement->bindValue(':type', $_POST["type"],PDO::PARAM_INT);
        $statement->bindValue(':time', $_POST["time"],PDO::PARAM_INT);
        for($x=1;$x<=20;$x++){
            $ingredientsValue_11 =  $ingredientsValue_1[$x-1];
            $ingredientsValue_22 = $ingredientsValue_2[$x-1];
            if($ingredientsValue_1[$x-1]==NULL){
                $ingredientsValue_11 = "";
            }
            if($ingredientsValue_2[$x-1]==NULL){
                $ingredientsValue_22 = "empty";
            }
            $statement->bindValue(':skl'.$x, $ingredientsValue_22,PDO::PARAM_STR);
            $statement->bindValue(':skl'.$x."Am", $ingredientsValue_11,PDO::PARAM_STR);
        }
        $statement->bindValue(':preparing', $preparingStepValue,PDO::PARAM_STR);
        $statement->bindValue(':image', $imagePath,PDO::PARAM_STR);
        $statement->bindValue(':author', $_SESSION["result"],PDO::PARAM_STR);
        $statement->bindValue(':tips', $_POST["tipsArea"],PDO::PARAM_STR);
        $statement->execute();
        echo "Dodano Przepis";
    } catch (PDOException $e) {
        $message = $e->getMessage();
        //echo $message;
    }

?>
<div>
    <div class="row w-100 np">
        <?php include_once('user-menu.php'); ?>
        <div class="col-12 col-lg-8 d-flex flex-column items-align-center justify-content-center text-center np">
            <div class="d-flex justify-content-center">
                <h1 class="form-title d-flex justify-content-center">DODAJ PRZEPIS</h1>
            </div>
            <div class="container d-flex flex-column justify-content-center align-items-center text-center mb-5">
                <form method="POST" class="d-flex flex-column justify-content-center align-items-center text-center" enctype="multipart/form-data">
                <input class="form-long-input mb-2" type="text" placeholder="Nazwa przepisu" name="recipeName" />
                <textarea class="form-long-text mb-3" type="text" placeholder="Opis" name="recipeDescription"></textarea>
                <div class="ingredientDiv d-flex flex-column justify-content-center align-items-center text-center mt-4 mb-4">
                    <textarea class="form-long-text mb-3" type="text" placeholder="Wpisz składniki przykład: Ser 40g" name="ingredientArea"></textarea>
                </div>
                <div class="preparingDiv d-flex flex-column justify-content-center align-items-center text-center mb-3">
                    <textarea class="form-long-text-preparing" type="text" placeholder="Wpisz sposób przygotowania" name="preparingStep1"></textarea>
                </div>
                <div class="tipsDiv d-flex flex-column justify-content-center align-items-center text-center mb-3">
                    <textarea class="form-long-text" type="text" placeholder="Dodaj wskazówki" name="tipsArea"></textarea>
                </div>
                <div class="p-2 mb-2 mt-4">
                    <input type="file" name="image"/>
                </div>
                <div class="p-2 mb-2 mt-4">
                    Wybierz rodzaj dania<br>
                        <input class="p-2 m-2" type="radio" name="type" value="1">Brak
                        <input class="p-2 m-2" type="radio" name="type" value="2">Wegetariańskie
                        <input class="p-2 m-2" type="radio" name="type" value="3">Wegańskie
                </div>
                <div>
                    Wybierz czas wykonania<br>
                    <div class="row mt-2 mb-2">
                        <div class="d-flex justify-content-center align-items-center col-md-3 col-12"><input class="mr-2" type="radio" name="time" value="15">15 min</div>
                        <div class="d-flex justify-content-center align-items-center col-md-3 col-12"><input class="mr-2" type="radio" name="time" value="30">30 min</div>
                        <div class="d-flex justify-content-center align-items-center col-md-3 col-12"><input class="mr-2" type="radio" name="time" value="45">45 min</div>
                        <div class="d-flex justify-content-center align-items-center col-md-3 col-12"><input class="mr-2" type="radio" name="time" value="60">60 min</div>
                    </div>
                </div>
                <button class="mt-3">DODAJ PRZEPIS</button>
            </form>
        </div>
    </div>
        <div class="col-12 col-lg-2 d-flex justify-content-center np pt-2">
        INSTRUCTION
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/menuScript.js"?>></script>