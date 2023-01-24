<?php
    include('database.php');
    $sql ='SELECT skladnik,nazwy FROM skladniki';
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $databaseData = $statement->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        
        $sql ='INSERT INTO skladniki (skladnik,nazwy)  VALUES (:skladnik,:nazwy)';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':skladnik', $_POST["newIngredientName"],PDO::PARAM_STR);
        $statement->bindValue(':nazwy', $_POST["newIngredientAllNames"],PDO::PARAM_STR);
        $statement->execute();
        
        $sql ='ALTER TABLE listaZakupow ADD `'.$_POST["newIngredientName"].'` VARCHAR(45) NOT NULL';
        $statement = $pdo->prepare($sql);
        $statement->execute();


        $sql ='SELECT * FROM skladniki';
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $databaseData = $statement->fetchAll();

        $newIngredientNumber = sizeof($databaseData);
        
        $sql ='ALTER TABLE przepisy ADD skl'.$newIngredientNumber.' text NOT NULL DEFAULT "empty"';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $sql ='ALTER TABLE przepisy ADD skl'.$newIngredientNumber.'Am text NOT NULL DEFAULT ""';
        $statement = $pdo->prepare($sql);
        $statement->execute();
        
        echo '<script>location.replace("http://przepisowo.epizy.com/user/dodaj-skladnik/");</script>';
    }
?>
<div>
    <div class="row w-100 np">
        <?php include_once('user-menu.php'); ?>
        <div class="col-12 col-lg-8 d-flex flex-column items-align-center justify-content-center text-center np">
            <div class="d-flex justify-content-center">
                <h1 class="form-title d-flex justify-content-center">SKŁADNIKI</h1>
            </div>
            <div class="d-flex justify-content-center">
                <form class="mb-4" method="POST">
                    Nazwa<br>
                    <input type="text" class="mb-2" placeholder="ser" name="newIngredientName"/><br>
                    Nazwy podobne(wraz z główną nazwą)
                    <textarea class="mb-2" placeholder="ser,sera" name="newIngredientAllNames"></textarea><br>
                    <input type="submit" class="mb-2" value="DODAJ SKŁADNIK" />
                </form>
            </div>
            <div class="d-flex flex-column">
                <?php
                    foreach($databaseData as $row){
                        echo '<div class="d-flex justify-content-center border mb-2">';
                        echo '<h2 class="col-3">'.$row['skladnik'].'</h2><div class="col-9">'.$row['nazwy'].'</div>';
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/menuScript.js"?>></script>