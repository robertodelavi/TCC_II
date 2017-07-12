<?php
    require_once 'readData.php';
    //    
    // Domingo
    $domingo_manha = array(); $domingo_manha = validaCharts(0, 0); 
    $domingo_tarde = array(); $domingo_tarde = validaCharts(0, 1); 
    $domingo_noite = array(); $domingo_noite = validaCharts(0, 2); 
    // Segunda
    $segunda_manha = array(); $segunda_manha = validaCharts(1, 0);
    $segunda_tarde = array(); $segunda_tarde = validaCharts(1, 1);
    $segunda_noite = array(); $segunda_noite = validaCharts(1, 2);
    // Terça
    $terca_manha = array(); $terca_manha = validaCharts(2, 0);
    $terca_tarde = array(); $terca_tarde = validaCharts(2, 1);
    $terca_noite = array(); $terca_noite = validaCharts(2, 2);
    // Quarta
    $quarta_manha = array(); $quarta_manha = validaCharts(3, 0);
    $quarta_tarde = array(); $quarta_tarde = validaCharts(3, 1);
    $quarta_noite = array(); $quarta_noite = validaCharts(3, 2);
    // Quinta
    $quinta_manha = array(); $quinta_manha = validaCharts(4, 0);
    $quinta_tarde = array(); $quinta_tarde = validaCharts(4, 1);
    $quinta_noite = array(); $quinta_noite = validaCharts(4, 2);
    // Sexta
    $sexta_manha = array(); $sexta_manha = validaCharts(5, 0);
    $sexta_tarde = array(); $sexta_tarde = validaCharts(5, 1);
    $sexta_noite = array(); $sexta_noite = validaCharts(5, 2);
    // Sabado
    $sabado_manha = array(); $sabado_manha = validaCharts(6, 0);
    $sabado_tarde = array(); $sabado_tarde = validaCharts(6, 1);
    $sabado_noite = array(); $sabado_noite = validaCharts(6, 2);
?>    
<script>
    $(document).ready(function(){        
        var indice = 0;        
        //
        for(var i = 0; i< 7; i++){ // Dias da semana                
            //          
            for(var j = 0; j< 3; j++){ // Turnos do dia                
                var valor = new Array();       
                //
                if(i == 0){ // Domingo
                    if(j == 0){ // Manha
                        valor = <?php echo json_encode($domingo_manha); ?>;                                   
                    }else
                    if(j == 1){ // Tarde
                        valor = <?php echo json_encode($domingo_tarde); ?>;                                                 
                    }else
                    if(j == 2){ // Noite
                        valor = <?php echo json_encode($domingo_noite); ?>;                         
                    }
                }else
                if(i == 1){ // Segunda
                    if(j == 0){ // Manha
                        valor = <?php echo json_encode($segunda_manha); ?>;                         
                    }else
                    if(j == 1){ // Tarde
                        valor = <?php echo json_encode($segunda_tarde); ?>;                         
                    }else
                    if(j == 2){ // Noite
                        valor = <?php echo json_encode($segunda_noite); ?>;                         
                    }
                }else
                if(i == 2){ // Terça
                    if(j == 0){ // Manha
                        valor = <?php echo json_encode($terca_manha); ?>;                           
                    }else
                    if(j == 1){ // Tarde
                        valor = <?php echo json_encode($terca_tarde); ?>;                           
                    }else
                    if(j == 2){ // Noite
                        valor = <?php echo json_encode($terca_noite); ?>;                           
                    }
                }else
                if(i == 3){ // Quarta
                    if(j == 0){ // Manha
                        valor = <?php echo json_encode($quarta_manha); ?>;                          
                    }else
                    if(j == 1){ // Tarde
                        valor = <?php echo json_encode($quarta_tarde); ?>;                          
                    }else
                    if(j == 2){ // Noite
                        valor = <?php echo json_encode($quarta_noite); ?>;                          
                    }
                }else
                if(i == 4){ // Quinta
                    if(j == 0){ // Manha
                        valor = <?php echo json_encode($quinta_manha); ?>;                          
                    }else
                    if(j == 1){ // Tarde
                        valor = <?php echo json_encode($quinta_tarde); ?>;                          
                    }else
                    if(j == 2){ // Noite
                        valor = <?php echo json_encode($quinta_noite); ?>;                          
                    }
                }else
                if(i == 5){ // Sexta
                    if(j == 0){ // Manha
                        valor = <?php echo json_encode($sexta_manha); ?>;                           
                    }else
                    if(j == 1){ // Tarde
                        valor = <?php echo json_encode($sexta_tarde); ?>;                           
                    }else
                    if(j == 2){ // Noite
                        valor = <?php echo json_encode($sexta_noite); ?>;                           
                    }
                }else
                if(i == 6){ // Sabado
                    if(j == 0){ // Manha
                        valor = <?php echo json_encode($sabado_manha); ?>;                          
                    }else
                    if(j == 1){ // Tarde
                        valor = <?php echo json_encode($sabado_tarde); ?>;                          
                    }else
                    if(j == 2){ // Noite
                        valor = <?php echo json_encode($sabado_noite); ?>;                          
                    }
                } 

                if(valor != ''){
                    // Para definir a ordem dos locais
                    if(!valor[0]) valor[0] = "N/I - N/I";
                    var aux0  = valor[0].split(" - ");
                    valor[0]  = aux0[0];                    
                    var disp0 = aux0[2]; // Para exibir a media de horarios no gráfico
                    //
                    if(!valor[2]) valor[2] = "N/I - N/I";
                    var aux1  = valor[2].split(" - ");
                    valor[2]  = aux1[0];                    
                    var disp1 = aux1[2]; // Para exibir a media de horarios no gráfico
                    //
                    if(!valor[4]) valor[4] = "N/I - N/I";
                    var aux2  = valor[4].split(" - ");
                    valor[4]  = aux2[0];                    
                    var disp2 = aux2[2]; // Para exibir a media de horarios no gráfico

                    // Define a cor para cada local. Cada letra e cor corresponderão a um nº                    
                    var cor0, cor1, cor2;
                    cor0 = fromLetters(valor[0]); // Recebe a letra convertida em nº, que será o índice do vetor de cores
                    if(cor0 > (cores.length)-1) cor0 -= cores.length; // Caso retornar um nª maior que a qtd no vetor
                    cor0 = cores[cor0];
                    cor1 = fromLetters(valor[2]); // Recebe a letra convertida em nº, que será o índice do vetor de cores
                    if(cor1 > (cores.length)-1) cor1 -= cores.length; // Caso retornar um nª maior que a qtd no vetor
                    cor1 = cores[cor1];
                    cor2 = fromLetters(valor[4]); // Recebe a letra convertida em nº, que será o índice do vetor de cores
                    if(cor2 > (cores.length)-1) cor2 -= cores.length; // Caso retornar um nª maior que a qtd no vetor
                    cor2 = cores[cor2];

                    //alert(valor[0]+' - '+cor0);
                                       
                    var chart = AmCharts.makeChart('ct-chart_'+indice, {
                        "type": "pie",
                        "theme": "light",
                        "dataProvider": [
                            {
                                "country": 'Méd. hs.: '+disp0+'\nLocal '+valor[0],
                                "litres":  valor[1],
                                "color":   cor0
                            }, {
                                "country": 'Méd. hs.: '+disp1+'\nLocal '+valor[2],
                                "litres":  valor[3],
                                "color":   cor1
                            }, {
                                "country": 'Méd. hs.: '+disp2+'\nLocal '+valor[4],
                                "litres":  valor[5],
                                "color":   cor2
                            }
                        ],
                        "valueField": "litres",
                        "titleField": "country",
                        "colorField": "color",
                        "balloon": {
                            "fixedPosition": true
                        }
                    }); 
                }
                //
                indice++;               
            }    
        }            
    });	

    function fromLetters(str){
        "use strict";
        var out = 0, len = str.length, pos = len;
        while (--pos > -1){
            out += (str.charCodeAt(pos) - 64) * Math.pow(26, len - 1 - pos);
        }
        //
        return out;
    }

    // Vetor de cores
    var cores = [
            "#CD5C5C",
            "#F08080",
            "#FA8072",
            "#E9967A",
            "#FFA07A",
            "#DC143C",
            "#FF0000",
            "#B22222",
            "#8B0000",
            "#FFC0CB",
            "#FFB6C1",
            "#FF69B4",
            "#FF1493",
            "#C71585",
            "#DB7093",
            "#FFA07A",
            "#FF7F50",
            "#FF6347",
            "#FF4500",
            "#FF8C00",
            "#FFA500",
            "#FFD700",
            "#FFFF00",
            "#FFFFE0",
            "#FFFACD",
            "#FAFAD2",
            "#a05a2c",
            "#FFEFD5",
            "#FFE4B5",
            "#FFDAB9",
            "#EEE8AA",
            "#F0E68C",
            "#BDB76B", 
            "#E6E6FA",
            "#D8BFD8",
            "#0000aa",
            "#00d455",
            "#DDA0DD",
            "#EE82EE",
            "#DA70D6",
            "#FF00FF",
            "#FF00FF",
            "#00d400",
            "#BA55D3",
            "#9370DB",
            "#8A2BE2",
            "#9400D3",
            "#9932CC",
            "#8B008B",
            "#800080",
            "#4B0082",
            "#6A5ACD",
            "#483D8B",
            "#ADFF2F",
            "#7FFF00",
            "#7CFC00",
            "#00FF00",
            "#32CD32",
            "#98FB98",
            "#90EE90",
            "#00FA9A",
            "#00FF7F",
            "#3CB371",
            "#2E8B57",
            "#228B22",
            "#008000",
            "#006400",
            "#9ACD32",
            "#6B8E23",
            "#808000",
            "#556B2F",
            "#66CDAA",
            "#8FBC8F",
            "#20B2AA",
            "#008B8B",
            "#008080",
            "#00FFFF",
            "#00FFFF",
            "#E0FFFF",
            "#AFEEEE",
            "#7FFFD4",
            "#40E0D0",
            "#48D1CC",
            "#00CED1",
            "#5F9EA0",
            "#4682B4",
            "#B0C4DE",
            "#B0E0E6",
            "#ADD8E6",
            "#87CEEB",
            "#87CEFA",
            "#00BFFF",
            "#1E90FF",
            "#6495ED",
            "#7B68EE",
            "#4169E1",
            "#0000FF",
            "#0000CD",
            "#00008B",
            "#000080",
            "#191970", 
            "Browns",
            "#FFF8DC",
            "#FFEBCD",
            "#FFE4C4",
            "#FFDEAD",
            "#F5DEB3",
            "#DEB887",
            "#D2B48C",
            "#BC8F8F",
            "#F4A460",
            "#DAA520",
            "#B8860B",
            "#CD853F",
            "#D2691E",
            "#8B4513",
            "#A0522D",
            "#A52A2A",
            "#800000", 
            "#FFFAFA",
            "#F0FFF0",
            "#F5FFFA",
            "#F0FFFF",
            "#F0F8FF",
            "#F8F8FF",
            "#F5F5F5",
            "#FFF5EE",
            "#F5F5DC",
            "#FDF5E6",
            "#FFFAF0",
            "#FFFFF0",
            "#FAEBD7",
            "#FAF0E6",
            "#FFF0F5",
            "#FFE4E1",
            "#DCDCDC",
            "#D3D3D3",
            "#C0C0C0",
            "#A9A9A9",
            "#808080",
            "#696969",
            "#778899",
            "#708090",
            "#2F4F4F",
            "#000000"
    ];  
</script>