<?php 
function randomString($n){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for($i=0; $i<$n; $i++){
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}

function getIngredients(){
    include('database.php');
		$sql ='SELECT skladnik FROM skladniki';
		$statement = $pdo->prepare($sql);
		$statement->execute();
		$databaseData = $statement->fetchAll();
		$data = "";
		$x = 0;
             foreach ($databaseData as $row){
                if($x==0){
                    $data .= $row['skladnik'];
                }else{
                    $data .= ','.$row['skladnik'];
                }
                $x++;
             }
        return $data;
}

function getIngredientsJS(){
    include('database.php');
		$sql ='SELECT skladnik FROM skladniki';
		$statement = $pdo->prepare($sql);
		$statement->execute();
		$databaseData = $statement->fetchAll();
		$data = "";
		$x = 0;
            foreach ($databaseData as $row){
                if($x==0){
                    $data .= '"'.$row['skladnik'].'"';
                }else{
                    $data .= ', "'.$row['skladnik'].'"';
                }
                $x++;
             }
        return $data;
}

function getFullIngredients(){
    include('database.php');
		$sql ='SELECT nazwy FROM skladniki';
		$statement = $pdo->prepare($sql);
		$statement->execute();
		$databaseData = $statement->fetchAll();
        $ingredients = [];
        $names = [];
		foreach ($databaseData as $row){
            $data = explode(",", $row['nazwy']);
            for ($i=0; $i < sizeof($data) ; $i++) { 
                array_push($ingredients, $data[0]);
                array_push($names, $data[$i]);
            }
		}
        return [$ingredients, $names];
}
?>