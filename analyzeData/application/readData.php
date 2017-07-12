<?php
	require_once '../../library/DataManipulation.php';
    require_once '../../library/MySql.php';
    $data = new DataManipulation();    
    
    // PARAMETROS
    if(!$_POST['usuario'])
        $usuario = 'JuliaCaoGuia';
    else
        $usuario = $_POST['usuario'];
    if(!$_POST['raioAgrupamento'])
        $raioAgrupamento = 0.05;
    else
        $raioAgrupamento = $_POST['raioAgrupamento'];
    //
    // Usuario, apenas para exibir o nick
    $sql = 'SELECT id, screen_name, location
            FROM users                 
            WHERE screen_name = "'.$usuario.'" ';
    $users = $data->find('dynamic', $sql);    

    $sql = 'SELECT id, latitude, longitude, time
            FROM tweets                 
            WHERE id_user = '.$users[0]['id'].'  
            ORDER BY time ASC';
    $tweets = $data->find('dynamic', $sql);   

    // From locaisUsuarios
    $vetCoorNomes = [];
    $centroides   = [];
    $letra        = 'A';
    //
    for ($i=0; $i< count($tweets); $i++){
        $vetCoorNomes[$tweets[$i]['latitude'].', '.$tweets[$i]['longitude']] = 0;
    }
    //
    $dom_manha = array(); $dom_tarde = array(); $dom_noite = array();
    $seg_manha = array(); $seg_tarde = array(); $seg_noite = array();
    $ter_manha = array(); $ter_tarde = array(); $ter_noite = array();
    $qua_manha = array(); $qua_tarde = array(); $qua_noite = array();
    $qui_manha = array(); $qui_tarde = array(); $qui_noite = array();
    $sex_manha = array(); $sex_tarde = array(); $sex_noite = array();
    $sab_manha = array(); $sab_tarde = array(); $sab_noite = array();
?>