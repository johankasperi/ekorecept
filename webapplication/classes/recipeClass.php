<?php
class Recipe{
	var $rid;
	var $name;
	var $ingredients;
	var $url;
	var $totalCarbon;
	var $servings;
	
	function __construct($rid, $name, $url, $input, $totalCarbon, $servings) {
		$this->rid = $rid;
		$this->name = $name;
		$this->url = $url;
		$this->servings = $servings;
		$this->parseIng($input);
		$this->totalCarbon = $totalCarbon;		
	}
	
	function parseIng($input) {
		$input = explode("|",$input);
		$this->ingredients = array();
		for($i=0;$i<sizeof($input);$i++) { 
			$this->ingredients[] = $this->createIng($input[$i]);
		}
	}
	
	function createIng($ing) {
		$ing = explode(' ', $ing);
		$name = implode(' ',array_slice($ing, 2));
		$amount = str_replace(",",".",$ing[0]);
		if(is_numeric($amount)) {
			$amount = round(($amount/$this->servings)*4,2);
		}
		else {
			$amount = $ing[0];
		}
		$unit = $ing[1];
		$ingredient = new Ingredient($amount, $unit, $name);
		return $ingredient;
	}
	
	function totalCarbon() {
		$totalCarbon = 0;
		for($i=0;$i<sizeof($this->ingredients);$i++) {
			$ing = $this->ingredients[$i];
			$totalCarbon = $totalCarbon + $ing->calcCarbon();
		}
		return $totalCarbon;
	}
	
	function highestIng() {
		$highest = $this->ingredients[0];
		for ($i=0;$i<sizeof($this->ingredients);$i++) {
			if ($highest->calcCarbon() < $this->ingredients[$i]->calcCarbon()) {
				$highest = $this->ingredients[$i];
			}
		}
		return $highest;	
	}	
}
?>