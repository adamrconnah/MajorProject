<?php 
ini_set('display_errors', 1);
 ini_set('log_errors', 1);
 ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
 error_reporting(E_ALL);
 
 //include rap
 define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

//sparql client
$client = ModelFactory::getSparqlClient("http://localhost:8890/DAV/project/"); 
//Find the name of all diseases
$querystring = '
PREFIX dc: <http://dublincore.org/documents/2012/06/14/dcmi-terms/?v=elements#title>
SELECT *
where   { ?s ?p ?o } ';

//To execute the query, we create a new ClientQuery 
//object and pass it to the SPARQL client:

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all 
//bindings of the variable ?fullName.
if(is_array($result)){}
foreach($result as $line){
  $value = $line['?o'];
    if($value != "")
      echo $value->toString()."<br>";
    else
      echo "undbound<br>";
}
}

SPARQLEngine::writeQueryResultAsHtmlTable($result); 
 