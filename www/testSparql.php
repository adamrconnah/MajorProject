<?php 
//this file loads everything from biocrunch../sparql
//include rap
 define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

//sparql client
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
//Find the name of all diseases
$querystring = '
PREFIX dc: <http://dublincore.org/documents/2012/06/14/dcmi-terms/?v=elements#title>
SELECT ?x
where   { ?x ?y ?z } Limit 20';

//To execute the query, we create a new ClientQuery 
//object and pass it to the SPARQL client:

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all 
//bindings of the variable ?fullName.
foreach($result as $line){
//editing below to either ?s ?o ?p  prints out different parts of data.
  $value = $line['?x'];
    if($value != "")
      echo $value->toString()."<br>";
    else
      echo "undbound<br>";
}


//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
//?x  = Resource("http://www.example.co.uk/genotype/disease#Z")
//?y  = Resource("http://purl.org/dc/elements/1.1/title")	
//?z = Literal("Disease Z")
