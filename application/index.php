<html>
<head>
	<title>Välkommen till Ekorecept! | Ekorecept</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" sizes="16x16"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/style.css"></link>
	<link href='http://fonts.googleapis.com/css?family=Bitter:400|Nunito:300;700' rel='stylesheet' type='text/css'>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="js/responsive-nav.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.validate.min.js"></script>
	<script>
$(document).ready(function(){

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
	
	$("#addCarbon").validate({
    	errorContainer: "#errorBox",
    	errorLabelContainer: "#errorBox ul",
   	 	wrapper: "li",
        rules: {
            carbon: {
            	required: true,
            	number: true
            },
            source: {
            	required: true,
            	url: true
            }
        },
        
        messages: {
            carbon: "Vänligen skriv in koldioxiddata, det måste vara en siffra.",
            source: {
            	required: "Vänligen skriv in en källa.",
            	url: "Källan måste vara en url."
            }
        },
        
        submitHandler: function(form) {
            form.submit();    
        }
    });
});

</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript"><?php include 'js/jsChart.php'; ?></script>
</head>
<body>
<header class="header">
	<div class="inner">
	<div id="logo">
	<a href="/"><img src="img/logo.png" alt="logo" />
	<div class="logo-text">Ekorecept</div></a>
	</div>

	<nav id="nav">
		<?php include 'common/menuIndex.php';?>
	</nav>
	</div>
</header>

<span id="top-anchor" class="anchor"></span>
<section class="section" id="top">
<section class="inner">
<h1 class="first">Börja använd Ekorecept</h1>
<p>För att komma igång installerar du vårat add-on till webläsaren Google Chrome, för hjälp följ vår <a id="installHref" href="#install-anchor" class="scroll">installationsanvisning</a> nedan.</p>
<a target="_blank" href="http://recept.kasperi.se/plugin/ekorecept_plugin.zip" onclick="_gaq.push(['_trackEvent', 'Download add-on', 'Download add-on button']);"><button class="big">Ladda hem add-on</button></a><br/>
</section>
</section>

<span id="about-anchor" class="anchor"></span>
<section class="section" id="about">
<section class="inner">
	<h1>Välkommen till Ekorecept</h1>
<span>Vi beräknar ditt recepts koldioxidkostnad med hjälp av livscykelanalyser. Livscykelanalys är en metod för att åstadkomma en helhetsbild av hur stor den totala miljöpåverkan är under en produkts livscykel från råvaruutvinning, via tillverkningsprocesser och användning till avfallshanteringen, inklusive alla transporter och all energiåtgång i mellanleden.<br><br>Utvecklat av Daniel Lindström och Johan Kasperi under vårt kandidatexamensarbete på Medieteknik, KTH.</span>
</section>
</section>

<span id="install-anchor" class="anchor"></span>
<section class="section" id="install">
<section class="inner">
	<h1>Installationsanvisningar</h1>
<span>1. <a target="_blank" href="http://recept.kasperi.se/plugin/ekorecept_plugin.zip" onclick="_gaq.push(['_trackEvent', 'Download add-on', 'Download add-on link']);">Ladda hem</a> och packa upp vårat add-on.<br /><br />2. Öppna Google Chrome, gå in i Inställningar -> Tillägg, kryssa i "Programmerarläge".<br /><br />3. Tryck på knappen "Hämta okomprimerat tillägg.." och välj mappen som du packade upp vårat add-on.<br /><br />4. Klart! Surfa in på t.ex. <a href="http://www.tasteline.com">Tasteline</a> och hitta miljövänliga recept.</span>
</section>
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