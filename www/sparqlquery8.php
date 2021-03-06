<?php 
//searches for specific diseases using index.html and POST to get the variable submitted
	if (isset($_POST['searchQuery3'])){ 
    $searchQuery3 = $_POST['searchQuery3'];
	
}

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
select *
where { 
	?dis phe:has_phenotype ?pheno .
	?pheno phe:has_name  ?name
    FILTER (?name = '$searchQuery3')
}
LIMIT 20";

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
foreach($result as $line){
  $value = $line['?dis'];
  $value2 =$line['?pheno'];  
    if($value != ""){
      echo $value->toString()."..... ".$value2->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $value2->toString()."<br>";
	  }
    else{
      echo "undbound<br>";
	  }
	  
}

//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
