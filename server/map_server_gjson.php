<?php

//The information of the already registered raspberry pi
$raspberry_file = "raspberry_pis.txt";

//Store it in an json array.
$raspberries = json_decode(file_get_contents($raspberry_file), true);
//print_r($raspberries);
//print_r(json_encode($raspberries, JSON_PRETTY_PRINT));

//The size of the $raspberries array, which means the number of raspberry pi inserted.
$count=sizeof($raspberries);

//For storing the results of each raspberry pi
$results=array();

//the item class contains the information for dealing with the item
//The main goal of this class is to store the content of the file

class item
{
      public $name;
      public $count;

      public function __construct($name, $count)
      {
          $this->name = $name;
          $this->count = $count;
      }
}

//The main goal of this array is to record whether there are some people voting or not
//Therefore, I make this array to record whether result file has changed or not compared with before
//Everytime I run this file, I check all of the results
$changes=array();

//This array records the titles of the questionniare of each raspberry pi
$titles=array();

if($count!=0){

        for ($i = 1; $i <=$count; $i++) {
                
		//The votes file to be processed.
		$data_file="./votes/votes_Rpi".$i.".txt";
		//The title of the questionnaire associated to the raspberry pi with its raspberry pi id.
		$title_file="./votes/title_Rpi".$i.".txt";
		$title = file_get_contents($title_file,true);
		array_push($titles,$title);
		//echo "TITLE:";
		//echo $title;

		//If no one votes 
		if(file_exists($data_file)==0){
			$change=0;
			$fp="Null";
			array_push($changes,$change);
		}	
		
		//If someone has already voted
        if(file_exists($data_file)==1&&filesize($data_file)!=0){
			if($fp=="Null"){

			//In this case,the result file is uploaded the first time.
				
				$tmp = compute_votes($data_file);
                		array_push($results,$tmp);
  				$change=1;
				array_push($changes,$change);
				
				//Store this result in the folder called votes_new.
				$fp="./votes_new/votes_Rpi".$i.".txt";
				$current = file_get_contents($data_file);
				file_put_contents($fp, $current);		
			
			}else{
			//In this case,I compared the result with previous voting. 
                        	
				$tmp = compute_votes($data_file);
               			array_push($results,$tmp);
				$fp="./votes_new/votes_Rpi".$i.".txt";
				
				//The content of the current result  file
				$crc1 = strtoupper(dechex(crc32(file_get_contents($data_file))));
				//The content of the previous result file
				$crc2 = strtoupper(dechex(crc32(file_get_contents($fp))));

				if ($crc1!=$crc2) {
				//The two files are not the same
				//So someone votes during the period when this page is refreshing
					
					$change=1;
				
				} else {
				//The two files are the same
				//no one votes during the period when this page is refreshing
					
					$change=0;
				
				}
				
				array_push($changes,$change);
				$current = file_get_contents($data_file);
				file_put_contents($fp, $current);
			}	
          }

        }
}

//print_r($results);

//You can see the changes from this array.
//print_r($changes);

//You can see the titles of the questionnaire from this array.
//print_r($titles);

function compute_votes($data_file){

        //empty array for storing the items
        $items=array();

        //Reading the file line by line

        $lines = file($data_file);

        foreach($lines as $line_num => $line)
        {

                $pos = strpos($line,"=");
                $name = substr($line,0,$pos);

                //Store the count of the vote
                $count = filter_var($line, FILTER_SANITIZE_NUMBER_INT);

                //make a new struct
                $tmp = new item($name,$count);

                //push this in the array of structs
                array_push($items,$tmp);
        }

        //Put the items and the votes in an array
        $data=array();

        foreach ($items as $value)
        {
                $tmp_name=$value->name;
                $tmp_count=$value->count;
                $tmp_element=array("$tmp_name"=>"$tmp_count");
                $data=$data+$tmp_element;
        }

        //sort it as descending order
        arsort($data);

        //This variable is to store the order of the items of the question
        $order=0;
	
	//Put the items and the votes in a order with index 1,2,3,4,....
        //In this case as administrator, you should know how many items you have put for the question.

        //store this result
        $result=array();

        foreach ($data as $key=>$value)
        {
                //$order++;
                //echo  $key." ".$value;
                //The count of votes for each item
                $tmp_result=$key."=".$value;

                //In this way, we can pop it up on the map.
                //The index should be defined in this way.
                //$tmp_order="n";

                //Assign the order to this item
                //$tmp=array("$tmp_order"=>"$tmp_result");

                //$result=$result+$tmp_result;
        	array_push($result,$tmp_result);
	}
//      print_r($data);
//      print_r($result);

        return $result;
}


?>

<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8 />
<title>MAP with GJSON</title>
<meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
<script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.4/mapbox.css' rel='stylesheet' />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<style>
body { margin:0; padding:0; }
#map { position:absolute; top:100; bottom:100; width:100%; height:100%;}
</style>
<style>
.leaflet-popup-content img {
max-width:70%;
}
</style>
</head>

<body>

<script>
$(document).ready(function(){
setInterval(function() {
$("#debugdiv").load("./refresh_pis.php");
}, 3000);
})
</script>

<div id='debugdiv'></div>
<div id='map'></div>

<script>

//Firstly, go to your MapBox Page
//Please put the your accessToken and your map ID of your account in the correct place


L.mapbox.accessToken = 'Your accessToken';
var map = L.mapbox.map('map', 'Your Map ID')
    .setView([47.367, 8.55], 12);

map.on('click', function(e){

        //Store the variables.
        var latitude = e.latlng.lat;
        var longtitude= e.latlng.lng;

        //When the file is empty,so we add the first raspberry pi, the id is 1.
        //If the file is not empty, the id is the length of the string +1.
        if(rpis==null){
                var new_id=1;
        }else{
                var new_id = (rpis.length)+1;
        }
        //alert(latitude+" "+longtitude+" "+new_id);
        //alert(rpis);

        //Display the confirm button.
	//Redirect to the page to insert a raspberry pi.
	//Set the id, latitude and longtitude of the new inserted raspberry pi 
	
        if(confirm("Would you like to add another Raspberry Pi?")==true){
                window.location.href = "./insert_rpi.php?new_id="+new_id+"&latitude="+latitude+"&longtitude="+longtitude;

        }
});


var rpis = <?php echo json_encode($raspberries, JSON_PRETTY_PRINT) ?>;
var votes = <?php echo json_encode($results,JSON_PRETTY_PRINT) ?>;
var color = <?php echo json_encode($changes,JSON_PRETTY_PRINT) ?>;
var questions = <?php echo json_encode($titles,JSON_PRETTY_PRINT) ?>;

var geoJson = new Array();

for (var key in rpis) {
 if (rpis.hasOwnProperty(key)) {
	//alert(color[key]);
 	var properties = new Array();
 	var geometry = new Array();
 	var marker_color = "#bbb";


	if (color[key]== "1"){
		marker_color = "#ff8888";
  	}
	
	var show_result="";
	for (var i in votes[key]){
		if(i==0){
			show_result=show_result+votes[key][i];
		}else{
			show_result=show_result+"<br>"+votes[key][i];
		}
	}
	
		
	if(questions[key]==null){
		geoJson.push({"type":'Feature', "geometry": {"type": 'Point', "coordinates":[parseFloat(rpis[key].longitude), parseFloat(rpis[key].latitude)]},
                              "properties": {"title":rpis[key].id+" "+rpis[key].ssid,
                                             "image": rpis[key].new_photo,
                                             "description": rpis[key].description,
                                             "question":"Questions are not set yet",
                                             "Vote":"No one vote yet",
                                             'marker-color': marker_color}});

	}


 	if(questions[key]!=null&&votes[key]==null){
  		geoJson.push({"type":'Feature', "geometry": {"type": 'Point', "coordinates":[parseFloat(rpis[key].longitude), parseFloat(rpis[key].latitude)]}, 
			      "properties": {"title":rpis[key].id+" "+rpis[key].ssid,
		 	      		     "image": rpis[key].new_photo, 
			                     "description": rpis[key].description,
			                     "question":questions[key],
					     "Vote":"No one vote yet",
			                     'marker-color': marker_color}});
 	}
	
	if(questions[key]!=null&&votes[key]!=null){
		geoJson.push({"type":'Feature', "geometry": {"type": 'Point', "coordinates":[parseFloat(rpis[key].longitude), parseFloat(rpis[key].latitude)]},
                              "properties": {"title": rpis[key].id+" "+rpis[key].ssid,
                                             "image": rpis[key].new_photo,
                                             "description": rpis[key].description,
			                     "question":questions[key],
					     "Vote": show_result, 'marker-color': marker_color}});
 	}
 }
}

var myLayer = L.mapbox.featureLayer().addTo(map);

/*
function resetColors() {
    for (var i = 0; i < geoJson.length; i++) {
        geoJson[i].properties['marker-color'] = geoJson[i].properties['old-color'] ||
            geoJson[i].properties['marker-color'];
    }
    myLayer.setGeoJSON(geoJson);
}
*/

myLayer.on('layeradd', function(e) {
    var marker = e.layer,
        feature = marker.feature;

  	var popupContent =  feature.properties.title+'<br><img src="' + feature.properties.image + '" /><br/>'+ feature.properties.description
			                            + "<br> question: <br/>"+feature.properties.question
	                     		    	    + "<br> votes: <br/>"+feature.properties.Vote;
    
    marker.bindPopup(popupContent,{
    closeButton: false,
    minWidth: 160
    });

});

myLayer.setGeoJSON(geoJson);

//$('#debugdiv').append("TEST"+JSON.stringify(geoJson, undefined, 2));

</script>

</body>
</html>


