<?php
	require_once('./dbconnect.php');

  $sql = "select l.lat, l.lng, l.parkingspace_id, l.parking_space_name, l.parking_space_desc, count(s.parkingspace_id) as total, (1-ifnull(avg(s.is_free),0))*count(s.parkingspace_id) as available from slot s right join parking_space l on s.parkingspace_id = l.parkingspace_id group by l.parkingspace_id;";     
      //include('functions/connection.php');
	$result = mysql_query($sql) or die(mysql_error());
   // $result=$conn->query($query); 
    $locationData = array();
    while ($row = mysql_fetch_assoc($result)) 
    {
      array_push($locationData, $row);
    }
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
  <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
  <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
  <script src="http://maps.google.com/maps/api/js?v=3.exp&libraries=places"></script>
    <script src="http://rawgit.com/allenhwkim/angularjs-google-maps/master/build/scripts/ng-map.min.js"></script>

    <style type="text/css">
      /*CSS for map info-window-Start*/
      #map-canvas {
          margin: 0;
          padding: 0;
          height: 600px;
          max-width: none;
      }
      .gm-style-iw {
          /*width: 350px !important;*/
          top: 10px !important;
          left: 0px !important;
          background-color: #fff;
          box-shadow: 0 1px 6px rgba(178, 178, 178, 0.6);
          border: 1px solid rgba(72, 181, 233, 0.6);
          border-radius: 10px 10px 10px 10px;
      }
      #iw-container {
          margin-bottom: 5px;
      }
      #iw-container .iw-title {
          font-family: 'Open Sans Condensed', sans-serif;
          font-size: 22px;
          font-weight: 400;
          padding: 10px;
          background-color: #48b5e9;
          color: white;
          margin: 0;
          border-radius: 2px 2px 0 0;
      }
      #iw-container .iw-content {
          font-size: 13px;
          line-height: 18px;
          font-weight: 400;
          margin-right: 1px;
          padding: 10px 5px 10px 5px;
          max-height: 200px;
          overflow-y: auto;
          overflow-x: hidden;
      }
      .iw-content img {
          float: right;
          margin: 0 5px 5px 10px; 
      }
      .iw-subTitle {
          font-size: 16px;
          font-weight: 400;
          padding: 0px 0;
      }
      .iw-bottom-gradient {
          position: absolute;
          width: 410px;
          height: 25px;
          bottom: 10px;
          right: 18px;
          background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
          background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
          background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
          background: -ms-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
      }
   /*CSS for map info-window-End*/   
    </style>
	<script type="text/javascript">
    var myApp = angular.module('userHomepageApp', [ 'ngMap' ]);

      myApp.controller('userHomepageCtrl', function($scope, $compile, $http) {
        $scope.setLocations = function() {
          $scope.locations = <?php echo json_encode($locationData); ?>;
        };

        var infowindow = new google.maps.InfoWindow({
            content: "  Selected",
            maxWidth: 500
        });

        $scope.getAvailableSlots = function(locationid) {
          console.log("In getAvailableSlots");
          alert("Will look for slots in "+locationid);
        }

        $scope.showParkingData = function(){
        var infoContent = '<div id="iw-container">' +
          '<div class="" >' +                       
               '<div class="iw-content">' +                 
                 '<div class="iw-subTitle">' +
                    '<p>Location name: ' + this.name.parking_space_name+'</p>' +
                '</div>' +
                 '<div class="iw-subTitle">' +
                    '<p>Location Desc: ' + this.name.parking_space_desc+'</p>' +
                '</div>' +
                '<div class="iw-subTitle">' +
                    '<p>Available Slots: '+ this.name.available+'</p>' +
                '</div>' +  
                '<div class="iw-subTitle">' +
                    '<p>Total Slots: '+ this.name.total+'</p>' +
                '</div>' +                  
                '<div class="iw-subTitle">' +
                '<a href="./getAvailableSlots/locationid='+this.name.locationid+'"><button type="submit" class="btn btn-xs btn-default pull-right" style="background-color:#3B5998; border: 1; color:white; margin-right:10px; margin-bottom:20px" >Check Available Slots</button></a>' +
                '</div>' +
              '</div>' +
              '<div class="iw-bottom-gradient">' +
              '</div>' +            
            '</div>' +
          '</div>';
      infowindow.setContent(infoContent);
          
      google.maps.event.addListener(infowindow, 'domready', function() {
        // Reference to the DIV that wraps the bottom of infowindow
        var iwOuter = $('.gm-style-iw');
        var iwBackground = iwOuter.prev();
        iwBackground.children(':nth-child(2)').css({'display' : 'none'});
      iwBackground.children(':nth-child(4)').css({'display' : 'none'});
        //iwOuter.parent().parent().css({left: '115px'});
        // iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'left: 76px !important;'});
        // iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 76px !important;'});
        iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(72, 181, 233, 0.6) 0px 1px 6px', 'z-index' : '1'});
        var iwCloseBtn = iwOuter.next();
        iwCloseBtn.css({opacity: '1', right: '30px', top: '9px', 'box-shadow': '0 0 5px #3990B9'});
        if($('.iw-content').height() < 140){
          $('.iw-bottom-gradient').css({display: 'none'});
        }
        iwCloseBtn.mouseout(function(){
          $(this).css({opacity: '1'});
        });
      });
  
    infowindow.open($scope.map, this);
    }

        $scope.setLocations();
      });
  </script>
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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<!--
    <div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" class="active"><a href="#">Home</a></li>
            <li role="presentation"><a href="#">About</a></li>
            <li role="presentation"><a href="#">Contact</a></li>
          </ul>
        </nav>
        <h3 class="text-muted">SmartPark</h3>
      </div>

      <div class="jumbotron">
        <h1>Choose Area to Park</h1>-->
        <div id="map-canvas">
      <map center="current-position" zoom="10" on-click="addMarker()" ng-model="map"  style="height:100%">
            <marker position="current-position" title="Reuest Ride" on-click="requestRide()" data-toggle="modal" data-target="#addLifeEventModal"></marker>
            <marker position="current-position" icon="/images/currentLocation.png" title="Request ride">
            </marker>
            <marker ng-repeat="location in locations" name="{{location}}"
              position="{{location.lat}}, {{location.lng}}" title="{{location.locationname}}" on-click="showParkingData()"></marker>           
          </map>
	</div>

    <!-- <table>
      <?php 
      for($i=0; $i<count($locationData);$i++) {
        echo '<tr>';
        echo '<td><a href="/CMPE281/app.php/getLocationData/'.$locationData[$i]['locationid'].'">'.$locationData[$i]['locationname'].'</a></td>';
        echo '<td><a href="/CMPE281/app.php/getLocationData/'.$locationData[$i]['locationid'].'">'.$locationData[$i]['locationdesc'].'</a></td>';
        echo '<td><a href="/CMPE281/app.php/getLocationData/'.$locationData[$i]['locationid'].'">'.$locationData[$i]['total'].'</a></td>';
        echo '<td><a href="/CMPE281/app.php/getLocationData/'.$locationData[$i]['locationid'].'">'.$locationData[$i]['available'].'</a></td>';
        echo '</tr>';
      }


      ?>
                  
    <!--              
	</div>
				
				

      

      <footer class="footer">
        <p>&copy; SmartPark 2015</p>
      </footer>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
