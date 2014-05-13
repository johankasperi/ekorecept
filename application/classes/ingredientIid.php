<?php
class ingredientWithIid {
	var $iid;
	var $cid;
	var $name;
	var $carbonDataList;
	var $averageCarbon;
	var $minCarbon;
	var $maxCarbon;
	
	function __construct($iid, $name, $cid) {
		$this->iid = $iid;
		$this->name = $name;
		$this->cid = $cid;
		$this->getCarbonData();
		$this->getAverageMinMax();
	}
	
	function getCarbonData() {
		$query = "SELECT * FROM carbon WHERE carbonGram>0 AND iid='".$this->iid."'";
		$result = sendQuery($query);
		$this->carbonDataList = array();
		while ($line = $result->fetch_object()) {
			$caid = $line->caid;
			$carbonGram = round($line->carbonGram, 2);
			$source = $line->source;
			$country = $line->country;
			$this->carbonDataList[] = new carbonData($caid, $carbonGram, $source, $country);
		}
	}
	
	function getAverageMinMax() {
		$query = "SELECT AVG(carbonGram) as av, MAX(carbonGram) as max, MIN(carbonGram) as min FROM carbon WHERE carbonGram>0 AND iid='".$this->iid."'";
		$result = sendQuery($query);
		while ($line = $result->fetch_object()) {
			$this->averageCarbon = round($line->av, 2);
			$this->minCarbon = round($line->min, 2);
			$this->maxCarbon = round($line->max, 2);
		}
	}
	
	function printCarbonDataTable() {
		$items="";
		$carbonDataList = $this->carbonDataList;
		$items = '<table class="carbonDataTable">
		<thead>
		<tr>
		<th>Koldioxid (g CO<sub>2</sub>/g)</th>
		<th>Källa</th>
		<th>Land</th>
		</tr></thead><tbody>';
		for($i=0;$i<sizeof($carbonDataList);$i++) {
			$carbonData = $carbonDataList[$i];
			$items .= '<tr>';
			$items .= '<td>'.$carbonData->carbonGram.'</td>';
			$items .= '<td>'.'<a href="'.$carbonData->source.'" target="_blank">'.$carbonData->source.'</td>';
			$items .= '<td>'.$carbonData->country.'</td>';
			$items .= '</tr>';
		}
		$items .= '</tbody></table>';
		return $items;
	}
}	
?>