if(document.querySelector('[itemprop="ingredients"]') || document.querySelector('[class="divrecipe222"]')){
	init();
}
	

function init() {
	var ings = screenScrape();
	var port = getPort();
	var url = getUrl();
	var xhr = new XMLHttpRequest();
	var name = document.getElementsByTagName("title")[0].innerHTML;
	if(name.indexOf(" - Recept") != -1){
	name=name.slice(0,-10);
	}
	xhr.open("GET", "http://recept.kasperi.se/calculate.php?ing="+ings+"&source="+encodeURIComponent(url)+"&name="+encodeURIComponent(name).replace("%0A%09",'')+"&port="+port, true);
	xhr.onreadystatechange = function() {
	  if (xhr.readyState == 4) {
		var resp = JSON.parse(xhr.responseText);
		insertResult(resp);
	  }
	}
	xhr.send();
}

function screenScrape() {
	if(document.querySelector('[itemprop="ingredients"]')) {
		var parse = document.querySelectorAll('[itemprop="ingredients"]');
	}
	else if(document.domain == "www.tasteline.com") {
		var parse = document.getElementsByClassName("tdIngredientAmount");
	}
	var url = "";
	var ing = new Array();
	for (var i = 0; i < parse.length;i++){
		var inHTML =parse[i].innerText.replace('\n/,','');
		inHTML=encodeURIComponent(inHTML).replace(/%C2%A0/g,'%20');
		inHTML=inHTML.replace(/%20%20/g,'%20');
		ing.push(inHTML);
	}
	ing = checkOrigin(ing);
	url = ing.join("|");
	return url;
}

function getUrl() {
	if(document.URL.indexOf('?')!=-1) {
		var url = document.URL.substring(0, document.URL.indexOf('?'));
	}
	else {
		var url = document.URL;
	}
	return url;
}

function getPort() {
	if(document.querySelector('[itemprop="ingredients"]') && document.domain != "www.recept.nu") {
		var port = document.querySelectorAll('[itemprop="recipeYield"]')[0].innerHTML;
		console.log(port);
		port = parseInt(port);
		console.log(port);
	}
	else if(document.domain == "www.tasteline.com" && document.getElementsByClassName("DivRecipePortions")[0].querySelectorAll('option:checked')[0]) {
		var port = document.getElementsByClassName("DivRecipePortions")[0].querySelectorAll('option:checked')[0].value;
	}
	else if(document.domain == "www.recept.nu") {
		var port = document.getElementsByClassName('yield')[0].innerText;
	}
	else {
		var port = 4;
	}
	return port;
}

function checkOrigin(ing) {
	var origin = window.location.origin;
	if (origin == "http://www.recept.nu") {
		var len = ing.length/2;
		ing.splice(len, len);
		for(var j=0;j<len;j++){
			ing[j] = ing[j].replace(/%20%0A/g,'');
		}
	}
	return ing;
}
	
function insertResult(resp) {
    var content = document.createElement('div');
	content.style.fontSize="12px";
	content.style.fontFamily="Arial";
	content.style.border="thin solid #b3b3b3";
	content.style.backgroundColor="white";
	content.style.display="inline-block";

	if(resp.carbon==0) {
		content.innerHTML = "<div style='margin:5px'><div style='float:right'><img height='20px' src='http://recept.kasperi.se/img/logo.png'/></div><b>Receptets CO<sub>2</sub>-utsläpp:</b><br/><div style='font-size:1.2em;'>Inga ingredienser hittades. Kan inte räkna ut koldioxidutsläppet.</div></div>";
	}
	else {
		var kmByCar = (resp.carbon/1000)*6;
		content.innerHTML = "<div style='margin:5px'><div style='float:right'><img height='20px' src='http://recept.kasperi.se/img/logo.png'/></div><b>Receptets CO<sub>2</sub>-utsläpp:</b><br/><div style='font-size:1.2em;'>ca <b>"+resp.carbon+"</b> g (4 portioner)<br>Det motsvarar en ca <b style='color:#ff5740'>"+Math.round(kmByCar)+"</b> km lång bilresa.</div></div>";
		var aLink = document.createElement('a');
		aLink.target="_blank";
		aLink.href="http://recept.kasperi.se/recept.php?rid="+resp.rid;
		var button = document.createElement('div');
		var tButton = document.createTextNode('Läs mer');
		button.appendChild(tButton);
		button.style.backgroundColor="#15b86e";
		button.style.display="inline-block";
		button.style.margin="5px";
		button.style.color="white";
		button.style.padding="2px 6px 2px 6px";
		button.onmouseover=function(){button.style.backgroundColor="#00a663";};
		button.onmouseout=function(){button.style.backgroundColor="#15b86e";};
		aLink.appendChild(button);
		content.appendChild(aLink);
	}
	
	
	if(document.domain == "www.tasteline.com") {
		var insert = document.getElementsByClassName('divrecipe222')[0];
		content.style.cssFloat="left";
		content.style.color="black";
		insert.appendChild(content);
	}
	else if(document.domain == "www.recept.nu") {
		var insert = document.getElementsByClassName('recipe-content')[0];
		content.style.margin="10px";
		content.style.cssFloat="left";
		content.style.color="black";
		insert.appendChild(content);
	}
	else if(document.domain == "www.coop.se") {
		var insert = document.getElementsByClassName('ingredient-list')[0];
		content.style.color="black";
		content.marginBottom="5px";
		insert.appendChild(content);
	}
	
	else if(document.querySelector('[itemprop="ingredients"]')) {
		var insert = document.querySelectorAll('[itemprop="name"]')[0];
		insert.parentNode.insertBefore(content,insert.nextSibling);
	}
}