<?php

class Ingredient{
	var $iid;
	var $amount;
	var $unit;
	var $name;
	var $category;
	var $carbonGram;
	var $gramPiece;
	var $gramDl;
	var $found;
	var $foundAmountOrUnit;
	var $catCarbonGram;
	var $catGramPiece;
	var $catGramDl;
	
	function __construct($a, $u, $n) {
		$this->notFoundText = $a." ".$u." ".$n;
		$this->notFoundName = $n;
		$this->found = 1;
		$this->foundAmountOrUnit = True;
		$this->getIngredient($n);
		$this->getAmount($a);
		$this->getUnit($u);
		$this->getCarbon();
	}
	
	function getAmount($amount) {
		$amount = str_replace(",",".",$amount);
		if(is_numeric($amount)) {
			$this->amount = $amount;
		}
		else {
			$this->found = 0;
			$this->foundAmountOrUnit = False;
		}
	}

	function getUnit($unit) {
		$unit = strtolower($unit);
		$unit = mysqli_real_escape_string($GLOBALS["connection"],$unit);
		$query = "SELECT * FROM units WHERE unit='".$unit."' OR short='".$unit."'";
		$result = sendQuery($query);
		$dl = 0;
		$g = 0;
		while ($line = $result->fetch_object()) {
			$dl = $line->dl;
			$g = $line->g;
		}
		if($dl != 0) {
			$this->amount = $dl*$this->amount;
			$this->unit = "dl";
		}
		elseif($g != 0) {
			$this->amount = $g*$this->amount;
			$this->unit = "g";
		}
		elseif($unit == "st" || $unit == "finhackad" || $unit == "hackad" || $unit == "stycken" || $unit == "skivad" || $unit == "paket" || $unit == "skiva(or)") {
			$this->unit = "st";
			/*if ($this->gramPiece != 0) {
				$this->piecesDesc = " (".round($this->amount*$this->gramPiece,0)." g)";
			}
			else {
				$this->piecesDesc = " (".round($this->amount*$this->catGramPiece,0)." g)";
			}*/
		}
		else {
			$this->found = 0;
			$this->foundAmountOrUnit = False;
		}
	}
	
	function getIngredient($name) {
		$names = $this->editName($name);
		$name = $names[0];
		$altName = $names[1];
		$name = mysqli_real_escape_string($GLOBALS["connection"],$name);
		$altName = mysqli_real_escape_string($GLOBALS["connection"],$altName);
		$query = "SELECT * 
		FROM ingredients
		WHERE name LIKE '%".$name."%' OR iid = (SELECT iid FROM ingAlt WHERE alt='".$name."')  
		ORDER by 
			CASE
				WHEN name LIKE '".$name."' OR name LIKE '".$altName."' THEN 1
				WHEN iid = (SELECT iid FROM ingAlt WHERE alt='".$name."') THEN 2
				WHEN name LIKE '".$name." ' OR name LIKE '".$altName." ' THEN 3
				WHEN name LIKE '".$name."%' OR name LIKE '".$altName."%' THEN 4
				WHEN name LIKE '%".$name."' OR name LIKE '%".$altName."' THEN 5
				ELSE 6
			END
		LIMIT 1";
		$result = sendQuery($query);
		if(mysqli_num_rows($result) > 0) {
			while ($line = $result->fetch_object()) {
				$this->iid = $line->iid;
				$this->name = $line->name;
				$this->category = $line->category;
				$this->gramPiece = $line->gramPiece;
				$this->gramDl = $line->gramDeciliter;
			}
			$this->getCategory();
		}
		else {
			$this->found = 0;
		}	
	}
	
	function editName($name) {
		if(strpos($name,",") !== False) {
			$name = substr($name, 0, strpos($name, ","));
		}
		if(strpos($name,"(") !== False) {
			$name = substr($name, 0, strpos($name, "("));
		}
		$altName = $name;
		if(substr($name, strlen($name)-2,strlen($name)) == "er" || substr($name, strlen($name)-2,strlen($name)) == "or") {
			$altName = substr($name,0,strlen($name)-2);
		}
		return array($name, $altName);
	}
	
	function getCategory() {
		$query = "SELECT * FROM categories WHERE cid='".$this->category."'";
		$result = sendQuery($query);
		while ($line = $result->fetch_object()) {
			$this->catCarbonGram = $line->carbonGram;
			$this->catGramPiece = $line->gramPiece;
			$this->catGramDl = $line->gramDeciliter;
		}
	}
	
	function getCarbon() {
		$query = "SELECT AVG(carbonGram) as average FROM carbon WHERE carbonGram>0 AND iid='".$this->iid."'";
		$result = sendQuery($query);
		while ($line = $result->fetch_object()) {
			$this->carbonGram = $line->average;
		}
	}
	
	function calcCarbon() {
		if ($this->carbonGram != 0) {
			$carbonGram = $this->carbonGram;
		}
		else {
			$carbonGram = $this->catCarbonGram;
		}
		if ($this->unit == "dl") {
			if ($this->gramDl != 0) {
				$carbon = $this->amount*$this->gramDl*$carbonGram;
			}
			else {
				$carbon = $this->amount*$this->catGramDl*$carbonGram;
			}
		}
		elseif ($this->unit == "st") {
			if ($this->gramPiece != 0) {
				$carbon = $this->amount*$this->gramPiece*$carbonGram;
			}
			else {
				$carbon = $this->amount*$this->catGramPiece*$carbonGram;
			}
		}
		elseif ($this->unit == "g") {
			$carbon = $this->amount*$carbonGram;
		}
		return ceil($carbon/10)*10;
	}
}
?>