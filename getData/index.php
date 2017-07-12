<?php
	// Conecta ao BD	
	require_once '../library/DataManipulation.php';
    require_once '../library/MySql.php';
    $data = new DataManipulation();    
	
	// Total de usuarios
	$sql = 'SELECT COUNT(*) AS qtd 
			FROM users';
	$usuarios = $data->find('dynamic', $sql);
	
	// Total de tweets
	$sql = 'SELECT COUNT(*) AS qtd 
			FROM tweets';
	$tweets = $data->find('dynamic', $sql);
	
	// Alguns usuarios
	$sql = 'SELECT u.id, u.screen_name, u.name, u.location, COUNT(t.id_user) AS qtd_tweets
			FROM users AS u
				JOIN tweets AS t ON (u.id = t.id_user) 	
			GROUP BY u.id
			ORDER BY qtd_tweets DESC
			LIMIT 0, 500';		
	$result = $data->find('dynamic', $sql);	
?>

<html>

<head>
<title>Estudo de caso de análise do perfil de mobilidade de usuários do Twitter</title>
</head>
	
<body>
	<br />
	<h1 style="text-align: center;">Estudo de caso de análise do perfil de mobilidade de usuários do Twitter</h1>
	<br />

	<h2 style="text-align: center;">Busca por loalizacao: </h2>	
	<center>
		<form method="POST" action="searchOnTwitter.php" id="myForm1" >
			<table style="text-align: left;">
				<tr>
					<th>Latitude:</th>
					<th>Longitude:</th>
					<th>Raio:<span style="font-size: 12px; color:#a3a3a3;"> [Km]</span></th>
					<th>Palavra:</th>
				</tr>
				<tr>
					<td><input type="text" name="latitude" value="-23.540166" /></td>
					<td><input type="text" name="longitude" value="-46.633933" /></td>
					<td><input type="text" name="raio" value="500" /></td>
					<td><input type="text" name="palavra" placeholder="Opcional" /></td>
					<td><button type="button" onclick="envia('myForm1');">Buscar no twitter</button></td>			
				</tr>
			</table>
		</form>
	</center>

	<br />
	<div style="text-align: center;">
		<h2 style="">Busca por usuarios:</h2>
		
		<div style="position: relative; top: -10px; font-size: 14px; color:#a3a3a3;"> 
			[Usuarios ja selecionados pelo sistema, que possuem pelo menos 1 tweet com a localizacao exata (LA, LO).]
		</div>

		<div style="font-size: 14px; ">[Total de usuarios: <b><?php echo $usuarios[0]['qtd']; ?></b>], [Total de tweets: <b><?php echo $tweets[0]['qtd']; ?></b>]</div>
		
	</div>
	<center>
		<br/>		
		<button type="button" onclick="envia('myForm2');">Buscar no twitter</button>
		<br/><br/>
		<form method="POST" action="analise.php" id="myForm2" >
			<input type="hidden" name="busca_manual" value="1" />

			<table style="text-align: left;">
				<tr>
					<th>Sel.:</th>
					<th >Screen name:</th>
					<th >Name:</th>
					<th >Location:</th>
					<th >Qtd. Tweets:</th>
				</tr>
				<?php
					for($i=0; $i< count($result); $i++){
						echo '
						<tr>
							<td><input type="checkbox" name="user_nick[]" id="user_'.$i.'" value="'.$result[$i]['screen_name'].'" /></td>
							<td>'.$result[$i]['screen_name'].'</td>
							<td>'.$result[$i]['name'].'</td>
							<td>'.$result[$i]['location'].'</td>							
							<td>'.$result[$i]['qtd_tweets'].'</td>							
						</tr>';
					}
				?>							
			</table>
			<br />
			<button type="button" onclick="envia('myForm2');">Buscar no twitter</button>
			<br /><br /><br /><br />
		</form>
	</center>

</body>

</html>

<script type="text/javascript">
	
	function envia(id){
		document.forms[id].submit();			
	}

	function sleep(sleepDuration){
	    var now = new Date().getTime();
	    while(new Date().getTime() < now + sleepDuration){ /* do nothing */ } 
	}
</script>