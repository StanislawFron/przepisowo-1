<?php
    include('database.php');

    if($_GET['editOrDelete']=='delete'){
        $sql ='DELETE FROM przepisy WHERE nazwa = :name';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':name', $_GET['recipeName'], PDO::PARAM_STR);
        $statement->execute();
        echo '<script>location.replace("http://przepisowo.epizy.com/user/moje-przepisy/");</script>';
    }

    $sql ='SELECT * FROM przepisy WHERE nazwa =:name';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':name', $_GET['recipeName'], PDO::PARAM_STR);
    $statement->execute();
    $databaseData = $statement->fetchAll();

    if($databaseData==[]){
        echo '<script>location.replace("http://przepisowo.epizy.com/");</script>';
    }

    foreach ($databaseData as $row){
        $name = $row['nazwa'];
        $description = $row['description'];
        $ingredients = "";
        for($x = 1; $x < 20; $x++){
            if($row['skl'.$x] != 'empty'){
                
                $ingredients .=  $row['skl'.$x]." ".str_replace(' ', '', $row['skl'.$x."Am"]);
            }
        }
        $preparing = $row['preparing'];
        $img = $row['image'];
        $type = $row['type'];
        $time = $row['time'];
        $tips = $row['tips'];
    }

    for($p=0; $p<20; $p++){
        $sqlSkl = $sqlSkl.","." skl".($p+1)." = "." :skl".($p+1).","." skl".($p+1)."Am = "." :skl".($p+1).'Am';
    }

    $ingredientsFullData = getFullIngredients();

    $recipeIngredients = array_reverse($ingredientsFullData[1]);
    $shoppingListIngredients = array_reverse($ingredientsFullData[0]);

    
    $sql = "UPDATE przepisy SET nazwa = :recipeName, description = :recipeDescription, type = :type, time = :time$sqlSkl, preparing = :preparing, image = :image, tips=:tips WHERE nazwa=:name";

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


    $image = $_FILES['image'] ?? null;
    $imagePath = $img;
    if($image){
            $path = randomString(8);
            mkdir('wp-content/images/'.$path );
            $imagePath = 'wp-content/images/'.$path.'/'.$image['name'];
            move_uploaded_file($image['tmp_name'], $imagePath);
    }
    if($image['name']==""){
        $imagePath = $img;
    }


    if(sizeof($_POST)>0){
        try {
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':recipeName', $_POST['recipeName'], PDO::PARAM_STR);
            $statement->bindValue(':recipeDescription', $_POST["recipeDescription"],PDO::PARAM_STR);
            $statement->bindValue(':type', $_POST["type"],PDO::PARAM_INT);
            $statement->bindValue(':time', $_POST["time"],PDO::PARAM_INT);
            for($x=1;$x<=20;$x++){
                if(isset($ingredientsValue_2[$x-1])){
                    $statement->bindValue(':skl'.$x, $ingredientsValue_2[$x-1],PDO::PARAM_STR);
                    $statement->bindValue(':skl'.$x.'Am', $ingredientsValue_1[$x-1],PDO::PARAM_STR);
                }
                else{
                    $statement->bindValue(':skl'.$x, 'empty',PDO::PARAM_STR);
                    $statement->bindValue(':skl'.$x.'Am', "",PDO::PARAM_STR);
                }
            }
            $statement->bindValue(':preparing', $_POST['preparingStep1'],PDO::PARAM_STR);
            $statement->bindValue(':name', $_GET['recipeName'],PDO::PARAM_STR);
            $statement->bindValue(':image', $imagePath,PDO::PARAM_STR);
            $statement->bindValue(':tips', $_POST['tipsArea'],PDO::PARAM_STR);
            $statement->execute();
            echo '<script>location.replace("http://przepisowo.epizy.com/user/moje-przepisy/");</script>';
            echo "Updated Przepis";
        } catch (PDOException $e) {
            $message = $e->getMessage();
        }
    }

?>
<div>
    <div class="row w-100 np">
        <?php include_once('user-menu.php'); ?>
        <div class="col-12 col-lg-8 d-flex flex-column items-align-center justify-content-center text-center np">
            <div class="d-flex justify-content-center">
                <p class="form-title d-flex justify-content-center">EDYTUJ PRZEPIS</p>
            </div>
            <div class="container d-flex flex-column justify-content-center align-items-center text-center mb-5">
                <form method="POST" class="d-flex flex-column justify-content-center align-items-center text-center col-12 np" enctype="multipart/form-data">
                <input class="form-long-input mb-2" type="text" placeholder="Nazwa przepisu" name="recipeName" value="<?= $name ?>"/>
                <textarea class="form-long-text mb-3" type="text" placeholder="Opis" name="recipeDescription"><?= $description ?></textarea>
                <div class="ingredientDiv d-flex flex-column justify-content-center align-items-center text-center mt-4 mb-4">
                    <textarea class="form-long-text mb-3" type="text" placeholder="Wpisz składniki przykład: Ser 40g" name="ingredientArea"><?= $ingredients ?></textarea>
                </div>
                <div class="preparingDiv d-flex flex-column justify-content-center align-items-center text-center mb-3">
                    <textarea class="form-long-text-preparing w-90" type="text" placeholder="Wpisz sposób przygotowania" name="preparingStep1"><?= $preparing ?></textarea>
                </div>
                <div class="tipsDiv d-flex flex-column justify-content-center align-items-center text-center mb-3">
                    <textarea class="form-long-text" type="text" placeholder="Dodaj wskazówki" name="tipsArea"><?= $tips ?></textarea>
                </div>
                <div class="p-2 mb-2 mt-4">
                    <input type="file" name="image"/>
                    <?php 
                        if(isset($img) && $img != NULL){
                            echo '<img src='.get_home_url()."/".$img.' class="updateImg"/>';
                        }
                    ?>
                </div>
                <div class="p-2 mb-2 mt-4">
                    Wybierz rodzaj dania<br>
                        <input class="p-2 m-2 type" type="radio" name="type" value="1">Brak
                        <input class="p-2 m-2 type" type="radio" name="type" value="2">Wegetariańskie
                        <input class="p-2 m-2 type" type="radio" name="type" value="3">Wegańskie
                </div>
                <div>
                    Wybierz czas wykonania<br>
                    <div class="row mt-2 mb-2">
                        <div class="d-flex justify-content-center align-items-center col-md-3 col-12"><input class="mr-2 time" type="radio" name="time" value="15">15 min</div>
                        <div class="d-flex justify-content-center align-items-center col-md-3 col-12"><input class="mr-2 time" type="radio" name="time" value="30">30 min</div>
                        <div class="d-flex justify-content-center align-items-center col-md-3 col-12"><input class="mr-2 time" type="radio" name="time" value="45">45 min</div>
                        <div class="d-flex justify-content-center align-items-center col-md-3 col-12"><input class="mr-2 time" type="radio" name="time" value="60">60 min</div>
                    </div>
                </div>
                <button id="editRecipeSaveRecipeButton" class="mt-3">ZAPISZ PRZEPIS</button>
            </form>
        </div>
    </div>
        <div class="col-12 col-lg-2 d-flex justify-content-center np pt-2">
        INSTRUCTION
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script>
    let data = <?= $type; ?>;
    let timeData = <?= $time; ?>;

    let type = $('.type');
    for (let i = 0; i < type.length; i++){
        if($(type[i]).val() == data){
            $(type[i]).prop('checked', true);
        }
    }

    let time = $('.time');
    for (let i = 0; i < time.length; i++){
        if($(time[i]).val() == timeData ){
            $(time[i]).prop('checked', true);
        }
    }

    $("#editRecipeSaveRecipeButton").on('click', () => {
        
    })
   

</script>
<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/menuScript.js"?>></script>