<?php     
    require_once '../../library/DataManipulation.php';
    require_once '../../library/MySql.php';
    require_once 'functions.php';    
    $data = new DataManipulation();
    //
    $sql = 'SELECT u.id, COUNT(t.id_user) AS qtd_tweets
			FROM users AS u
				JOIN tweets AS t ON (u.id = t.id_user) 			
			GROUP BY u.id
			ORDER BY qtd_tweets DESC
			LIMIT 0, 600';
    $users = $data->find('dynamic', $sql);
    //
    $vetorRobos = array();
    for($i=0; $i< count($users); $i++){  // Varre usuario por usuario
    	// Todos os tweets do usuario atual
    	$sql = 'SELECT latitude, longitude
	    		FROM tweets
	    		WHERE id_user = '.$users[$i]['id'].'   
	    		LIMIT 0, 100';
	    $result = $data->find('dynamic', $sql);	
	    //
	    if(count($result) > 50){	    	
	    	$robo = 1;
		    for ($j=1; $j< count($result); $j++){ 		    	
		    	if($result[0]['latitude'] != $result[$j]['latitude'] || $result[0]['longitude'] != $result[$j]['longitude']){ // Nao eh robo 
		    		$robo = 0;		    		
		    		break;
		    	}
		    	
		    }		    
		    if($robo == 1){ // É robo, insere id no vetor de robôs
		    	echo '<br>Eh robo';
		    	array_push($vetorRobos, $users[$i]['id']);
		    }		    
		}    	
    }

    // DELETAR OS ROBOS
    foreach ($vetorRobos as $key => $value){
    	// Deleta da tabela users
    	$sql = 'DELETE FROM users WHERE id = '.$value;
    	$data->executaSQL($sql);

    	// Deleta da tabela tweets
		$sql = 'DELETE FROM tweets WHERE id_user = '.$value;
    	$data->executaSQL($sql);    	
    }    

    echo '<br/><br/><h2 style="text-align: center;">'.count($vetorRobos).' Usuários robôs foram removidos do Banco de Dados!</h2>';
?>