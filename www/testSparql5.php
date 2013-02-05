<?php 
/*
	session_start(); 
	if(isset($_SESSION['views']))
	$_SESSION['views'] = $_SESSION['views']+ 1;
	else
	$_SESSION['views'] = 1;	


	if (isset($_POST['searchTerm'])){ 
	$_SESSION['searchTerm'] = $_POST['searchTerm'];
	$searchQuery=$_POST['searchTerm'];
	}
*/
$searchQuery="http://www.example.co.uk/genotype/disease#Z";


//this file loads specific things from biocrunch../sparql -- look at query below

//include rap
define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

//sparql client
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
//Find the name of all diseases
$querystring = sprintf('
PREFIX dc: <http://dublincore.org/documents/2012/06/14/dcmi-terms/?v=elements#title>
SELECT DISTINCT ?title
where   {  <%s> <http://purl.org/dc/elements/1.1/title> ?title . }', $searchQuery) ; 
//\"${searchQuery}\"
//?s= <http://www.example.co.uk/genotype/disease#X>
//?p= http://purl.org/dc/elements/1.1/title

//To execute the query, we create a new ClientQuery 
//object and pass it to the SPARQL client:

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all 
//bindings of the variable ?fullName.
foreach($result as $line){
  $value = $line['?title'];
    if($value != "")
      echo $value->toString()."<br>";
	/*else if($value =="")
		echo "no results found";*/
    else
      echo "undbound<br>";
}


//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
 