<meta http-equiv="refresh" content="60">

<?php
	require_once('TwitterAPIExchange.php');
 
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$settings = array(
	    'oauth_access_token' => "216785886-FUCiGBg3IQedt0FvN0fBxU8AbJVK8gltaFFqXgfk",
	    'oauth_access_token_secret' => "y1emmAYDdKFhTfDg5Fx8y0BUS2n6UeUucKZBbklU8KTBs",
	    'consumer_key' => "AASmCEYKKNl1CVZxotB1huO1n",
	    'consumer_secret' => "D0ywQXEVUUU5eyOhVcQgu7vCjD0zuwcylUwO5nfazyTg5FQ6iM"
	);

	echo "<h2 style='text-align: center;'>Busca por localizacao</h2><a href='index.php'>Voltar para o inicio</a><br/><br/>";
	$url = "https://api.twitter.com/1.1/search/tweets.json";

	$requestMethod = "GET";

	if(!$_POST['palavra'])
		$_POST['palavra'] = '';
	
	if(!$_POST['raio'])
		$_POST['raio'] = '500';


	if(!$_POST['latitude']){
		$lat_lon = valida_localizacao();
		//
		$tmp                = explode(',', $lat_lon);
		$_POST['latitude']  = $tmp[0];
		$_POST['longitude'] = $tmp[1];		
	}
	
	//
	$getfield = '?q='.$_POST['palavra'].'&geocode='.$_POST['latitude'].','.$_POST['longitude'].','.$_POST['raio'].'km&geo=true&count=500'; 

	//  Make that call to Twitter
	$twitter = new TwitterAPIExchange($settings);
	
	$twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest();
	
    $string = json_decode($twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest(),$assoc = TRUE);

    if($string["errors"][0]["message"] != "") {
    	echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";
    	
    	echo "<meta http-equiv='refresh' content='60'>";	
    	//
    	exit();
    }
	//
	$totalGer = 0;
	$totalGeo = 0;
	$totalCoo = 0;
	$totalPla = 0;
	//
	
	foreach ($string['statuses'] as $tweet){
			//
			echo "Screen name: ".$tweet['user']['screen_name']."<br/>";
			echo "Data: ".$tweet['created_at']."<br/>";
			echo "Id: ".$tweet['id']."<br/>";
			echo "Descricao: ".$tweet['text']."<br/>"; 
			//	
			if($tweet['geo']){
				//
				$id[$totalGeo]          = $tweet['user']['id'];					
				$screen_name[$totalGeo] = $tweet['user']['screen_name'];					
				$name[$totalGeo]        = $tweet['user']['name'];					
				$location[$totalGeo]    = $tweet['user']['location'];					
				$description[$totalGeo] = $tweet['user']['description'];	
				$latitude[$totalGeo]	= $tweet['geo']['coordinates'][0];
				$longitude[$totalGeo]	= $tweet['geo']['coordinates'][1];			
				//
				$source[$totalGeo]      = strip_tags($tweet['source']); // Vem com links, retira tag <a>
				$tweet['source']        = strip_tags($tweet['source']); // apenas para exibir aqui nessa tela (retira tags, pois vem com links)
				//
				echo "<b>Geocode: ".$tweet['geo']['coordinates'][0].", ".$tweet['geo']['coordinates'][1]."</b><br/>";
				$totalGeo++;
			}
			//	
			if($tweet['coordinates']){		
				echo "Coordinates: ".$tweet['coordinates']['coordinates'][0].", ".$tweet['coordinates']['coordinates'][1]."<br/>";
				$totalCoo++;			
			}
			//	
			if($tweet['place']){
				echo "Place: ";				
				foreach($tweet['place']['bounding_box']['coordinates'][0] as $p) 
					echo " | ".$p[0].", ".$p[1]." |, ";
				echo "<br/>";
				$totalPla++;
			}
			//
			if($tweet['source']){
				echo '<b>Source: '.$tweet['source'].'</b><br />';
			}
			echo "<br/>";
			//
			$totalGer++;	
	}
	
	//
	echo "<b>Total Geral:</b> ".$totalGer."<br/>";
	echo "<b>Total Geocode:</b> ".$totalGeo."<br/>";
	echo "<b>Total Coordinates:</b> ".$totalCoo."<br/>";
	echo "<b>Total Place:</b> ".$totalPla."<br/>";

	// Se houver pelo menos 1 usuario com geolocalização, enviar para analise	
	if(count($id) > 0){
		echo 'Pelo menos um usuario com Geolocalizacao, analisar ...';
		echo '
		<form action="analise.php" method="POST" id="myForm" >';
			for($i=0; $i< count($id); $i++){
				echo '
				<input type="hidden" name="id[]" value="'.$id[$i].'" />
				<input type="hidden" name="screen_name[]" value="'.$screen_name[$i].'" />
				<input type="hidden" name="name[]" value="'.$name[$i].'" />
				<input type="hidden" name="location[]" value="'.$location[$i].'" />
				<input type="hidden" name="description[]" value="'.$description[$i].'" />
				<input type="hidden" name="latitude[]" value="'.$latitude[$i].'" />
				<input type="hidden" name="longitude[]" value="'.$longitude[$i].'" />
				<input type="hidden" name="source[]" value="'.$source[$i].'" />';				
			}
		echo '			
		</form>';
	}	

	function valida_localizacao(){
		date_default_timezone_set('Brazil/East');
		$minuto_atual = date('i', time()); // Minuto
		//
		switch ($minuto_atual) {			
			case '01':
			case '02':
			case '03':
			case '04':
				$latitude_longitude = '-7.141050,-68.632179';				
				break;
			case '05':
			case '06':
			case '07':
			case '08':
				$latitude_longitude = '-0.536984,-62.955181';				
				break;
			case '09':
			case '10':
			case '11':
			case '12':
				$latitude_longitude = '-8.831881,-60.960682';				
				break;
			case '13':
			case '14':
			case '15':
			case '16':
				$latitude_longitude = '-2.172147,-54.166151';				
				break;
			case '17':
			case '18':
			case '19':
			case '20':
				$latitude_longitude = '-6.158245,-46.609180';				
				break;
			case '21':
			case '22':
			case '23':
			case '24':
				$latitude_longitude = '-7.825506,-39.271439';				
				break;
			case '25':
			case '26':
			case '27':
			case '28':
				$latitude_longitude = '-10.733276,-52.739958';				
				break;
			case '29':
			case '30':
			case '31':
			case '32':
				$latitude_longitude = '-14.146747,-43.948410';				
				break;
			case '33':
			case '34':
			case '35':
			case '36':
				$latitude_longitude = '-14.751730,-55.624481';				
				break;
			case '37':
			case '38':
			case '39':
			case '40':
				$latitude_longitude = '-18.781051,-44.079220';				
				break;
			case '41':
			case '42':
			case '43':
			case '44':
			case '45':
				$latitude_longitude = '-17.285106,-50.303950';				
				break;
			case '46':
			case '47':
			case '48':
			case '49':
			case '50':
				$latitude_longitude = '-20.412204,-53.077991';				
				break;
			case '51':
			case '52':
			case '53':
			case '54':
			case '55':
				$latitude_longitude = '-24.919293,-49.150579';				
				break;
			default:			
				$latitude_longitude = '-29.397007,-52.658032';				
				break;			
		}		
		//
		return $latitude_longitude;
	}	
?>

<script type="text/javascript">
	document.forms['myForm'].submit();
</script>