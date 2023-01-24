<?php
include_once('database.php');
    if(isset($_POST['newValue']) && isset($_POST['newValue2'])){
        $sql ='UPDATE listaZakupow SET '.$_POST['newValue2'].' = :data WHERE name =:nazwa';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':data', $_POST['newValue'], PDO::PARAM_STR);
        $statement->bindValue(':nazwa',$_SESSION['result'], PDO::PARAM_STR);
        $statement->execute();
    }

    $sql = 'SELECT skladnik FROM skladniki';
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $ingredientData = $statement->fetchAll();

    if(isset($_POST['allIngredients']) && $_POST['allIngredients'] != 'brak'){
        $postIngredient = $_POST['allIngredients'];
        $sql = 'SELECT '.$_POST['allIngredients'].' FROM listaZakupow WHERE name =:nazwa';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':nazwa',$_SESSION['result'], PDO::PARAM_STR);
            $statement->execute();
            $data = $statement->fetchAll();
            $data = $data[0];
            if($data[$postIngredient] == ""){
                $sql ='UPDATE listaZakupow SET '.$postIngredient.' = 0 WHERE name =:nazwa';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':nazwa',$_SESSION['result'], PDO::PARAM_STR);
                $statement->execute();
            }
    }

    if(isset($_POST['deleteInput'])){
            $sql ='UPDATE listaZakupow SET '.$_POST['deleteInput'].' = "" WHERE name =:nazwa';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':nazwa',$_SESSION['result'], PDO::PARAM_STR);
            $statement->execute();
    }
?>
<div>
    <div class="row w-100 np">
    <?php include_once('user-menu.php'); ?>
        <div class="col-12 col-lg-8 d-flex flex-column items-align-center justify-content-center text-center np">
            <h1>LISTA ZAKUPÓW</h1>
            <div>
                <div class="row w-100 np d-flex justify-content-center">
            <?php
                $sql ='SELECT * FROM listaZakupow WHERE name =:nazwa';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':nazwa', $_SESSION["result"], PDO::PARAM_STR);
                $statement->execute();
                $databaseData = $statement->fetchAll();

                $skladniki= explode(",", getIngredients());
                $ilosc = [];
                $shoppingList = [];

                foreach($databaseData as $row){
                    for($r=0; $r<=100; $r++){
                        if($row[$skladniki[$r]]!=""){
                            array_push($shoppingList,$skladniki[$r]);
                            array_push($shoppingList,$row[$skladniki[$r]]);
                        }
                    }
                }

                echo "<div class='col-12 col-md-8 np'><form method='post' id='updateForm'>";
                for ($i=0; $i < sizeof($shoppingList) ; $i+=2) { 
                    echo '<div class="shoppingListIngredient d-flex">'.'<div class="ingredientName col-5">'.$shoppingList[$i].'</div><div class="ingredientGrammature col-5">'.$shoppingList[$i+1].'</div><div class="actions col-2 d-flex np"><div class="shoppingListIngredientImg shoppingListEdit" style="background-image:url('.get_home_url().'/wp-content/themes/przepisy/assets/img/pencil.svg)"></div><div class="shoppingListIngredientImg shoppingListDelete" style="background-image:url('.get_home_url().'/wp-content/themes/przepisy/assets/img/x-lg.svg)"></div></div></div>';
                }
                echo "</form></div>";

                echo '<form id="deleteForm" method="POST">';
                echo '<input type="hidden" value="" id="deleteInput" name="deleteInput"/>';
                echo "</form>";
            ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-2 d-flex np pt-2 flex-column text-center">
            <h2>AKCJE</h2>
            <form class="mt-2" method="POST">
                Dodaj składnik:
                <select name="allIngredients">
                <option value='brak'>WYBIERZ</option>
                    <?php foreach ($ingredientData as $ingredient){
                       echo '<option value='.$ingredient['skladnik'].'>'.$ingredient['skladnik'].'</option>';
                    }
                    ?>
                </select>
                <button>DODAJ</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/calendar.js"?>></script>
<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/menuScript.js"?>></script>
<script>
    $(".shoppingListIngredient:even").css('background-color', 'lightgray');
    $(".shoppingListGramature:even").css('background-color', 'lightgray');
</script>