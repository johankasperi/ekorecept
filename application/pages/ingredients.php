<a href="#converter"><div class="arrowUp"></div></a>
	<div class="wrap">
		<div><h1 class="title"><?php echo $recipe->name; ?></h1><a href="<?php echo $recipe->url; ?>" target="_blank"><div class="exlink" data-toggle="tooltip" title="Gå till källa"></div></a></div>

			<div class="found-wrapper">
				<div class="inner">
					<h3>Hittade ingredienser</h3>
					<?php echo ($printIngs["found"]); ?>
				</div>
			</div>
			<div class="notfound-wrapper">
				<div class="inner">
					<h3>Ej hittade ingredienser</h3>
					<?php echo ($printIngs["notfound"]); ?>
				</div>
			</div>
		<div class="ingInfo">Tänk på att alla siffror är ungefärliga.</div>
	</div>
	<a href="#about"><div class="arrowDown"></div></a>