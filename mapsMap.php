<?php
$server = substr(str_replace('\\', '/', __DIR__), strlen($_SERVER['DOCUMENT_ROOT']));
?>

function doSwitchImage(images, mapId, next) {
	/* This is the image that we switch the source of */
	var mainImage = document.getElementById("mainImg" + mapId);

	/* Get the current index of the image */
	var currentIndex = parseInt(mainImage.getAttribute("data-imgNum"));

	if (next) {
		if (currentIndex == images.length - 1) {
			mainImage.src = images[0];
			mainImage.setAttribute("data-imgNum", 0);
		} else {
			mainImage.src = images[currentIndex + 1];
			mainImage.setAttribute("data-imgNum", currentIndex + 1);
		}
	} else {
		if (currentIndex == 0) {
			mainImage.src = images[images.length - 1];
			mainImage.setAttribute("data-imgNum", images.length - 1);
		} else {
			mainImage.src = images[currentIndex - 1];
			mainImage.setAttribute("data-imgNum", currentIndex - 1);
		}
	}
}

/* Gets the list of images that we need to switch between */
function switchImage(mapId, next, mapUrl) {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	        var images = JSON.parse(this.responseText);
			images.splice(0, 0, mapUrl);

			doSwitchImage(images, mapId, next);
		}
	};
	xmlhttp.open("GET", "<?php echo $server?>/readdir.php?mapId=" + mapId, true);
	xmlhttp.send();
}

/* Hides the arrow if there's only the one image for a map */
function hideArrows(mapId, side) {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	        var images = JSON.parse(this.responseText);

			if (images.length != 0) {
				/* Hide the arrow as we only have the main map image */
				document.getElementById("arrow" + side + mapId).style.display = "";
			}
		}
	};
	xmlhttp.open("GET", "<?php echo $server?>/readdir.php?mapId=" + mapId, true);
	xmlhttp.send();
}

/* Initialize map */
var mymap = L.map('mapid').setView([54, -125], 5);

/* Use these tiles, for now */
var OpenStreetMap_Mapnik = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	maxZoom: 19,
	attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mymap);

/* Create a cluster group */
var markers = L.markerClusterGroup({
	showCoverageOnHover: false,
	maxClusterRadius: 30,
	disableClusteringAtZoom: 10,
	spiderfyOnMaxZoom: false,
});

/* For each club, get their maps and add them to the map */
for (var i = 0; i < clubIds.length; i++) {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	        allMaps = JSON.parse(this.responseText);

			for (var i = 0; i < allMaps.maps.length; i++) {
				/* Create marker */
				var marker = L.marker([allMaps.maps[i].lat, allMaps.maps[i].lng]);

				/* Create the code for the popup */
				var wjrImage = allMaps.maps[i].url + "../../../rendering/" + allMaps.maps[i].id + "/400x600";

				var popupCode = "<h3><a href='" + allMaps.maps[i].url + "'>" + allMaps.maps[i].name + "</a></h3>";
				popupCode += "<div class='holder'>";
				popupCode += "<img class='arrow' id='arrowLeft" + allMaps.maps[i].id + "' src='<?php echo $server?>/arrow-left-small.png' onclick='switchImage(" + allMaps.maps[i].id + ", false, \"" + wjrImage + "\")' onload='hideArrows(" + allMaps.maps[i].id + ", \"Left\")' style='display: none;'/>";
				popupCode += "<img class='mainImg' id='mainImg" + allMaps.maps[i].id + "' src='" + wjrImage + "' data-imgNum='0'/>";
				popupCode += "<img class='arrow' id='arrowRight" + allMaps.maps[i].id + "' src='<?php echo $server?>/arrow-right-small.png' onclick='switchImage(" + allMaps.maps[i].id + ", true, \"" + wjrImage + "\")' onload='hideArrows(" + allMaps.maps[i].id + ", \"Right\")' style='display: none;'/>";
				popupCode += "</div>";
				popupCode += "<p>" + allMaps.maps[i].club.name + "</p>";

				/* Bind the popup */
				marker.bindPopup(popupCode);

				/* Add the marker to the cluster group */
				markers.addLayer(marker);
			}
	    }
	};
	xmlhttp.open("GET", "https://whyjustrun.ca/api/maps.json?max_lat=180&min_lat=-180&max_lng=180&min_lng=-180&club_id=" + clubIds[i], true);
	xmlhttp.send();
}

/* Add the cluster group to the map */
mymap.addLayer(markers);
