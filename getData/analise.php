<?php 
	// Conecta ao BD
	include('../library/conecta.php');

	require_once('TwitterAPIExchange.php');
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$settings = array(
	    'oauth_access_token' => "216785886-FUCiGBg3IQedt0FvN0fBxU8AbJVK8gltaFFqXgfk",
	    'oauth_access_token_secret' => "y1emmAYDdKFhTfDg5Fx8y0BUS2n6UeUucKZBbklU8KTBs",
	    'consumer_key' => "AASmCEYKKNl1CVZxotB1huO1n",
	    'consumer_secret' => "D0ywQXEVUUU5eyOhVcQgu7vCjD0zuwcylUwO5nfazyTg5FQ6iM"
	);
	//
	//	
	echo "<h2 style='text-align: center;'>Busca por usuarios</h2><a href='index.php'>Voltar para o inicio</a><br/><br/>";
	
	// Apenas para exibir na tela os tweets selecionados
	if(count($_POST['id']) > 0){
		echo '<h3>Tweets selecionados na Busca por Localizacao:</h3>';
		for($i=0; $i< count($_POST['id']); $i++){			
			echo '
			Tweet id: '.$_POST['id'][$i].'<br/> 
			Screen name: '.$_POST['screen_name'][$i].'<br/>
			Name: '.$_POST['name'][$i].'<br/>
			Location: '.$_POST['location'][$i].'<br/>
			Description: '.$_POST['description'][$i].'<br/>
			<b>Geocode: '.$_POST['latitude'][$i].', '.$_POST['longitude'][$i].'</b><br/>
			Source: '.$_POST['source'][$i].'<br/><br/>';
		}
		echo ' -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- // -- <br/><br/>';
	}

	if($_POST['busca_manual'] == 1){ // selecionou os usuarios manualmente na tela inicial
		$var = 'user_nick';
	}else{							 // usuarios vindos da busca por localizacao, usuarios com geolocalizacao
		$var = 'screen_name';
	}
	//
	for($i=0; $i< count($_POST[$var]); $i++){
		$query .= $_POST[$var][$i]." OR ";
	}
	$query = substr($query, 0, strlen($query)-4);
	//	
	$url = "https://api.twitter.com/1.1/search/tweets.json";
	$requestMethod = "GET";
	$getfield = '?from='.$query.'&count=100';
	
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
    	
    	//
    	echo "<meta http-equiv='Refresh' CONTENT='0;URL=".$url_page."/mobilidade/getData/searchOnTwitter.php'>";	
    	//echo "<meta http-equiv='Refresh' CONTENT='searchOnTwitter.php'>";	
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
			$source[$totalGeo] = strip_tags($tweet['source']);
			//
			echo "Screen name: ".$tweet['user']['screen_name']."<br/>";
			echo "Data: ".$tweet['created_at']."<br/>";
			echo "Id: ".$tweet['id']."<br/>";
			echo "Descricao: ".$tweet['text']."<br/>"; 
			echo "Source: ".$source[$totalGeo]."<br/>";
			//	
			if($tweet['geo']){	
				$id[$totalGeo]        = $tweet['id'];
				$id_user[$totalGeo]   = $tweet['user']['id'];
				$text[$totalGeo]      = $tweet['text'];
				$latitude[$totalGeo]  = $tweet['geo']['coordinates'][0];
				$longitude[$totalGeo] = $tweet['geo']['coordinates'][1];
				$time[$totalGeo]      = date('Y-m-d H:i:s', strtotime($tweet['created_at']));				
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
				$users_with_geo[$totalPla] = $tweet['user']['screen_name'];
				foreach($tweet['place']['bounding_box']['coordinates'][0] as $p) 
					echo " | ".$p[0].", ".$p[1]." |, ";
				echo "<br/>";
				$totalPla++;
			}

			echo "<br/>";
			//
			$totalGer++;	
	}
	date_default_timezone_set('America/Sao_Paulo');
	
	//
	echo "<b>Total Geral:</b> ".$totalGer."<br/>";
	echo "<b>Total Geocode:</b> ".$totalGeo."<br/>";
	echo "<b>Total Coordinates:</b> ".$totalCoo."<br/>";
	echo "<b>Total Place:</b> ".$totalPla."<br/>";
	
	$conecta = mysql_connect($server, $user, $passw) or print (mysql_error()); 
	mysql_select_db($database, $conecta) or print(mysql_error()); 		
	// insert users
	for($i=0; $i< count($_POST['id']); $i++){		
		$sql = 'INSERT INTO users (id, screen_name, name, location, description) VALUES ('.$_POST['id'][$i].', "'.$_POST['screen_name'][$i].'", "'.$_POST['name'][$i].'", "'.$_POST['location'][$i].'", "'.$_POST['description'][$i].'")';		
		mysql_query($sql, $conecta);
	}
	// Tweets
	for($i=0; $i< $totalGeo; $i++){		
		$sql = 'INSERT INTO tweets (id, id_user, text, latitude, longitude, source, time) VALUES ('.$id[$i].', '.$id_user[$i].', "'.$text[$i].'", '.$latitude[$i].', '.$longitude[$i].', "'.$source[$i].'", "'.$time[$i].'")';		
		mysql_query($sql, $conecta);
	}
	mysql_close($conecta); 

	echo "<meta http-equiv='Refresh' CONTENT='0;URL=".$url_page."/mobilidade/getData/searchOnTwitter.php'>";	
		
	
?>