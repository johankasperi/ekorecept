<?php

include("./database/connect.php");
include("./classes/ingredientClass.php");
include("./classes/recipeClass.php");

/// HÄMTA FRÅN DATABASEN FUNKTIONER ///

function getRecipe($rid) {
	$query = "SELECT * FROM recipes WHERE rid='".$rid."'";
	$result = sendQuery($query);
	while ($line = $result->fetch_object()) {
		$rid = $line->rid;
		$name = $line->name;
		$url = $line->url;
		$ings = $line->ingredients;
		$totalCarbon = $line->totalCarbon;
		$servings = $line->servings;
	}
	$recipe = new Recipe($rid, $name, $url, $ings, $totalCarbon, $servings);
	return $recipe;	
}

function updateCarbon($recipe) {
	$query = "UPDATE recipes
	SET totalCarbon=".$recipe->totalCarbon().
	" WHERE rid=".$recipe->rid;
	sendQuery($query);
}

function highscore() {
	$query = "SELECT * FROM recipes WHERE totalCarbon>0 ORDER BY totalCarbon ASC limit 10";
	$result = sendQuery($query);
	$recipes = array();
	while ($line = $result->fetch_object()) {
		$rid = $line->rid;
		$name = $line->name;
		$url = $line->url;
		$ings = $line->ingredients;
		$totalCarbon = $line->totalCarbon;
		$servings = $line->servings;
		$recipes[] = new Recipe($rid, $name, $url, $ings, $totalCarbon, $servings);
	}
	return $recipes;
}

function getAverage() {
	$query = "SELECT AVG(totalCarbon) as average FROM recipes WHERE totalCarbon>0";
	$result = sendQuery($query);
	while ($line = $result->fetch_object()) {
		$avg = $line->average;
	}
	return ceil($avg/100)*100;
}

/// PRINT-FUNKTIONER ///
function printIngs($recipe) {
	$ings = $recipe->ingredients;
	$found = "";
	$notFound = "";
	for ($i=0;$i<sizeof($ings);$i++) {
		$ingredient = $ings[$i];
		if($ingredient->found==1) {
			$gaEventTrack = "_gaq.push(['_trackEvent', 'Click on found ingredient', 'Click on found ingredient']);";
			$found .= '<div class="ingredient">';
			$found .= '<div class="amount">'.$ingredient->amount.'</div>';
			$found .= '<div class="unit">'.$ingredient->unit.'</div>';
			$found .= '<div class="name" data-toggle="tooltip" data-placement="right" title="'.$ingredient->calcCarbon().'g CO2"><a href="/ingredient.php?iid='.$ingredient->iid.'&rid='.$recipe->rid.'" onclick="'.$gaEventTrack.'">'.$ingredient->name.'</a></div>';
			$found .= '</div>';
		}
		else if($ingredient->found==0 && $ingredient->foundAmountOrUnit==True) {
			$gaEventTrack = "_gaq.push(['_trackEvent', 'Click on not-found ingredient', 'Click on not-found ingredient']);";
			$notFound .= '<div class="ingredient">';
			$notFound .= '<button class="small" onclick="'.$gaEventTrack.'" id="addBtn" data-toggle="modal" data-target="#myModal'.$i.'">Lägg till</button> ';
			$notFound .= '<div class="name">'.$ingredient->notFoundText.'</div>';
			$notFound .= '<div id="name'.$i.'" class="name-hidden">'.$ingredient->notFoundName.'</div>';
			$notFound .= '<div class="modal fade" id="myModal'.$i.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							  <div class="modal-dialog">
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="myModalLabel">Sök efter '.$ingredient->notFoundName.'</h4>
								  </div>
								  <div class="modal-body">
									<form class="form-add">'.'<input type="text" placeholder="Sök" id="'.$i.'" class="input-add form-control" />'.'<div id="result'.$i.'"></div></form>
								  </div>
								</div>
							  </div>
							</div></div>';
		}
	}
	return array("found"=>$found, "notfound"=>$notFound);
}

/// FUNKTIONER FÖR INGREDIENSSIDAN ///
function getIngredient($iid) {
	$query = "SELECT * FROM ingredients WHERE iid=".$iid;
	$result = sendQuery($query);
	while ($line = $result->fetch_object()) {
		$iid = $line->iid;
		$name = $line->name;
		$cid = $line->category;
	}
	return new ingredientWithIid($iid, $name, $cid);
}

function getCategoryData($ingredient) {
	$query = "SELECT * FROM categories WHERE cid=".$ingredient->cid;
	$result = sendQuery($query);
	$items = "";
	while ($line = $result->fetch_object()) {
		$items .= '<h3>Genomsnittligt koldioxidutsläpp för '.$line->name.': '.$line->carbonGram.' g CO<sub>2</sub>/g</h3>';
		$items .= '<div class="desc">Då ingen koldioxiddata är inrapporterad för "'.$ingredient->name.'" så använder vi oss av de genomsnittliga värdet
		för dennes kategori, vilket är '.$line->carbonGram.' g CO<sub>2</sub>/g.</div>';
	}
	return $items;
}

function printCarbonData($ingredient) {
	$items = "";
	if($ingredient->averageCarbon != 0) {
		$items .= '<div class="item"><h4 class="dataLabel">Aktuella värdet:</h4> <strong>'.$ingredient->averageCarbon.'</strong> g CO<sub>2</sub>/g  (medel av all inrapporterad data)</div>';
		$items .= '<div class="item"><h4 class="dataLabel">Max:</h4> <span class="red">'.$ingredient->maxCarbon.'</span> g CO<sub>2</sub>/g</div>';
		$items .= '<div class="item"><h4 class="dataLabel">Min:</h4> <span class="green">'.$ingredient->minCarbon.'</span> g CO<sub>2</sub>/g</div>';
		$items .= '<div class="carbonDataList">';
		$items .= '<h3>Inrapporterade koldioxiddata</h3>';
		$items .= '<div class="desc">Siffrorna nedan har våra användare rapporterat in till oss. Det är dessa siffror som vi beräknar ditt recepts koldioxidutsläpp på.</div>';
		$items .= $ingredient->printCarbonDataTable();
		$items .= '</div>';
	}
	else {
		$items .= getCategoryData($ingredient);
	}
	return $items;
}

?>