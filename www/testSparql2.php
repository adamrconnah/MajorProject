<?php 
 // Include all RAP classes
define("RDFAPI_INCLUDE_DIR", "C:/Apache/htdocs/rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

// Create a SPARQL client
$client = ModelFactory::getSparqlClient("http://www.exampleSparqlService.net:2020/example");

//Once the client has been created, we can perform our first query which will be: "Find the full name of all employees". We create a $querystring containing the corresponding SPARQL query:

$querystring = '
PREFIX vcard: <http://www.w3.org/2001/vcard-rdf/3.0#>
SELECT ?fullName
WHERE { ?x vcard:FN ?fullName }';

//To execute the query, we create a new ClientQuery object and pass it to the SPARQL client:

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all bindings of the variable ?fullName.

foreach($result as $line){
  $value = $line['?fullName'];
    if($value != "")
      echo $value->toString()."<br>";
    else
      echo "undbound<br>";
}

//Another, even more convenient way to display the results of a query is to use the writeQueryResultAsHtmlTable() method of the SPARQL engine. All we have to do is to pass the query result to this method:

SPARQLEngine::writeQueryResultAsHtmlTable($result); 