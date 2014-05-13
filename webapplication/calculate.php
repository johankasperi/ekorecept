<?php			

include("common/functions.php");
include("common/functionsCalculate.php");

addRecipe();
$rid = getRid();
$recipe = getRecipe($rid);
$total = $recipe->totalCarbon();
updateCarbon($recipe);
$result=array("carbon"=>$total, "rid"=>$recipe->rid);
header('Content-type: application/json');
$result = json_encode($result);
echo $result;
?>