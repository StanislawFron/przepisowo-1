<div>
    <div class="row w-100 np">
    <?php include_once('user-menu.php'); ?>
        <div class="col-12 col-lg-8 d-flex flex-column items-align-center justify-content-center text-center np">
            <div class="d-flex justify-content-center">
                    <h1 class="form-title d-flex justify-content-center">MOJE PRZEPISY</h1>
            </div>
            <div class="d-flex flex-column">
                <form action="/user/editRecipe" method="get">
                    <input id="editInput" type="hidden" name="recipeName" value="Kanapka"/>
                    <input id="editOrDelete" type="hidden" name="editOrDelete" value=""/>
                <?php 
                    include_once('database.php');
                    $sql = "SELECT * FROM przepisy WHERE author = :author";
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':author', $_SESSION['result'], PDO::PARAM_STR);
                    $statement->execute();
                    $databaseData = $statement->fetchAll();

                    foreach ($databaseData as $row){
                        $ingredients = "";
                        for($x=1; $x < 20; $x++){
                            if($row['skl'.$x] != 'empty'){
                                if($x>1){
                                    $ingredients .= ", ";
                                }
                                $ingredients .= $row['skl'.$x];
                            }
                        }
                        echo "<div class='myRecipesResult w-90 md-4 p-2 m-2 position-relative d-flex justify-content-center'><div class='row d-flex'><div class='col-12 myRecipesData'><p class='ingredientName np'>".$row["nazwa"] . "</p><p>Składniki : ".$ingredients."</p><button class='myRecipesEditBtn'>EDYTUJ</button><button class='deleteButton'>USUŃ</button></div></div></div>";
                    }
                ?>
                </form>
            </div>
        </div>
        <div class="col-12 col-lg-2 d-flex justify-content-center np pt-2">

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/myRecipes.js"?>></script>
<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/menuScript.js"?>></script>