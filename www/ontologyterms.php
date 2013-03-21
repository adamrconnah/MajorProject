<!DOCTYPE HTML>
<html>
<head>
<title>Phenomanal</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" type="text/css" href="styles/layout.css">
<script src="scripts/jquery-1.7.2.min.js"></script>
<script src="scripts/jquery.carouFredSel-5.5.2.js"></script>
<script src="scripts/jquery.easing.1.3.js"></script>
<!--[if lt IE 9]>
<script src="scripts/html5.js"></script>
<script src="scripts/css3-mediaqueries.min.js"></script>
<![endif]-->
</head>
<body>
<div class="wrapper">
	<div id="top" class="clearfix">
		<!-- add in logo -->
		<div id="logo"><img id="logoimage" src="" alt=""> 
		  <h1 id="logotitle">Phenomanal</h1>
		</div>
		<!--/logo-->
		<nav>
		  <ul>
			<li><a href="index.html">Home</a></li>
			<li><a href="#">Data</a></li>
			<li><a href="work.html">Help</a></li>
			<li><a href="#">Contact</a></li>
		  </ul>
		</nav>
	</div>
  <header>
    <h1><span>Kitty</span> ipsum dolor sit amet, attack et orci turpis quis vehicula, pellentesque kittens stuck in a tree I don't like that food feed me hiss. </h1>
    <h2>Fluffy fur et bat tortor in viverra</h2>
  </header>
<?php 
//searches for specific diseases using index.html and POST to get the variable submitted
	if (isset($_GET['searchQuery'])){ 
	$_GET["searchQuery"];
	    $searchQuery = $_GET['searchQuery'];
}

/*echo $searchQuery;
str_replace('"', "", $searchQuery);
echo $searchQuery;*/

//this file loads specific things from biocrunch../sparql -- look at query below

//include rap
define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

//sparql client
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
//Find the name of all diseases
$querystring = "
PREFIX phe: <http://phenomebrowser.org/phenomenet/>
PREFIX obo: <http://obofoundry.org/obo>
select ?pheno ?name
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { 
	?dis phe:has_phenotype ?pheno .
	?pheno phe:has_name  ?name
    FILTER regex(?dis, '$searchQuery', 'i')
}
	GROUP BY ?pheno
	ORDER BY ASC(?pheno)
";

/* ?pheno phe:has_name \"%s>\" .'   ,$searchQuery3
PREFIX dc: <http://dublincore.org/documents/2012/06/14/dcmi-terms/?v=elements#title>
SELECT *
where   {  ?s <http://purl.org/dc/elements/1.1/%s> ?title . }', $searchQuery) ; 
*/
//the %s relates to the variable at the end of the line"
//?s= <http://www.example.co.uk/genotype/disease#X>
//?p= http://purl.org/dc/elements/1.1/title

//To execute the query, we create a new ClientQuery 
//object and pass it to the SPARQL client:

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all 
//results of the variable ?title.
?>
<h3>Phenotypes directly associated with <?php echo $searchQuery ?></h3>

  <table id="hor-minimalist-a" summary="Phenotype table">
 <thead>
    	<tr>
        	<th scope="col">Phenotype ID</th>
            <th scope="col">Name</th>
          
        </tr>
    </thead>
    <tbody>
  <?php
foreach($result as $line){
  $pheno = $line['?pheno'];
  $name =$line['?name'];  
  
  if (preg_match('/"([^"]+)"/', $pheno, $m)) { //finds instances that match regex aka gets url
    $pheno = $m[0];   	//assign first instance to pheno
	$c=explode("/", $pheno); //explode pheno to get just end of url
	$pheno=end($c); 
	$pheno = str_replace('"', "", $pheno); //remove quotations from string.
} 

	if (preg_match('/"([^"]+)"/', $name, $n)) {
	$name = $n[0]; 
	$name =str_replace('"', "", $name);
} 
  
    if($name != ""){
    //  echo $name->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
		echo "<tr>";
        echo "<td><a href='https://www.ebi.ac.uk/ontology-lookup/?termId=/$pheno'>$pheno</a></td>";
        echo "<td>$name</td>";
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
?>
<?php 
//searches for specific diseases using index.html and POST to get the variable submitted
	if (isset($_GET['searchQuery'])){ 
	$_GET["searchQuery"];
	    $searchQuery = $_GET['searchQuery'];
}

echo $searchQuery;


//this file loads specific things from biocrunch../sparql -- look at query below

//include rap
define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

//sparql client
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
//Find the name of all diseases
$querystring = "
PREFIX phe: <http://phenomebrowser.org/phenomenet/>
PREFIX obo: <http://obofoundry.org/obo>
select ?infpheno ?name
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { 
	?dis phe:has_inferred_phenotype ?infpheno .
	?infpheno phe:has_name  ?name
    FILTER regex(?dis, '$searchQuery', 'i')
}
	GROUP BY ?infpheno
	ORDER BY ASC(?infpheno)
";

/* ?pheno phe:has_name \"%s>\" .'   ,$searchQuery3
PREFIX dc: <http://dublincore.org/documents/2012/06/14/dcmi-terms/?v=elements#title>
SELECT *
where   {  ?s <http://purl.org/dc/elements/1.1/%s> ?title . }', $searchQuery) ; 
*/
//the %s relates to the variable at the end of the line"
//?s= <http://www.example.co.uk/genotype/disease#X>
//?p= http://purl.org/dc/elements/1.1/title

//To execute the query, we create a new ClientQuery 
//object and pass it to the SPARQL client:

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all 
//results of the variable ?title.
?>
<h3>Mammalian Phenotypes inferred for <?php echo $searchQuery ?></h3>

  <table id="hor-minimalist-a" summary="Inferred Phenotype table">
 <thead>
    	<tr>
        	<th scope="col">Inferred Phenotype ID</th>
            <th scope="col">Name</th>
          
        </tr>
    </thead>
    <tbody>
  <?php
foreach($result as $line){
  $infpheno = $line['?infpheno'];
  $name =$line['?name'];  
  
  if (preg_match('/"([^"]+)"/', $infpheno, $m)) { //finds instances that match regex aka gets url
    $infpheno = $m[0];   	//assign first instance to pheno
	$c=explode("/", $infpheno); //explode pheno to get just end of url
	$infpheno=end($c); 
	$infpheno = str_replace('"', "", $infpheno); //remove quotations from string.
} 

	if (preg_match('/"([^"]+)"/', $name, $n)) {
	$name = $n[0]; 
	$name =str_replace('"', "", $name);
} 
  

    if($name != ""){
    //  echo $name->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
		echo "<tr>";
        echo "<td><a href='https://www.ebi.ac.uk/ontology-lookup/?termId=$infpheno'>$infpheno</a></td>";
        echo "<td>$name</td>";
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
?>

<footer id="footer" class="clearfix">
  <div class="wrapper">
    
    <section class="right social clear">
      <!-- Replace with any 32px x 32px icons -->
      <a href="https://plus.google.com/u/0/111044644508972712357/posts"><img class="icon" src="images/icons/google.png" alt=""></a> <a href="https://www.youtube.com/user/dudders666"><img class="icon" src="images/icons/youtube.png" alt=""></a> <a href="http://www.facebook.com/adamrconnah"><img class="icon" src="images/icons/facebook.png" alt=""></a> <a href="htpp://www.twitter.com/adamrconnah"><img class="icon" src="images/icons/twitter.png" alt=""></a>
      <!-- /icons -->
    </section>
    <!-- /section -->
    <section id="copyright" class="clearfix">
      <p class="left">Adam Connah<a href="#"></a> aoc9@aber.ac.uk</p>
      <p class="right">Website Template By <a target="_blank" href="http://www.birondesign.com/">Chris Biron</a> &amp; Modified By <a href="http://www.os-templates.com/">OS Templates</a></p>
    </section>
  </div>
</footer>

</body></html>