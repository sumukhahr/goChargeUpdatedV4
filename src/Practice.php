<?php
include("fusioncharts.php");
 require_once('./dbconnect.php');
 $sql= "SELECT COUNT(userid) as count,ps.`parking_space_desc`as place FROM `slot` 
          LEFT JOIN `parking_space`ps ON ps.`parkingspace_id`=slot.`parkingspace_id`
          GROUP BY ps.`parking_space_desc`";
		  
		  
   $sql= "SELECT  SUM(price_per_hour) as money,day_of_week FROM pricing GROUP BY day_of_week";

 $result = mysql_query($sql) or die(mysql_error());
				$resultData = array();
				while ($row = mysql_fetch_assoc($result)) 
				{
					array_push($resultData, $row);
				}
				   
			?>
				
<html>
<head>
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="js/highcharts.js"></script>
	<script src="js/exporting.js"></script>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/favicon.ico">

    <title>Narrow Jumbotron Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->
	<link href="bootswatch/paper/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron-narrow.css" rel="stylesheet">

	<!-- Custom styles for SmartPark -->
    <link href="css/style.css" rel="stylesheet">
	
	<!-- beautiful fonts -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,300" rel="stylesheet" type="text/css">
	
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>
    
<script type="text/javascript">

		var chartData = <?php echo json_encode($resultData); ?>;
		
		var categoryNames = new Array();
		var values = new Array();
		var total = new Array();
		for(var i=0; i<chartData.length; i++) {
			categoryNames.push(chartData[i]['d']);
			values.push(parseInt(chartData[i]['day_of_week']));	
			total.push(parseInt(chartData[i]['money']));
		}
		//console.log(JSON.stringify(categoryNames));
		
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'User count'
        },
        subtitle: {
            text: ' '
        },
        xAxis: {
            categories: categoryNames,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' '
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            /*y: 80,*/
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Total revenue',
            data: values
        }]
    });
	$('#container2').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Total Revenue Statistics'
        },
        subtitle: {
            text: ' '
        },
        xAxis: {
            categories: categoryNames,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' '
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            /*y: 80,*/
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Total Revenue',
            data: total
        }]
    })
    });
		</script>	
		
</head>
<body>
<div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" class="active"><a href="login.php">Home</a></li>
			<li role="presentation"><a href="vendorSpaceRegister.php">Vendor</a></li>
            <li role="presentation"><a href="about.php">About</a></li>
            <li role="presentation"><a href="contact.php">Contact</a></li>
          </ul>
        </nav>
        <h3 class="text-muted">Go Charge</h3>
      </div>
      <div class="jumbotron">
			<div id="container" style="width:600px; height: 400px; margin: 0 auto"></div>
				<div id="container2" style="width:600px; height: 400px; margin: 0 auto"></div>
				</div>
 <footer class="footer">
        <p>&copy; GoCharge 2016</p>
      </footer>

    </div>
</body>
</html>							   