<!-- hero-section::begin -->
	<section>
		<div class="hero-image position-relative container-fluid d-flex align-middle align-items-center justify-content-center mb-3">
			<div class="overlay position-absolute"></div>
			<div class="hero-image-text container-fluid d-flex align-middle align-items-center justify-content-center text-center">
				<h1>Czas znaleźć odpowiedni przepis</h1>
			</div>
		</div>
	</section>
	<!-- hero-section::end -->

	<form id="testForm" action="/przepisy/przepis" autocomplete="off">
        <input id="testFormInput" type="text" hidden name="id" value="1" autocomplete="off">
    </form>

	<!-- search-section::begin -->
	<section class="serach-section container-fluid np">
	<div class="row np d-flex justify-content-center">
	<button id="hideShowBtn" class="d-md-none mt-3">SCHOWAJ</button>
		<div class="search-form col-md-2 col-sm-12 np">
			<form id="ingredientsForm" method="POST">
				<div class="row d-flex justify-content-center np">
					<input class="form-search-input col-10 mt-3" id="form-search-input" type="search" placeholder="Znajdź produkt" name="fraza" aria-label="Search">
				</div>
				<div class="row d-flex justify-content-center w-100 np">
					<button id="checkAll" class="smallBtn col-lg-3 col-md-7 col-sm-3 col-3 d-md-flex justify-content-center">Zaznacz wszystko</button>
					<button id="uncheckAll" class="smallBtn col-lg-3 col-md-7 col-sm-3 col-3 d-md-flex justify-content-center">Odznacz wszystko</button>
					<button id="exceptAll" class="smallBtn col-lg-3 col-md-7 col-sm-3 col-3 d-md-flex justify-content-center">Wyklucz wszystko</button>
				</div>
				<div class="ingredients p-4 p-md-0 mt-3"></div>
			</form>
		</div>
		<div class="search-result col-md-6 col-sm-12 p-1 p-md-0 mb-3">
			<div class="row d-flex justify-content-center np">
			<input class="form-result-input col-md-6 col-sm-11 mt-3" id="form-result-input" type="search" placeholder="Wpisz poszukiwany przepis" name="fraza" aria-label="Search"><button class="form-search-btn btn btn-outline-secondary col-2 mt-3 np" type="submit" onclick="$('#ingredientsForm').submit()">Szukaj</button>
		<?php
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			include('database.php');
			$skladnikiBasic = explode(",", getIngredients());
			$skladniki = "";
			$wykluczone = "";

			for ($i=0; $i <sizeof($skladnikiBasic) ; $i++) { 
				$skladnikiBasic[$i] = str_replace(" ", "_", $skladnikiBasic[$i]);
				if($_POST[$skladnikiBasic[$i]]==1){
					$skladniki .= "'" . $skladnikiBasic[$i] . "'";
				}
				if($_POST[$skladnikiBasic[$i]]==0){
					$wykluczone .= "'" . $skladnikiBasic[$i] . "'";
				}
			}

			$skladniki = str_replace("''", "','", $skladniki);
			$wykluczone = str_replace("''", "','", $wykluczone);

			
			if(strlen($skladniki)>3){
				$sql = "SELECT * FROM przepisy WHERE (";
				for ($i=1; $i <= sizeof($skladnikiBasic); $i++) { 
					if($i != sizeof($skladnikiBasic)){
						$sql .= 'skl' . $i . ' IN ('. $skladniki .') OR ';
					}else{
						$sql .= 'skl' . $i . ' IN ('. $skladniki .'))';
					}
				}
			
			if(strlen($wykluczone)>3){
				$sql .= ' AND (';
				for ($i=1; $i <= sizeof($skladnikiBasic); $i++) { 
					if($i != sizeof($skladnikiBasic)){
						$sql .= 'skl' . $i . ' NOT IN ('. $wykluczone .') AND ';
					}else{
						$sql .= 'skl' . $i . ' NOT IN ('. $wykluczone .'))';
					}
				}
			}

			$sql = str_replace("_", " ", $sql);

			$statement = $pdo->prepare($sql);
			$statement->execute();
            $databaseData = $statement->fetchAll();
				foreach($databaseData as $row){
					$ingredients = "";
					$diffLvl = $row["type"];
					$time = $row["time"];
					for($r=1; $r<=sizeof($skladnikiBasic); $r++){
						if($row["skl".$r]!="empty"){
							if($r!=1){
								$ingredients = $ingredients.", ".$row["skl".$r];
							}
							else{
								$ingredients = $ingredients.$row["skl".$r];
							}
						}
					} 
					$homeUrl = get_home_url();
					$diffUrl = "";
					if($diffLvl>1){
						$diffUrl = "<div class='ingredientImg' style='background-image:url(".'"'.$homeUrl."/wp-content/themes/przepisy/assets/img/bar-chart-".$diffLvl.".svg".'"'.")'></div>";
					}
					$timeUrl = $homeUrl."/wp-content/themes/przepisy/assets/img/clock.svg";
					echo "<div class='ingredientResult w-100 mr-md-4 p-2 m-2 position-relative' data-value=";
					echo '"'.$row["nazwa"].'"';
					echo "><div class='d-flex justify-content-between'><p class='ingredientName'>".$row["nazwa"] . "</p><div class='d-flex justify-content-center text-center align-items-center'>".$diffUrl."<div class='ingredientImg ml-2 mr-2' style='background-image:url(".'"'.$timeUrl.'"'.")'></div>".$time." min"."</div></div><div>".$row["description"]."</div><br><p>Składniki : ".$ingredients."</p><button type='' class='testFormBtn h-100 w-100 position-absolute' style='top:0;left:0; opacity:0;'></button></div><br/>";
				}
			}
		}
    ?>
		</div>
		</div>
	</div>
	</section>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script>
		 const form = document.getElementById('testForm');
		const formSubmitBtn = document.querySelectorAll('.testFormBtn');
		const formInput = document.getElementById('testFormInput');

		formSubmitBtn.forEach((a) => {
			a.addEventListener('click', (b) => {
			formInput.value = b.target.parentElement.dataset.value;
			form.submit();
			})
		})
	</script>
	<script>
	<?php 
	echo 'const skladnikiBasic = ['.getIngredientsJS().']; '; 
	?>
	</script>
	<script src="<?= get_template_directory_uri() ?>/assets/js/script.js"></script>
	<script type="text/javascript" src=<?= get_home_url()."/wp-content/themes/przepisy/assets/js/menuScript.js"?>></script>