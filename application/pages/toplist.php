<a href="#converter"><div class="arrowUp"></div></a>
	<div class="wrap">
		<h1>Topplista</h1>
		<h2>Spana in de mest miljövänliga recepten!</h2>
		<div class="inlineB textLeft">
		<?php
		$toplist = highscore();
		for($i=0;$i<sizeof($toplist);$i++) {
			echo ($i+1).'. <a class="toplist" data-toggle="tooltip" data-placement="right" title="'.$toplist[$i]->totalCarbon.'g CO2" href="http://recept.kasperi.se/recept.php?rid='.$toplist[$i]->rid.'">'.$toplist[$i]->name.'</a><br />';
		}
		?>
		</diV>
	</div>
<a href="#about"><div class="arrowDown"></div></a>