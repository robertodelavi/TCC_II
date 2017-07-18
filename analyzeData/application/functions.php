<?php
	require_once 'readData.php';
    //

    function nomeiaLocal($coordenada){
        global $vetCoorNomes;
        //        
        foreach ($vetCoorNomes as $key => $value){            
            if($coordenada == $key)
                return $value;            
        }
    }

    function mapeiaLocais($raio){
        global $vetCoorNomes;
        global $centroides;
        global $letra;
        //          
        foreach ($vetCoorNomes as $coordenadaVetor => $value) {
            $possui_local = 0;
            // Pega o ponto atual e verifica a distancia com cada centroide
            foreach ($centroides as $letraCentroide => $valuec) { // Varre este laço para cada local: A, B, C, D ...                            
                //echo '<br>letra centroide: '.$letraCentroide;
                $cent = explode(', ', $valuec);
                $vet  = explode(', ', $coordenadaVetor);            
                //
                $dist = getDistance($cent[0], $cent[1], $vet[0], $vet[1]);
                //                                    
                if($dist <= $raio && $possui_local == 0){
                    // atualiza centroide deste local
                    $centroides[$letraCentroide] = atualiza_centroide_local($letraCentroide, $coordenadaVetor);                         
                    $vetCoorNomes[$coordenadaVetor] = $letraCentroide;
                    $possui_local = 1;
                }                
            }
            //
            if($possui_local == 0){ // Este ponto nao se encaixou em nehnhuma centroide, novo local             
                $vetCoorNomes[$coordenadaVetor] = $letra;       
                $centroides[$letra] = $coordenadaVetor;     
                $letra++;                
            }
        }           
    }

    function atualiza_centroide_local($letraCentroide, $coordenada){        
        global $vetCoorNomes;
        $qtd_pontos = 1;
        //
        $aux     = explode(', ', $coordenada);
        $somaLat = $aux[0];
        $somaLon = $aux[1];        
        //
        foreach ($vetCoorNomes as $key => $value) {
            if($value === $letraCentroide){ // varre todas as coordenadas do vetor que estao no local A por ex.                
                $coord = explode(', ', $key);
                //
                $somaLat += $coord[0];
                $somaLon += $coord[1];
                //
                $qtd_pontos++;
            }           
        }
        //
        $med_lat = $somaLat/$qtd_pontos;
        $med_lon = $somaLon/$qtd_pontos;
        //              
        return $med_lat.', '.$med_lon;
    }

    // Return the distance in KM
    function getDistance($latitude1, $longitude1, $latitude2, $longitude2){     
        $earth_radius = 6371;
        //
        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);
        //
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;
        //        
        return $d;
    }

    function validaCharts($numeroSemana, $turno){
        $vetor = array();
        //
        global $dom_manha, $dom_tarde, $dom_noite, $seg_manha, $seg_tarde, $seg_noite, $ter_manha, $ter_tarde, $ter_noite, $qua_manha, $qua_tarde, $qua_noite, $qui_manha, $qui_tarde, $qui_noite, $sex_manha, $sex_tarde, $sex_noite, $sab_manha, $sab_tarde, $sab_noite;

        switch ($numeroSemana){
            case '0': // Domingo
                switch ($turno) {
                    case '0': // Manha                        
                        $vetor = $dom_manha;                        
                        break;
                    case '1': // Tarde
                        $vetor = $dom_tarde;
                        break;
                    case '2': // Noite
                        $vetor = $dom_noite;                        
                        break;
                }                
                break;
            case '1': // Segunda
                switch ($turno) {
                    case '0': // Manha                        
                        $vetor = $seg_manha;
                        break;
                    case '1': // Tarde
                        $vetor = $seg_tarde;
                        break;
                    case '2': // Noite
                        $vetor = $seg_noite;
                        break;
                }
                break;
            case '2': // Terça
                switch ($turno) {
                    case '0': // Manha                        
                        $vetor = $ter_manha;
                        break;
                    case '1': // Tarde
                        $vetor = $ter_tarde;
                        break;
                    case '2': // Noite
                        $vetor = $ter_noite;
                        break;
                }
                break;
            case '3': // Quarta
                switch ($turno) {
                    case '0': // Manha                        
                        $vetor = $qua_manha;
                        break;
                    case '1': // Tarde
                        $vetor = $qua_tarde;
                        break;
                    case '2': // Noite
                        $vetor = $qua_noite;
                        break;
                }
                break;
            case '4': // Quinta
                switch ($turno) {
                    case '0': // Manha                        
                        $vetor = $qui_manha;
                        break;
                    case '1': // Tarde
                        $vetor = $qui_tarde;
                        break;
                    case '2': // Noite
                        $vetor = $qui_noite;
                        break;
                }
                break;
            case '5': // Sexta
                switch ($turno) {                    
                    case '0': // Manha                        
                        $vetor = $sex_manha;
                        break;
                    case '1': // Tarde
                        $vetor = $sex_tarde;
                        break;
                    case '2': // Noite
                        $vetor = $sex_noite;
                        break;
                }
                break;
            case '6': // Sabado
                switch ($turno) {
                    case '0': // Manha                        
                        $vetor = $sab_manha;
                        break;
                    case '1': // Tarde
                        $vetor = $sab_tarde;
                        break;
                    case '2': // Noite
                        $vetor = $sab_noite;                        
                        break;
                }
                break;            
        }        
        // Validar quais sao os 3 pontos com mais tweets em todas as semanas e mostrar a qtd
        $indice  = 0;
        $control = 0;
        foreach ($vetor as $key => $value) {
            if($control == 0){
                $nomeLocal[$indice] = $value;
                $control++;
            }else
            if($control == 1){
                $coord[$indice]  = $value;                
                $control++;
            }else
            if($control == 2){
                $qtdLocal[$indice]  = $value;                
                $control++;
            }else
            if($control == 3){
                $horaLocal[$indice] = $value;                
                $indice++;
                $control = 0;
            }
        }
        //
        for($i=0; $i< count($nomeLocal); $i++){            
            $vsoma     = [];
            $vsoma2[0] = date('H:i:s', $horaLocal[$i]);
            $soma_hora_semana[$i] = $horaLocal[$i];
            $qtdHora              = 0;
            for($j=0; $j< count($nomeLocal); $j++){                
                if($i!=$j && $qtdLocal[$j] != -1){
                    if($nomeLocal[$i] == $nomeLocal[$j]){                        
                        $qtdLocal[$i] += $qtdLocal[$j];
                        $vsoma2[$qtdHora] = date('H:i:s', $horaLocal[$j]);
                        $soma_hora_semana[$i] += $horaLocal[$j];
                        $qtdHora++;                        
                        $nomeLocal[$j] = -1;
                        $qtdLocal[$j]  = -1;
                    }
                }
            }
            $medHoraDisplay[$i] = mediaHora($vsoma2);
            $nomeTime[$i] = $nomeLocal[$i].' - '.$soma_hora_semana[$i].' - '.date('H:i:s', $medHoraDisplay[$i]);            
        }        
        $vetNomeLocal = array();
        $vetQtdLocal  = array();        
        //
        for($i=0; $i< count($nomeLocal); $i++){
            if($qtdLocal[$i] != -1){                
                array_push($vetNomeLocal, $nomeTime[$i]);
                array_push($vetQtdLocal, $qtdLocal[$i]);                
            }            
        }

        $vetLocaisOrdenados = array();
        $vetLocaisOrdenados = ordenaVetor(1, $vetNomeLocal, $vetQtdLocal);        

        $vetFinal = array();
        $control  = 0;
        foreach ($vetLocaisOrdenados as $key => $value) {
            if($control< 3){ // Envia somente os 3 locais com mais pontos
                array_push($vetFinal, $key);
                array_push($vetFinal, $value);                        
                $control++;
            }            
        }
        return $vetFinal;
    }

    function ordenaVetor($tipoOrdenacao, $vetorDados, $vetorQuantidades){
        $vetor = array();
        $vetor = array_combine($vetorDados, $vetorQuantidades);
        //
        if($tipoOrdenacao == 0) // Ordena ASC
            asort($vetor);
        else                    // Ordena DESC
            arsort($vetor);
        //
        $i         = 0;
        $control   = 0;
        $dados     = array();        
        $qtdPontos = array();
        //
        foreach($vetor as $chave => $valor){          
            $dados[$i] = $chave;               
            $qtdPontos[$i] = $valor;               
            $i++;  
        }
        
        for ($i=0; $i < count($dados); $i++) { 
            if(!$qtdPontos[$i])
                $qtdPontos[$i] = 0;            
        }
            
        return array_combine($dados, $qtdPontos);
    }

    function diaDaSemana($data){    	
		$data = explode(' ', $data);
		// Varivel que recebe o dia da semana (0 = Domingo, 1 = Segunda ...)
		$diasemana_numero = date('w', strtotime($data[0]));
		// Retorna o dia da semana com o Array		
		return $diasemana_numero;
    }

    function pontosAgrupados($vetor, $raioAgrupamento){        
        $control = 0;
        $i       = 0;
        foreach ($vetor as $value){
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

        $indicesUsados = array();       
        $indicePontos  = 0; 
        //
        for ($i=0; $i< count($id); $i++){ 
            $qtdPonto[$indicePontos] = 0; 
            //
            if(array_search($i, $indicesUsados) == ''){ // Valida se este ponto ja foi usado   
                $soma_hora = 0;
                $indiceH   = 0;
                $vsoma = [];
                for ($j=0; $j< count($id); $j++){ // Pega o ponto $i e compara com todos os outros            
                    if(array_search($j, $indicesUsados) == ''){ // Valida se este ponto ja foi usado
                        $distance = getDistance($latitude[$i], $longitude[$i], $latitude[$j], $longitude[$j]);   
                        //
                        if($distance <= $raioAgrupamento){ // Outro ponto proximo (< 50m) => incrementa contador deste ponto $i
                            $qtdPonto[$indicePontos]++;

                            // Somatorio das horas, pra calculo da media
                            $auxiliar = explode(" ", $time[$j]);                            
                            $horaInt = strtotime($auxiliar[1]);
                            $soma_hora += $horaInt;
                            $vsoma[$indiceH] = $auxiliar[1];
                            //
                            $latitudePonto[$indicePontos]  = $latitude[$i];
                            $longitudePonto[$indicePontos] = $longitude[$i];                            
                            //
                            array_push($indicesUsados, $j);      
                            $indiceH++;                                          
                        }            
                    }
                }
                //           
                $mediaHora[$indicePontos] = mediaHora($vsoma);                            
                // Acabou o laço, ja varreu todas as coordenadas deste local (raio < 50 m), incrementa o indice de locais para iniciar um contador p um novo local
                $indicePontos++;
            }            
        }   

        // Laço para inserir os locais em vetores, para poder ordenar pela quantidade
        $vetorDados = array();          
        $vetorMedH  = array();
        for ($i=0; $i< $indicePontos; $i++){ 
            // Insere no vetor, para ordenar e saber quais sao os pontos mais visitados
            array_push($vetorDados, $latitudePonto[$i].', '.$longitudePonto[$i].' - '.$qtdPonto[$i]);            
            array_push($vetorMedH, $mediaHora[$i]);            
        }
        // Ordena pela quantidade
        $vetorResult = array();        
        $vetorResult = ordenaVetor(0, $vetorDados, $vetorMedH);
        //
        return $vetorResult;
    }

    function mediaHora($vetor){
        $soma_hora = 0;
        $soma_min  = 0;
        $soma_seg  = 0;
        //
        for ($i=0; $i< count($vetor); $i++) { 
            $aux = explode(':', $vetor[$i]);    
            //
            $hora = $aux[0];
            $min  = $aux[1];
            $seg  = $aux[2];    
            //
            if($hora < 19){ // madrugada
                $hora += 24;
            }
            //
            $soma_hora += $hora;
            $soma_min  += $min;
            $soma_seg  += $seg;
        }
        //
        $med_hora = floor($soma_hora/$i); 
        $med_min  = floor($soma_min/$i);
        $med_seg  = floor($soma_seg/$i);
        //
        if($med_hora >= 24)
            $med_hora -= 24;
        //
        if($med_hora < 10) $med_hora = '0'.$med_hora;
        if($med_min  < 10) $med_min  = '0'.$med_min;
        if($med_seg  < 10) $med_seg  = '0'.$med_seg;
        //
        $hora = $med_hora.':'.$med_min.':'.$med_seg;
        //
        return strtotime($hora);
    }

    // 
    function disMedia($vetorDia, $raioAgrupamento){
        $control = 0;
        $i       = 0;
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
        $qtd_util   = 0;        
        //
        for ($i=0; $i< count($latitude)-1; $i++){    
            $dist = getDistance($latitude[$i], $longitude[$i], $latitude[$i+1], $longitude[$i+1]); 
            // Cálculo das distancias entre os pontos
            if($dist > $raioAgrupamento){
                $dist_total += $dist;
                $qtd_util++;
            }
        }
        // Media distancias
        $media_distancia = $dist_total/$qtd_util;        
        // Converte numeros
        $media_distancia = number_format($media_distancia, 2, ".", "");        
        //
        return $media_distancia;
    }

    function distanciaTotal($vetor){
        $soma = 0;        
        //
        foreach ($vetor as $value){
            $soma += $value;                        
        }
        //        
        $soma = number_format($soma, 2, ".", ",");
        //
        return $soma;
    }

    function media($vetorMedias){
        $soma = 0;
        $qtd  = 0;
        //
        foreach ($vetorMedias as $value){
            $soma += $value;            
            $qtd++;
        }
        //
        $media = $soma/$qtd;
        $media = number_format($media, 2, ".", ",");
        //
        return $media;
    }

    function desvioPadrao($vetorMedias){        
        $n = 0;
        //
        foreach ($vetorMedias as $value){
            $valor[$n] = $value;            
            $n++;
        }
        //
        $media = media($vetorMedias);
        // Obter a variância
        $squared = 0;
        foreach ($vetorMedias as $value){
            $squared += elevarAoQuadrado($value - $media);
        }
        //
        $variacao     = $squared/($n-1);
        $desvioPadrao = sqrt($variacao);
        $desvioPadrao = number_format($desvioPadrao, 5, ".", ",");
        //
        return $desvioPadrao;
    }

    function elevarAoQuadrado($valor){
        return pow($valor, 2);        
    }

    function tirarRaizQuadrada($valor){
        if($valor < 0)
            $valor = $valor * (-1);        
        //
        return sqrt($valor);
    }
?>