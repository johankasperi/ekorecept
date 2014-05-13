 // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Koldioxid');
        data.addColumn('number', 'gram');
        data.addRows([<?php
			$sumOther=0;
			$theIngs='';
			$other='';
			$ings = $recipe->ingredients;
			$totalCarb = $recipe->totalCarbon;
			for($i=0;$i<sizeof($ings);$i++){
				$ingCarb=$ings[$i]->calcCarbon();
				if($ingCarb != null){
					if($ingCarb/$totalCarb < 0.05){
						$sumOther=$sumOther+$ings[$i]->calcCarbon();
						$other="['Övrigt', ".$sumOther."],";
					}
					else{
						echo"['".$ings[$i]->name."', ".$ings[$i]->calcCarbon()."],";
					}
				}
			}
			echo $other;?>]);

        // Set chart options
        var options = {'width':600,
                       'height':300,
					   'backgroundColor':{ fill:'transparent' },
					   pieSliceBorderColor:'#e9e9e9',
					   legend:{textStyle: {color: '#7a7a7a'}},
					   colors:['#0068af','#ff7e00','#5cb85c','#ff5740','#72c6ff','#006418','#fffd67','#ffb4fc'],
					   pieSliceBorderColor: 'none',
					   pieHole: 0.3,};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('googleChart'));
        chart.draw(data, options);
      }