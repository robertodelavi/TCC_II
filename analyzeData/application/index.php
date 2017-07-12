<?php 
    require_once 'header.php';
    require_once 'readData.php';
    require_once 'functions.php';        
    //
    $totalRecalculos = 0;
    do{                 
        $centroidAntes = $centroides;
        mapeiaLocais($raioAgrupamento);
        $totalRecalculos++;      
    }while($centroidAntes != $centroides);
    //
    echo '    
    <link rel="stylesheet" href="style.css" type="text/css">

    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <title>Estudo de caso de mobilidade de usuários do Twitter</title>        
    </head>

    <h1>DADOS USUÁRIO</h1>

    <!-- PARAMETROS -->    
    <form method="post">
        <b>Usuário:</b><br>
        <input type="text" name="usuario" value="'.$_POST['usuario'].'" />
        <br/><br/>
        <b>Raio de agrupamento (km):</b><br>
        <input type="text" name="raioAgrupamento" value="'.$_POST['raioAgrupamento'].'" />
        <input type="submit" value="Enviar parâmetros">
    </form>

    <h2>
        '.$users[0]['id'].' - '.$users[0]['screen_name'].'<br/>
        Raio de agrupamento: <b>'.$raioAgrupamento.' Km</b>
    </h2>';
    
    // EXIBE INFORMAÇOES TEMPORAIS
    dadosTemporais($users[$i]['id'], $tweets, $raioAgrupamento);
    
    // EXIBE TABELA COM OS CHARTS
    montaCharts();    

    require_once 'charts.php';
    echo '
    <div id="chartdiv"></div>';

    // EXIBE MAPAS
    require_once 'map.php';
    echo '   
    <h3>Mapa com todas as postagens do usuário</h3>
    <div id="map2"></div>

    <h3>Mapa com todos os locais do usuário</h3>
    <div id="map1"></div>';

    ///////////////////////////////////////////////////// FUNÇÕES PARA EXIBIÇÕES /////////////////////////////////////////////////////
    function montaCharts(){
        $ind = 0;
        $label_dia   = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
        $label_turno = array('Manhã', 'Tarde', 'Noite');
        echo '    
        <table>';
        for ($j=0; $j< count($label_dia); $j++){        // Dias da semana
            echo '
            <tr>                    
                <th>Dia</th>
                <th>Manhã</th>
                <th>Tarde</th>
                <th>Noite</th>                             
            </tr>
            <tr>
                <th>'.$label_dia[$j].'</th>';
                for ($k=0; $k< count($label_turno); $k++){  // Turnos do dia
                    echo '                         
                    <th><div id="ct-chart_'.$ind.'" class="ct-perfect-fourth" style="width: 500px; height: 500px;" ></div></th>';
                    $ind++;
                }
            echo '    
            </tr>';            
        }
        echo '    
        </table>';
    }
    
    // Exibe toda a parte temporal de cada usuario
    function dadosTemporais($usuario, $result, $raioAgrupamento){        
        //        
        $numSemana = 0; // Flag para controlar quando troca a semana 
        $semana    = 0; 
        //
        for ($i=0; $i< 30; $i++){ 
            $dom[$i] = array(); 
            $seg[$i] = array();
            $ter[$i] = array();
            $qua[$i] = array();
            $qui[$i] = array();
            $sex[$i] = array();
            $sab[$i] = array();
        }

        // Adiciona as coordenadas em cada vetor separado por dias da semana, semana por semana
        for ($i=0; $i< count($result); $i++){     
            switch (diaDaSemana($result[$i]['time'])){
                case '0': // Domingo    
                    if(diaDaSemana($result[$i]['time']) < $numSemana) $semana++; // Trocou a semana                  
                    array_push($dom[$semana], $result[$i]['id'], $result[$i]['latitude'], $result[$i]['longitude'], $result[$i]['time']);
                    break;
                case '1': // Segunda    
                    if(diaDaSemana($result[$i]['time']) < $numSemana) $semana++; // Trocou a semana                 
                    array_push($seg[$semana], $result[$i]['id'], $result[$i]['latitude'], $result[$i]['longitude'], $result[$i]['time']);
                    break;
                case '2': // Terça    
                    if(diaDaSemana($result[$i]['time']) < $numSemana) $semana++; // Trocou a semana                 
                    array_push($ter[$semana], $result[$i]['id'], $result[$i]['latitude'], $result[$i]['longitude'], $result[$i]['time']);
                    break;
                case '3': // Quarta    
                    if(diaDaSemana($result[$i]['time']) < $numSemana) $semana++; // Trocou a semana                 
                    array_push($qua[$semana], $result[$i]['id'], $result[$i]['latitude'], $result[$i]['longitude'], $result[$i]['time']);
                    break;
                case '4': // Quinta    
                    if(diaDaSemana($result[$i]['time']) < $numSemana) $semana++; // Trocou a semana                 
                    array_push($qui[$semana], $result[$i]['id'], $result[$i]['latitude'], $result[$i]['longitude'], $result[$i]['time']);
                    break;
                case '5': // Sexta    
                    if(diaDaSemana($result[$i]['time']) < $numSemana) $semana++; // Trocou a semana                 
                    array_push($sex[$semana], $result[$i]['id'], $result[$i]['latitude'], $result[$i]['longitude'], $result[$i]['time']);
                    break;
                case '6': // Sábado    
                    if(diaDaSemana($result[$i]['time']) < $numSemana) $semana++; // Trocou a semana                 
                    array_push($sab[$semana], $result[$i]['id'], $result[$i]['latitude'], $result[$i]['longitude'], $result[$i]['time']);
                    break;                      
            }
            // Atribuição para controle das trocas de semana
            $numSemana = diaDaSemana($result[$i]['time']);      
        }

        // Agora que está tudo separado em dias, é feito o cálculo das distancias por dia e exibido na tabela..    
        echo '   
        <br/>
        
        <table>
            <tr>
                <th colspan="8" style="text-align: center;">POSTAGENS AGRUPADAS POR DIAS DA SEMANA</th>
            </tr>
            <tr>    
                <th>Semana</th>
                <th>Domingo</th>
                <th>Segunda-Feira</th>
                <th>Terça-Feira</th>
                <th>Quarta-Feira</th>
                <th>Quinta-Feira</th>
                <th>Sexta-Feira</th>
                <th>Sábado</th>
            </tr>'; 
            //
            $somDistMedDom = 0;
            $somDistMedSeg = 0;
            $somDistMedTer = 0;
            $somDistMedQua = 0;
            $somDistMedQui = 0;
            $somDistMedSex = 0;
            $somDistMedSab = 0;
            //
            $vetMedDom = array();
            $vetMedSeg = array();
            $vetMedTer = array();
            $vetMedQua = array();
            $vetMedQui = array();
            $vetMedSex = array();
            $vetMedSab = array();
            //
            for ($i=0; $i<= $semana; $i++){      
                echo '   
                <tr>
                    <td style="text-align: center;">                    
                        '.($i+1).'
                    </td>
                    <td valign="top">                    
                        '.dadosDoDia($usuario, $dom[$i], $raioAgrupamento, 0).' <!-- Domingo -->                               
                    </td>
                    <td valign="top">                    
                        '.dadosDoDia($usuario, $seg[$i], $raioAgrupamento, 1).' <!-- Segunda -->                               
                    </td>
                    <td valign="top">                    
                        '.dadosDoDia($usuario, $ter[$i], $raioAgrupamento, 2).' <!-- Terça -->                             
                    </td>
                    <td valign="top">                    
                        '.dadosDoDia($usuario, $qua[$i], $raioAgrupamento, 3).' <!-- Quarta -->                            
                    </td>
                    <td valign="top">                    
                        '.dadosDoDia($usuario, $qui[$i], $raioAgrupamento, 4).' <!-- Quinta -->                            
                    </td>
                    <td valign="top">                    
                        '.dadosDoDia($usuario, $sex[$i], $raioAgrupamento, 5).' <!-- Sexta -->                             
                    </td>
                    <td valign="top">                    
                        '.dadosDoDia($usuario, $sab[$i], $raioAgrupamento, 6).' <!-- Sábado -->                            
                    </td>
                </tr>';

                // Insere as medias de distancias uteis nos vetores, para depois calcular as medias e os desvios padroes
                array_push($vetMedDom, disMedia($dom[$i], $raioAgrupamento));
                array_push($vetMedSeg, disMedia($seg[$i], $raioAgrupamento));
                array_push($vetMedTer, disMedia($ter[$i], $raioAgrupamento));
                array_push($vetMedQua, disMedia($qua[$i], $raioAgrupamento));
                array_push($vetMedQui, disMedia($qui[$i], $raioAgrupamento));
                array_push($vetMedSex, disMedia($sex[$i], $raioAgrupamento));
                array_push($vetMedSab, disMedia($sab[$i], $raioAgrupamento));
            }

            //
            $vetVet[0] = $vetMedDom;
            $vetVet[1] = $vetMedSeg;
            $vetVet[2] = $vetMedTer;
            $vetVet[3] = $vetMedQua;
            $vetVet[4] = $vetMedQui;
            $vetVet[5] = $vetMedSex;
            $vetVet[6] = $vetMedSab;

            echo '
            <tr>
                <td colspan="8" style="text-align: center;"><b>CÁLCULOS BASEADOS NAS '.($semana+1).' SEMANAS</b></td>
            </tr>

            <tr>    
                <th>--</th>
                <th>Domingo</th>
                <th>Segunda-Feira</th>
                <th>Terça-Feira</th>
                <th>Quarta-Feira</th>
                <th>Quinta-Feira</th>
                <th>Sexta-Feira</th>
                <th>Sábado</th>
            </tr>

            <tr>    
                <td>--</td>';                
                for ($i=0; $i < count($vetVet); $i++) {                  
                    echo '
                    <td>
                        <h4 style="color: #00b300;">Distância total: </h4>'.distanciaTotal($vetVet[$i]).' km
                        <h4 style="color: #d45500;">Média das distâncias: </h4>'.media($vetVet[$i]).' km                        
                        <h4 style="color: #cc00ff;">Desvio padrão da média: </h4>'.desvioPadrao($vetVet[$i]).'                        
                    </td>';
                }                
            echo '
            </tr>
        </table>
        <br/><br/>';
    }

    // Função que irá receber todos os pontos daquele dia e retornar os dados
    function dadosDoDia($usuario, $vetorDia, $raioAgrupamento, $nroDiaSemana){                
        $control   = 0;
        $i         = 0;
        foreach ($vetorDia as $value){
            switch ($control) {
                case '0':
                    $id[$i] = $value;
                    //
                    $control++;
                    break;
                case '1':
                    $latitude[$i] = $value;
                    //
                    $control++;
                    break;
                case '2':
                    $longitude[$i] = $value;
                    //
                    $control++;
                    break;
                case '3':
                    $time[$i] = $value;
                    //
                    $control = 0;
                    $i++;
                    break;              
            }
        }
        //     
        $dist_total = 0;
        $qtd_post   = 0;        
        $qtd_horas  = 0;
        $qtd_manha  = 0;
        $qtd_tarde  = 0;
        $qtd_noite  = 0;        
        // Vetores para controlar os dados por turno
        $vetorManha = array();
        $vetorTarde = array();
        $vetorNoite = array();        
        //        
        for ($i=0; $i< count($latitude); $i++){  
            //
            if($i< count($latitude)-1)          
                $dist = getDistance($latitude[$i], $longitude[$i], $latitude[$i+1], $longitude[$i+1]); 
            else
                $dist = 0;            
           
            $dist_total += $dist;
            $qtd_post++;
           
            // Media de Horários
            $aux        = explode(' ', $time[$i]);              
            $qtd_horas += strtotime($aux[1]); // Somatorio das horas das postagens

            // Quantidade de postagens por turnos do dia
            if($aux[1] >= 6 && $aux[1] < 12){
                array_push($vetorManha, $id[$i], $latitude[$i], $longitude[$i], $time[$i]);
                $qtd_manha++;
            }else
            if($aux[1] >= 12 && $aux[1] < 19){
                array_push($vetorTarde, $id[$i], $latitude[$i], $longitude[$i], $time[$i]);
                $qtd_tarde++;
            }else{
                array_push($vetorNoite, $id[$i], $latitude[$i], $longitude[$i], $time[$i]);
                $qtd_noite++;
            }
        }
        
        // Média distancias
        $media_distancia = $dist_total/$qtd_post;
        // Média dos horarios
        $media_horario   = $qtd_horas/count($latitude);

        // Converte numeros
        $media_distancia = number_format($media_distancia, 2, ".", ",");
        $dist_total      = number_format($dist_total, 2, ",", ".");
        //
        $resManha = array();
        $resTarde = array();
        $resNoite = array();        

        // Retorna um array com os locais agrupados, ordenados do maior ponto para o menor                
        $resManha = pontosAgrupados($vetorManha, $raioAgrupamento);        
        $resTarde = pontosAgrupados($vetorTarde, $raioAgrupamento);
        $resNoite = pontosAgrupados($vetorNoite, $raioAgrupamento);        

        //
        $i = 0;
        foreach ($resManha as $chave => $value){            
            $auxx                 = explode(' - ', $chave); // coordenadas base de comparacoes de cada local
            $nomeLocalManha[$i]   = nomeiaLocal($auxx[0]);                        
            $dadosManha[$i]       = $auxx[0];            
            $qtdManha[$i]         = $auxx[1];
            $medHorariosManha[$i] = $value; // Média dos horarios deste ponto em numero inteiro            
            //
            $i++;                                  
        }    
        $i = 0;
        foreach ($resTarde as $chave => $value){            
            $auxx                 = explode(' - ', $chave);
            $nomeLocalTarde[$i]   = nomeiaLocal($auxx[0]);            
            $dadosTarde[$i]       = $auxx[0];
            $qtdTarde[$i]         = $auxx[1];
            $medHorariosTarde[$i] = $value; // Média dos horarios deste ponto em numero inteiro
            //
            $i++;                                  
        }       
        $i = 0;        
        foreach ($resNoite as $chave => $value){            
            $auxx                 = explode(' - ', $chave);
            $nomeLocalNoite[$i]   = nomeiaLocal($auxx[0]);     
            $dadosNoite[$i]       = $auxx[0];
            $qtdNoite[$i]         = $auxx[1];
            $medHorariosNoite[$i] = $value; // Média dos horarios deste ponto em numero inteiro
            //
            $i++;                                  
        }       
        
        if($aux[0]){
            $data_calendar = implode("/", array_reverse(explode("-", $aux[0])));
            //
            $dados = '
            '.$data_calendar.'<br/>
            <h4>Nº postagens: '.$qtd_post.'</h4>
            <h4>Média dist. perc.: '.$media_distancia.' Km</h4>                        
            
            <h4>Horários:</h4>        
            <span style="color: #7f2aff">Man. [06:00 - 11:59]: '.$qtd_manha.'</span>';

            global $dom_manha, $dom_tarde, $dom_noite, $seg_manha, $seg_tarde, $seg_noite, $ter_manha, $ter_tarde, $ter_noite, $qua_manha, $qua_tarde, $qua_noite, $qui_manha, $qui_tarde, $qui_noite, $sex_manha, $sex_tarde, $sex_noite, $sab_manha, $sab_tarde, $sab_noite;
            //        
            for($i=0; $i< count($dadosManha); $i++) { 
                $dados .= '<br/>
                <span title="['.$dadosManha[$i].']">&nbsp;&nbsp;&nbsp;&nbsp;- Local '.$nomeLocalManha[$i].'</b>: <b>'.$qtdManha[$i].' pontos</b><br>'.$dadosManha[$i].'</span>';

                if($i< count($dadosManha)-1){
                    $ponto1 = explode(", ", $dadosManha[$i]);
                    $ponto2 = explode(", ", $dadosManha[$i+1]);
                    $dist   = getDistance($ponto1[0], $ponto1[1], $ponto2[0], $ponto2[1]);
                    $dados .= '
                    <br/>
                    <span style="color:#F00;">['.$nomeLocalManha[$i].']->['.$nomeLocalManha[$i+1].']: '.number_format($dist,2,",",".").' km</span>';    
                }
                
                // Manhã                                
                switch ($nroDiaSemana) {
                    case '0': // Domingo                            
                        array_push($dom_manha, $nomeLocalManha[$i], $dadosManha[$i], $qtdManha[$i], $medHorariosManha[$i]);
                        break;
                    case '1': // Segunda
                        array_push($seg_manha, $nomeLocalManha[$i], $dadosManha[$i], $qtdManha[$i], $medHorariosManha[$i]);
                        break;
                    case '2': // Terça
                        array_push($ter_manha, $nomeLocalManha[$i], $dadosManha[$i], $qtdManha[$i], $medHorariosManha[$i]);
                        break;
                    case '3': // Quarta
                        array_push($qua_manha, $nomeLocalManha[$i], $dadosManha[$i], $qtdManha[$i], $medHorariosManha[$i]);
                        break;
                    case '4': // Quinta
                        array_push($qui_manha, $nomeLocalManha[$i], $dadosManha[$i], $qtdManha[$i], $medHorariosManha[$i]);
                        break;
                    case '5': // Sexta
                        array_push($sex_manha, $nomeLocalManha[$i], $dadosManha[$i], $qtdManha[$i], $medHorariosManha[$i]);
                        break;
                    case '6': // Sabado
                        array_push($sab_manha, $nomeLocalManha[$i], $dadosManha[$i], $qtdManha[$i], $medHorariosManha[$i]);
                        break;
                }
            }
            $dados .= '
            <br/>
            <span style="color: #7f2aff">Tar. [12:00 - 18:59]: '.$qtd_tarde.'</span>';
            for($i=0; $i< count($dadosTarde); $i++) { 
                $dados .= '<br/><span title="['.$dadosTarde[$i].']">&nbsp;&nbsp;&nbsp;&nbsp;- Local '.$nomeLocalTarde[$i].': <b>'.$qtdTarde[$i].' pontos</b><br>'.$dadosTarde[$i].'</span>';

                if($i< count($dadosTarde)-1){
                    $ponto1 = explode(", ", $dadosTarde[$i]);
                    $ponto2 = explode(", ", $dadosTarde[$i+1]);
                    $dist   = getDistance($ponto1[0], $ponto1[1], $ponto2[0], $ponto2[1]);
                    $dados .= '
                    <br/>
                    <span style="color:#F00;">['.$nomeLocalTarde[$i].']->['.$nomeLocalTarde[$i+1].']: '.number_format($dist,2,",",".").' km</span>';    
                }
                // Tarde                
                switch ($nroDiaSemana) {
                    case '0': // Domingo
                        array_push($dom_tarde, $nomeLocalTarde[$i], $dadosTarde[$i], $qtdTarde[$i], $medHorariosTarde[$i]);
                        break;
                    case '1': // Segunda
                        array_push($seg_tarde, $nomeLocalTarde[$i], $dadosTarde[$i], $qtdTarde[$i], $medHorariosTarde[$i]);
                        break;
                    case '2': // Terça
                        array_push($ter_tarde, $nomeLocalTarde[$i], $dadosTarde[$i], $qtdTarde[$i], $medHorariosTarde[$i]);
                        break;
                    case '3': // Quarta
                        array_push($qua_tarde, $nomeLocalTarde[$i], $dadosTarde[$i], $qtdTarde[$i], $medHorariosTarde[$i]);
                        break;
                    case '4': // Quinta
                        array_push($qui_tarde, $nomeLocalTarde[$i], $dadosTarde[$i], $qtdTarde[$i], $medHorariosTarde[$i]);
                        break;
                    case '5': // Sexta
                        array_push($sex_tarde, $nomeLocalTarde[$i], $dadosTarde[$i], $qtdTarde[$i], $medHorariosTarde[$i]);
                        break;
                    case '6': // Sabado
                        array_push($sab_tarde, $nomeLocalTarde[$i], $dadosTarde[$i], $qtdTarde[$i], $medHorariosTarde[$i]);
                        break;
                }                
            }
            $dados .= '
            <br/>
            <span style="color: #7f2aff">Noi. [19:00 - 05:59]: '.$qtd_noite.'</span>';
            for($i=0; $i< count($dadosNoite); $i++) { 
                $dados .= '<br/><span title="['.$dadosNoite[$i].']">&nbsp;&nbsp;&nbsp;&nbsp;- Local '.$nomeLocalNoite[$i].'</b>: <b>'.$qtdNoite[$i].' pontos</b><br>'.$dadosNoite[$i].'</span>';

                if($i< count($dadosNoite)-1){
                    $ponto1 = explode(", ", $dadosNoite[$i]);
                    $ponto2 = explode(", ", $dadosNoite[$i+1]);
                    $dist   = getDistance($ponto1[0], $ponto1[1], $ponto2[0], $ponto2[1]);
                    $dados .= '
                    <br/>
                    <span style="color:#F00;">['.$nomeLocalNoite[$i].']->['.$nomeLocalNoite[$i+1].']: '.number_format($dist,2,",",".").' km</span>';    
                }

                // Noite                
                switch ($nroDiaSemana) {
                    case '0': // Domingo
                        array_push($dom_noite, $nomeLocalNoite[$i], $dadosNoite[$i], $qtdNoite[$i], $medHorariosNoite[$i]);
                        break;
                    case '1': // Segunda
                        array_push($seg_noite, $nomeLocalNoite[$i], $dadosNoite[$i], $qtdNoite[$i], $medHorariosNoite[$i]);
                        break;
                    case '2': // Terça
                        array_push($ter_noite, $nomeLocalNoite[$i], $dadosNoite[$i], $qtdNoite[$i], $medHorariosNoite[$i]);
                        break;
                    case '3': // Quarta
                        array_push($qua_noite, $nomeLocalNoite[$i], $dadosNoite[$i], $qtdNoite[$i], $medHorariosNoite[$i]);
                        break;
                    case '4': // Quinta
                        array_push($qui_noite, $nomeLocalNoite[$i], $dadosNoite[$i], $qtdNoite[$i], $medHorariosNoite[$i]);
                        break;
                    case '5': // Sexta
                        array_push($sex_noite, $nomeLocalNoite[$i], $dadosNoite[$i], $qtdNoite[$i], $medHorariosNoite[$i]);
                        break;
                    case '6': // Sabado                        
                        array_push($sab_noite, $nomeLocalNoite[$i], $dadosNoite[$i], $qtdNoite[$i], $medHorariosNoite[$i]);                      
                        break;
                }
            
            }
            $dados .= '
            <br/>';                
        }
        //        
        return $dados;
    }   
?>