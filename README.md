# TCC_II

## Um Estudo de Caso de Análise do Perfil de Mobilidade de Usuários do Twitter

> Algoritmo para obtenção e análise de postagens do Twitter com a Geolocalização ativa.

## Menu (#menu)

  - [Execução](#execução)
  - [Funcionamento](#funcionamento)  

## Execução

  - Importe o BD com o arquivo "mobilidade.sql" localizado dentro do diretório: "mobilidade/";
  - Informe a senha do seu BD no arquivo de conexão, localizado no diretório: "mobilidade/library/MySql.php";
  - Página de análise dos dados do usuário:
    - Em seu navegador, acesse: "localhost/mobilidade/analyzeData/application/"
  - Página de busca dos dados:
    - Em seu navegador, acesse: "localhost/mobilidade/getData/"

## Funcionamento

  - Para realizar a busca dos dados do Twitter, é necessário que este projeto esteja hospedado em um servidor online;
  - A base de dados inclusa neste projeto foi reduzida para fins de amostra de resultados;

  - Na página de busca de dados, é possível realizar a busca pela coordenada ou pelo nome do usuário. Ao realizar esta busca, a aplicação vai verificar todos as postagens com a Geolocalização ativa, guardar os usuários de cada uma dessas postagens em um vetor. Após salvar os usuários no vetor, a aplicação vai buscar as postagens recenter de cada um destes usuários, as postagens que possuirem a Geolocalização ativa serão salvas na base de dados;

  - Na página "localhost/mobilidade/getData/searchOnTwitter.php" a busca por tweets é realizada automaticamente a cada minuto, onde, no período de uma hora, é contemplada toda a região brasileira nas buscas; 

  -  Na página "localhost/mobilidade/analyzeData/application/" são exibidos todos os dados do usuário selecionado, sendo:
    - Todas as postagens e locais criados com o agrupamento informado; 
    - As postagens e locais são plotadas em dois mapas;
    - Os locais mais visitados são exibidos em gráficos, separados por dias da semana e turnos do dia.

  - Na página "localhost/mobilidade/analyzeData/application/removeRobos.php" é executada a remoção de todos os "usuários robôs" da base de dados, ou seja, usuários que possuem todas as suas postagens somente em coordenadas exatamente iguais, o que provavelmente são postagens automatizadas;

[⬆ voltar ao topo](#menu)
