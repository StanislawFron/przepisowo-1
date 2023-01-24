<?php
$displayedYear = '2022';
$displayedMonth = $_GET['month'];
$calendarMonth = $displayedMonth;
$mode = 'PL';
$monthPL = ["styczeń", "luty", "marzec", "kwiecień", "maj", "czerwiec", "lipiec", "sierpień", "wrzesień", "październik", "listopad", "grudzień"];
$monthENG = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
$dayCounter = 0;

$fd = array_search(date('M', mktime(0, 0, 0, $displayedMonth, 10)),$monthENG);

$firstDay = date('N', strtotime($displayedYear.'-'.$displayedMonth.'-01'));
$actualMonthDayNumber = cal_days_in_month(CAL_GREGORIAN, $displayedMonth, 2022);
if($displayedMonth!=1){
    $previousMonthDayNumber = cal_days_in_month(CAL_GREGORIAN, $displayedMonth-1, 2022);
}else{
    $previousMonthDayNumber = cal_days_in_month(CAL_GREGORIAN, 12, 2021);
}
function translateDays($a){
    $b = date('l',mktime(0, 0, 0, date('m'), 5+$a));
    $daysPL = ["Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela"];
    $daysENG= ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    echo $daysPL[array_search($b,$daysENG)];
}
?>
<div>
    <div class="row w-100 np">
    <?php include_once('user-menu.php'); ?>
    <div class="col-12 col-lg-8 d-flex flex-column items-align-center justify-content-center text-center np">
        <h1>KALENDARZ</h1>
        <div id="calendarNav" class="d-flex justify-content-around"><button><a href='user/calendar?month=<?= $_GET['month']-1 ?>'><</a></button><p><?=$monthPL[$fd];?></p><button><a href='user/calendar?month=<?= $_GET['month']+1 ?>'>></a></button></div>
        <div id="calendarNavFixed" class="justify-content-around"><button><a href='user/calendar?month=<?= $_GET['month']-1 ?>'><</a></button><p><?=$monthPL[$fd];?></p><button><a href='user/calendar?month=<?= $_GET['month']+1 ?>'>></a></button></div>
        <div id="calendar">
            <?php
            $calendarRecipes = [];

            include('database.php');
            $sql ='SELECT * FROM kalendarz WHERE user =:result';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':result', $_SESSION["result"], PDO::PARAM_STR);
            $statement->execute();
            $databaseData = $statement->fetchAll();

            foreach($databaseData as $row){
                $day = ltrim(substr($row['data'],8, 2),"0");
                $hour = substr($row['data'],10, 15);
                $data = substr($row['data'],0, 8).$day.$hour.'<br>';
				array_push($calendarRecipes, $data, $row['przepis']);
            }

			echo '<div class="row d-flex justify-content-center w-100 np">';
            for($a=0; $a<=5; $a++){
                    for($b=1+$a*7; $b<=7+$a*7; $b++){
                        if($previousMonthDayNumber-$firstDay+$b+1<$previousMonthDayNumber+1){
                            if($displayedMonth-1<10){ 
                                $calendarMonth =  '0'.($displayedMonth-1);
                            }
                            echo '<div class="day calendarUnactive m-2 col-8 col-md-auto d-flex items-align-center text-center flex-column">';
                            echo translateDays($b)." ".$previousMonthDayNumber-$firstDay+$b+1;
                            $currentCalendarDate = $displayedYear."-".$calendarMonth.'-'.($previousMonthDayNumber-$firstDay+$b+1);
                            for($r=0; $r<sizeof($calendarRecipes); $r+=2){
                                if($currentCalendarDate==substr($calendarRecipes[$r],0, 10)){
                                    echo '<div class="calendarRecipe">'.$calendarRecipes[$r+1].'</div>';
                                }
                            }
                            echo '</div>';
                        }
                        else if($dayCounter>=$actualMonthDayNumber){
                            if($displayedMonth+1<10){ 
                                $calendarMonth =  '0'.($displayedMonth+1);
                            }
                            echo '<div class="day calendarUnactive m-2 col-8 col-md-auto d-flex items-align-center text-center flex-column">';
                            echo translateDays($b)." ".$dayCounter-$actualMonthDayNumber+1;
                            $currentCalendarDate = $displayedYear."-".$calendarMonth.'-'.($dayCounter-$actualMonthDayNumber+1);
                            for($r=0; $r<sizeof($calendarRecipes); $r+=2){
                                if($currentCalendarDate==substr($calendarRecipes[$r],0, 10)){
                                    echo '<div class="calendarRecipe">'.$calendarRecipes[$r+1].'</div>';
                                }
                            }
                            echo '</div>';
                            $dayCounter++;
                        }
                        else{
                            if($displayedMonth<10){ 
                                $calendarMonth =  '0'.($displayedMonth);
                            }
                            echo '<div class="day calendarActive m-2 col-8 col-md-auto d-flex items-align-center text-center flex-column">';
                            echo translateDays($b)." ".$dayCounter+1;
                            $currentCalendarDate = $displayedYear."-".$calendarMonth.'-'.($dayCounter+1);
                            for($r=0; $r<sizeof($calendarRecipes); $r+=2){
                                if($currentCalendarDate==trim(substr($calendarRecipes[$r],0, 10)," ")){
                                    echo '<div class="calendarRecipe">'.$calendarRecipes[$r+1].'</div>';
                                }
                            }
                            echo '</div>';
                            $dayCounter++;
                        }
                    }
                };
                echo '</div>';
            ?>
        </div>
    </div>
        <div class="col-12 col-lg-2 d-flex justify-content-center np pt-2">
            <h2>LISTA ZAKUPÓW</h2>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/calendar.js"?>></script>
<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/menuScript.js"?>></script>