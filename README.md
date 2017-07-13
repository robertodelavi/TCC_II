# Trabalho de conclusão de curso II

Autor: Roberto Delavi de Araújo, Julho, 2017.

## Um Estudo de Caso de Análise do Perfil de Mobilidade de Usuários do Twitter

> Algoritmo para obtenção e análise de postagens do Twitter com a Geolocalização ativa.

## Menu

  - [Requisitos básicos](#requisitos-básicos)
  - [Execução](#execução)
  - [Funcionamento](#funcionamento)  

## Requisitos básicos

Para realizar a execução da aplicação, é necessário possuir instalado:
  - Servidor Apache;
  - PHP;
  - MySql;

## Execução

  - Cole a pasta mobilidade no seu diretório desejado;
  - Importe a base de dados com o arquivo mobilidade.sql, localizado em: "<seudiretorio>/mobilidade/" ;
  - Informe a senha da sua base de dados no arquivo de conexão, localizado em: "<seudiretorio>/mobilidade/library/conecta.php";
  - Para acessar a página da análise de usuários, acesse em seu navegador: "<seudiretorio>/mobilidade/analyzeData/application/";
  - Para acessar a página de busca dos dados, acesse em seu navegador: "<seudiretorio>/mobilidade/getData/";

## Funcionamento

  - Para realizar a busca dos dados do Twitter, é necessário que este projeto esteja hospedado em um servidor online;
  - Na página de busca de dados, é possível realizar a busca pela coordenada ou pelo nome do usuário. Ao realizar esta busca, a aplicação vai verificar todos as postagens com a geolocalização ativa, guardar os usuários de cada uma dessas postagens em um vetor. Após salvar os usuários no vetor, a aplicação vai buscar as postagens recentes de cada um destes usuários, as postagens que possuírem a geolocalização ativa serão salvas na base de dados;
  - Na página "<seu-diretorio>/mobilidade/getData/searchOnTwitter.php" a busca por tweets é realizada automaticamente a cada minuto, onde, no período de uma hora, é contemplada toda a região brasileira nas buscas;
  - Na página "<seu-diretorio>/mobilidade/analyzeData/application/removeRobos.php" é executada a remoção de todos os "usuários robôs" da base de dados, ou seja, usuários que possuem todas as suas postagens somente em coordenadas exatamente iguais, o que provavelmente são postagens automatizadas;

[⬆ voltar ao topo](#menu)
