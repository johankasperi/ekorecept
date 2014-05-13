<div class="wrap"><h1><?php echo $recipe->name; ?></h1>
	<h2>Receptets totala koldioxidutsläpp är
	<?php 
		$average = getAverage();
		if($average+200<$recipe->totalCarbon()){
		echo '<bold class="red colors" data-toggle="tooltip" data-placement="bottom" title="Medel är '.round($average).'g CO2">'.$recipe->totalCarbon().'</bold>g, vilket är ovanligt högt!'; 
		}
		elseif ($average-200>$recipe->totalCarbon()){
				echo '<bold class="green colors" data-toggle="tooltip" data-placement="bottom" title="Medel är '.round($average).'g CO2">'.$recipe->totalCarbon().'</bold>g, vilket är under medel. Bra val!';
			}
		else{
			echo '<bold class="yellow colors" data-toggle="tooltip" data-placement="bottom" title="Medel är '.round($average).'g CO2">'.$recipe->totalCarbon().'</bold>g, vilket är runt medel.';
		}
			?></h2>
		
	<div id="googleChart"></div></div>
	<a href="#converter"><div class="arrowDown"></div></a>