<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Redup</title>
<script src="jquery-1.10.2.min.js"></script>
<style type="text/css">
@font-face {
	font-family: helvetica;
	src: url('Helvetica.otf');
}
.arrow1 {
	background:url('arrow.png') no-repeat 0 0;
	width:225px;
	height:225px;
	margin:auto;
}
.arrow2 {
	background:url('arrow.png') no-repeat -225px 0;
	width:225px;
	height:225px;
	margin:auto;
}
#platform {	
	font-family:helvetica, Helvetica, Arial, sans-serif;
	font-size:36px;
	text-align:center;
	height:400px;
	width:230px;
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-200px;
	margin-left:-115px;
	text-align:center;
}
#refresh {
	height:100px;
	width:100px;
	background:url('refresh.png') no-repeat 0 0;
	bottom:0px;
	margin:auto;
	cursor:pointer;
}
#refresh:hover {
	background:url('refresh.png') no-repeat -100px 0;	
}
body {
	background-color:#C00;	
}

</style>
</head>
<body>
<div id="platform"></div>
<div id="data"></div>

<script type="text/javascript">
Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

//$("#data").load('http://developer.mbta.com/lib/rthr/red.json');
var data=<?php
function getter($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //curl_setopt($ch, CURLOPT_POST, 1);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

echo getter('http://developer.mbta.com/lib/rthr/red.json');

?>;
var tripsize=Object.size(data.TripList.Trips);

var display="";
var platform="???<br>Unknown";
var time=99999999;

for (var i=0;i<tripsize;i++) {
	
	if(data['TripList']['Trips'][i]['Destination']=='Alewife') {
		display+="<br> "+data['TripList']['Trips'][i]['Destination'];
		var predictions=Object.size(data['TripList']['Trips'][i]['Predictions']);
		for (var ii=0;ii<predictions;ii++) {
			if (data['TripList']['Trips'][i]['Predictions'][ii]['Stop']=='JFK/UMass') {
				if (data['TripList']['Trips'][i]['Predictions'][ii]['Seconds']<time) {
					time=data['TripList']['Trips'][i]['Predictions'][ii]['Seconds'];
					if (data['TripList']['Trips'][i]['Predictions'][ii]['StopID']=='70086') {
						platform="Ashmont<br>Platform<div class=\"arrow1\"></div>";
					} else if (data['TripList']['Trips'][i]['Predictions'][ii]['StopID']=='70096') {
						platform="Braintree<br>Platform<div class=\"arrow2\"></div>";
					} else {
						platform="???<br>Unknown";
					}
				}
				display+="<br>--"+data['TripList']['Trips'][i]['Predictions'][ii]['Seconds']+" to "+data['TripList']['Trips'][i]['Predictions'][ii]['Stop']+" : "+data['TripList']['Trips'][i]['Predictions'][ii]['StopID'];
			}
			
		}
			
	}
}
platform+="<div id=\"refresh\" onClick=\"location.reload();\"></div>";
$("#platform").html(platform);
//$("#data").html(display);
</script>
</body>
</html>