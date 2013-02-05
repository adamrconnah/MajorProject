<?php 
//this file loads specific things from biocrunch../sparql -- look at query below

//include rap
define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

//sparql client
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
//Find the name of all diseases
$querystring = '
PREFIX dc: <http://purl.org/dc/elements/1.1/title>
SELECT ?x
where   { "<http://www.example.co.uk/genotype/disease#Z>"} ';

//To execute the query, we create a new ClientQuery 
//object and pass it to the SPARQL client:

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all 
//bindings of the variable ?fullName.
foreach($result as $line){
  $value = $line['?x'];
    if($value != "")
      echo $value->toString()."<br>";
    else
      echo "undbound<br>";
}


//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
 