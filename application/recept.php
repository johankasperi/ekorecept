<?php
include "common/functions.php";

$rid = $_GET["rid"];
if($rid===null) {
header("Location: /index.php");
}

$recipe = getRecipe($rid);
if($recipe->name===null) {
header("Location: /index.php");
}

if($recipe->totalCarbon !== $recipe->totalCarbon()) {
	updateCarbon($recipe);
	$recipe = getRecipe($rid);
}
$printIngs = printIngs($recipe);
$highscore = highscore();

$average = getAverage();
if($average<$recipe->totalCarbon){
	$colorCO2='<span class="red colors">'.$recipe->totalCarbon.'</span>'; 
}
elseif ($average>=$recipe->totalCarbon()){
	$colorCO2='<span class="green colors">'.$recipe->totalCarbon.'</span>';
}
$highestIng = $recipe->highestIng();
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>	
	<title><?php echo $recipe->name; ?> | Ekorecept</title>
	
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/style.css"></link>
	<link href='http://fonts.googleapis.com/css?family=Bitter:400|Nunito:300,700' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" sizes="16x16"/>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="js/responsive-nav.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
$(document).ready(function(){
	$('.name').tooltip();
	var unit = 'laptop';
	var carbon='<?php echo $recipe->totalCarbon/1000; ?>';
	$.ajax({url:"common/carbonTo.php?unit="+unit+"&carbon="+carbon,success:function(result){
		var number = result.match(/\d+/g);
		var nr=number[0].toString();
		result=result.replace(nr,"");
		$("#ref-canvas").html('≈ <span class="green colors">'+nr+'</span> timmars användning av en laptop');
	}});
	
	$("#suggestion-select").change(function() {
		var value = $("#suggestion-select").val();
		if(value=="chicken") {
			var newCarbon = "<?php echo $recipe->totalCarbon-$highestIng->calcCarbon()+($highestIng->amount)*2.2 ?>";
			$(".suggestion-result").html("<h3>Då blir receptets totala koldioxidutsläpp <span class='green'>"+newCarbon+"</span> g CO<sub>2</sub></h3>");
		}
		else if(value=="pork") {
			var newCarbon = "<?php echo $recipe->totalCarbon-$highestIng->calcCarbon()+($highestIng->amount)*3.7 ?>";
			$(".suggestion-result").html("<h3>Då blir receptets totala koldioxidutsläpp <span class='green'>"+newCarbon+"</span> g CO<sub>2</sub></h3>");
		}
		else if(value=="quorn") {
			var newCarbon = "<?php echo $recipe->totalCarbon-$highestIng->calcCarbon()+($highestIng->amount)*3.4 ?>";
			$(".suggestion-result").html("<h3>Då blir receptets totala koldioxidutsläpp <span class='green'>"+newCarbon+"</span> g CO<sub>2</sub></h3>");
		}
		else if(value=="vegetables") {
			var newCarbon = "<?php echo $recipe->totalCarbon-$highestIng->calcCarbon()+($highestIng->amount)*1.08 ?>";
			$(".suggestion-result").html("<h3>Då blir receptets totala koldioxidutsläpp <span class='green'>"+newCarbon+"</span> g CO<sub>2</sub></h3>");
		}
		else if(value=="none") {
			$(".suggestion-result").html("");
		}
	});
		
	
	$("#ref-select").change(function(){
	  var unit = $("#ref-select").val();
	  var carbon='<?php echo $recipe->totalCarbon/1000; ?>';
	  $.ajax({url:"common/carbonTo.php?unit="+unit+"&carbon="+carbon,success:function(result){
		var number = result.match(/\d+/g);
		var nr=number[0].toString();
		result=result.replace(nr,"");
		if(result.indexOf("bottles of beer") != -1){
			result=" flaskor öl";
		}
		else if(result.indexOf("milk") != -1){
			result=" liter mjölk";
		}
		else if(result.indexOf("lightbulb") != -1){
			result=" timmar tänd glödlampa";
		}
		else if(result.indexOf("apples") != -1){
			result=" äpplen";
		}
		else if(result.indexOf("bananas") != -1){
			result=" bananer";
		}
		else if(result.indexOf("hours flying") != -1){
			result=" timmars flygresa";
		}
		else if(result.indexOf("km of flight") != -1){
			result=" km lång flygresa";
		}
		else if(result.indexOf("train") != -1){
			result=" km lång tågresa";
		}		
		else if(result.indexOf("subway") != -1){
			result=" km lång resa med tunnelbana";
		}
		else if(result.indexOf("laptop") != -1){
			result=" timmars användning av en Laptop";
		}
		else if(result.indexOf("car") != -1){
			result=" km lång bilresa";
		}
		else if(result.indexOf("phone") != -1){
			result=" laddningar av en mobiltelefon";
		}
		else if(result.indexOf("tomatoes") != -1){
			result=" tomater";
		}
		else if(result.indexOf("beef") != -1){
			result=" kg nötkött";
		}
		else if(result.indexOf("fridge") != -1){
			result=" års strömförbrukning för ett kylskåp";
		}
		else if(result.indexOf("salmon") != -1){
			result=" kg lax";
		}
		else if(result.indexOf("rice") != -1){
			result=" portioner ris";
		}
		else if(result.indexOf("beef") != -1){
			result=" kg nötkött";
		}
		else if(result.indexOf("bread") != -1){
			result=" limpor bröd";
		}
		else if(result.indexOf("flat") != -1){
			result=" års värme för en lägenhet";
		}
		$("#ref-canvas").html('≈ <span class="green colors">'+nr+'</span>'+result);
	  }});
	});
	
	$(".input-add").keyup(function(){
		var q = $(this).val();
		var id = $(this).attr('id');
		var name = $("#name"+id).html();
		var rid = '<?php echo $recipe->rid; ?>';
		$.ajax({url:"common/liveSearch.php?q="+q+"&rid="+rid+"&name="+encodeURIComponent(name),success:function(result){
		$("#result"+id).html(result);
	  }});
	});
	
	$(".form-add").submit(function() {
	  return false;
	});
	
	$(".scroll").click(function (event) {
    event.preventDefault();
    //calculate destination place
    var dest = 0;
    if ($(this.hash).offset().top > $(document).height() - $(window).height()) {
        dest = $(document).height() - $(window).height();
    } else {
        dest = $(this.hash).offset().top;
    }
    //go to destination
    $('html,body').animate({
        scrollTop: dest
    }, 800, 'swing');
});
});

</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript"><?php include 'js/jsChart.php'; ?></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-26146773-5']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/sv_SE/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</head>
<body>
<header class="header">
	<div class="inner">
	<div id="logo">
	<a href="/"><img src="img/logo.png" alt="logo" />
	<div class="logo-text">Ekorecept</div></a>
	</div>

	<nav id="nav">
		<?php include 'common/menu.php';?>
	</nav>
	</div>
</header>

<span id="top-anchor" class="anchor"></span>
<section class="section" id="top">
	<div class="inner section1-inner">
		<h1 class="first">"<?php echo $recipe->name; ?>"</h1>
		<h1 class="first">Total koldioxidutsläpp: 
		≈<?php echo $colorCO2;?> g CO<sub>2</sub></h1>
		<div class="servings">(4 portioner)</div>
		<a class="scroll" href="#converter-anchor"><button class="big">Vad motsvarar <?php echo $recipe->totalCarbon; ?> g CO<sub>2</sub>?</button></a>
	</div>
</section>

<span id="converter-anchor" class="anchor"></span>
<section class="section" id="converter">
	<div class="inner">
		<h2>Vad motsvarar <?php echo $colorCO2; ?> g CO<sub>2</sub>?</h2>
		<div class="reference">
			<h2 id="ref-canvas"></h2>
		</div>
		<div id="convert">
			<p>Prova vår omvandlare för att se fler jämförelser!</p>
			<select class="form-control" id="ref-select" onchange="_gaq.push(['_trackEvent', 'Converter interaction', 'Converter interaction']);">
				<option value="beers">Öl</option>
				<option value="milk">Mjölk</option>
				<option value="lightbulb">Glödlampa</option>
				<option value="apples">Äpplen</option>
				<option value="bananas">Bananer</option>
				<option value="flight-distance">Km flygning</option>
				<option value="train">Tågresa</option>
				<option value="underground">Tunnelbana</option>
				<option value="laptop" selected>Laptop</option>
				<option value="car">Bil</option>
				<option value="mobile-charges">Mobiltelefon</option>
				<option value="tomatoes">Tomater</option>
				<option value="beef">Nötkött</option>
				<option value="fridge">Kylskåp</option>
				<option value="salmon">Lax</option>
				<option value="rice">Ris</option>
				<option value="bread">Bröd</option>
				<option value="heating">Värme från en lägenhet</option>
			</select>
		</div>
	</div>
</section>

<span id="emissions-anchor" class="anchor"></span>
<section class="section" id="emissions">
	<div class="inner">
		<h2>Utsläpp</h2>
		<div id="text">
		<div>
		<h4 class="dataLabel">Koldioxid:</h4><span style="font-family:Arial">≈</span>
		<?php echo $colorCO2; ?> g CO<sub>2</sub> (4 portioner)
		</div>
		<div>
		<h4 class="dataLabel">Genomsnitt:</h4> <?php echo round(getAverage()); ?> g CO<sub>2</sub> (medelvärdet av alla recept i vår databas).
		</div>
		<div class="notes">
		<h4>Värt att tänka på när du handlar</h4>
		<ul>
			<li>Försök välja ekologisk och närproducerade livsmedel.</li>
			<li>Försök undvika animalieprodukter, då dess koldioxidutsläpp är betydligt högre än vegetariska livsmedel.</li>
		</ul>
		</div>
		</div>
		<div id="googleChart"></div>
	</div>
</section>

<span id="villain-anchor" class="anchor"></span>
<section class="section" id="villain">
	<div class="inner">
		<h2>Stora boven</h2>
		<div class="content">Ingrediensen i det här receptet som har högst koldioxidutsläpp är "<?php echo $highestIng->name ?>". Dess koldioxidutsläpp är ca <b><span class="red"><?php echo $highestIng->calcCarbon() ?></span> g CO<sub>2</sub></b> vilket motsvarar <b><?php echo round(($highestIng->calcCarbon()/$recipe->totalCarbon)*100) ?> %</b> av receptets totala koldioxidutsläpp. Du kanske ska välja ett annat recept? T.ex. från vår <a class="scroll" href="#toplist-anchor">topplista.</a></div>
		<?php if($highestIng->category==1) {
		 $gaTrackEvent = "_gaq.push(['_trackEvent', 'Stora boven interaction', 'Stora boven interaction']);";
		 echo '<div class="suggestion">För att minska receptets koldioxidutsläpp kan du:
		<select class="form-control" id="suggestion-select" onchange="'.$gaTrackEvent.'">
		<option selected value="none">-Välj-</option>
		<option value="vegetables">Byta ut "'.$highestIng->name.'" till grönsaker eller svamp</option>
		<option value="chicken">Byta ut "'.$highestIng->name.'" till kyckling</option>
		<option value="pork">Byta ut "'.$highestIng->name.'" till fläskkött</option>
		<option value="quorn">Byta ut "'.$highestIng->name.'" till quorn</option>
		</select> 
		<div class="suggestion-result"></div>
		</div>';
		}
		?>
	</div>
</section>

<span id="ingredients-anchor" class="anchor"></span>
<section class="section" id="ingredients">
	<div class="inner">
		<h2>Ingredienser</h2>
		<div class="found-wrapper">
					<h3>Hittade ingredienser</h3>
					<div class="small">Hittades i vår databas. Klicka dig gärna in på dem för att få reda på mer.</div>
					<?php echo ($printIngs["found"]); ?>
			</div>
			<div class="notfound-wrapper">
					<h3>Ej hittade ingredienser</h3>
					<div class="small">Hittades inte i vår databas. Lägg gärna till!</div>
					<?php echo ($printIngs["notfound"]); ?>
			</div>
		<div class="ingInfo">Tänk på att alla siffror är ungefärliga.</div>
		<div class="source">
		<button class="big"><a href="<?php echo $recipe->url; ?>" target="_blank">Gå till källa</a></button>
		</div>
	</div>
</section>

<span id="toplist-anchor" class="anchor"></span>
<section class="section" id="toplist">
	<div class="inner">
		<h2>Topplista</h2>
		<h3>Spana in de mest miljövänliga recepten!</h3>
		<?php
		$toplist = highscore();
		for($i=0;$i<sizeof($toplist);$i++) {
			$gaEventTrack = "_gaq.push(['_trackEvent', 'Highscore click', ''no ".($i+1)."']);";
			echo '<div class="toplist-item"><span class="toplist-name">'.($i+1).'. <a class="toplist" onClick="'.$gaEventTrack.'" href="http://recept.kasperi.se/recept.php?rid='.$toplist[$i]->rid.'">'.$toplist[$i]->name.'</a></span>
					<span class="toplist-emission">'.$toplist[$i]->totalCarbon.'g CO<sub>2</sub></span></div>';
		}
		?>
	</div>
</section>

<span id="lca-anchor" class="anchor"></span>
<section class="section" id="about-lca">
	<div class="inner">
		<h2>Om livscykelanalyser</h2>
		<div class="content">
			Vi beräknar ditt recepts koldioxidkostnad med hjälp av livscykelanalyser från flera olika källor, men trots det så blir våra siffror väldigt ungefärliga. Livscykelanalys är en metod för att åstadkomma en helhetsbild av hur stor den totala miljöpåverkan är under en produkts livscykel från råvaruutvinning, via tillverkningsprocesser och användning till avfallshanteringen, inklusive alla transporter och all energiåtgång i mellanleden.
		</div>
	</div>
</section>

<span id="share-anchor" class="anchor"></span>
<section class="section" id="share">
	<div class="inner">
		<h2>Dela med dig</h2>
		<div class="content">
		Dela gärna med dig av ditt val av recept, på så sätt kan du få fler personer att välja en mer miljövänlig måltid!
		</div>
		<div class="shareLeft" onclick="_gaq.push(['_trackEvent', 'Twitter share', 'Twitter share']);"><a href="https://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a></div>
		<div class="shareRight"><div class="fb-share-button" data-href="http://recept.kasperi.se/recept.php?rid=<?php echo $recipe->rid; ?>" data-type="button"></div></div>

		</div>
	</div>
</section>
	
<footer id="foot">
	<p id="slv">Vi använder data ur <a href="http://www.slv.se/sv/">Livsmedelsverkets livsmedelsdatabas</a> version 2014-01-28</p>
	<div class="footContent">
		<div id="contact">
			<p>KONTAKTA OSS</p>
			<span>danielin [a] kth.se</span><br/>
			<span>kasperi [a] kth.se</span>
		</div>
		<div id="backTop">
			<p><a href="#top-anchor" class="scroll">Till toppen</a></p>
		</div>
	</div>
</footer>
<script src="js/gaTrackSocialEvent.js"></script>
<script>
_ga.trackTwitter();
_ga.trackFacebook();
</script>
    <script>
      var navigation = responsiveNav(".nav-collapse", {
        animate: true,                    // Boolean: Use CSS3 transitions, true or false
        transition: 284,                  // Integer: Speed of the transition, in milliseconds
        label: "<i class=\"fa fa-bars\"></i>",                    // String: Label for the navigation toggle
        insert: "before",                  // String: Insert the toggle before or after the navigation
        customToggle: "",                 // Selector: Specify the ID of a custom toggle
        closeOnNavClick: false,           // Boolean: Close the navigation when one of the links are clicked
        openPos: "relative",              // String: Position of the opened nav, relative or static
        navClass: "nav-collapse",         // String: Default CSS class. If changed, you need to edit the CSS too!
        navActiveClass: "js-nav-active",  // String: Class that is added to <html> element when nav is active
        jsClass: "js",                    // String: 'JS enabled' class which is added to <html> element
        init: function(){},               // Function: Init callback
        open: function(){},               // Function: Open callback
        close: function(){}               // Function: Close callback
      });
    </script>
</body>
</html>