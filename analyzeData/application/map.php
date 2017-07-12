<?php
	require_once 'readData.php';
	//
	$centroidesMapa = [];
    $i = 0;
    foreach ($centroides as $key => $value){
        $coord = explode(', ', $value);
        //
        $centroidesMapa[$i]['id']        = $i;
        $centroidesMapa[$i]['tipo']      = 0; // Para indicar no mapa que é a centroide de um local
        $centroidesMapa[$i]['centroide'] = $key; 
        $centroidesMapa[$i]['lat']       = $coord[0];
        $centroidesMapa[$i]['lng']       = $coord[1];
        //
        $i++;
    }
    //
    $pontosMapa = [];
    for ($i=0; $i < count($tweets); $i++){ 
    	$pontosMapa[$i]['id']  = $i;
    	$pontosMapa[$i]['lat'] = $tweets[$i]['latitude'];
        $pontosMapa[$i]['lng'] = $tweets[$i]['longitude'];
    }
?>

<script>    	
	function initMap() {
		// MAPA 1
		var map = new google.maps.Map(document.getElementById('map1'), {
			zoom: 3,
			center: {lat: -27.110607, lng: -52.616733}
		});
		//
		var markers = locations.map(function(location, i) {
			return new google.maps.Marker({
				position: location,
				label: location.centroide            
			});
		});
		//
		var markerCluster = new MarkerClusterer(map, markers, {
			imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
		});

		/// MAPA 2 ///
		var map2 = new google.maps.Map(document.getElementById('map2'), {
			zoom: 3,
			center: {lat: -27.110607, lng: -52.616733}
		});
		//
		var markers2 = locations2.map(function(location2, i) {
			return new google.maps.Marker({
				position: location2,
				label: location2.centroide            
			});
		});
		//
		var markerCluster = new MarkerClusterer(map2, markers2, {
			imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
		});
	}
	//
	var locations  = <?php echo json_encode($centroidesMapa, JSON_NUMERIC_CHECK); ?>;
	var locations2 = <?php echo json_encode($pontosMapa, JSON_NUMERIC_CHECK); ?>;
	
</script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmynBIfp8nLSAmndrUB3kmwMnSZiS1Z6k&amp;&callback=initMap">
</script>