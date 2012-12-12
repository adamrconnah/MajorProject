<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once('LibRDF/LibRDF.php');
 
// All models, i.e. graphs, reside in a storage. This defaults to
// memory.
$store = new LibRDF_Storage();
$model = new LibRDF_Model($store);
 
// Load some data into the model. The format must explicitly be
// declared for the parser, but using e.g. ARC's format detector
// should be easy to implement. Anyways, in this case we're
// dealing with an RDF/XML document:
$model->loadStatementsFromURI(
        new LibRDF_Parser('rdfxml'),
        'http://localhost/testrdf.rdf/');
 
// Create a SPARQL query
$query = new LibRDF_Query("
PREFIX dc:   <http://purl.org/dc/elements/1.1/>
SELECT *
WHERE
  {
    ?s ?p ?o
  }
", null, 'sparql');
 
// Execute the query. The results of a SPARQL SELECT provide
// array access by using the variables used in the query as keys:
$results = $query->execute($model);
foreach ($results as $result) {
    echo $result['?s'], /*. $result['?p'] . $result['?o'] .*/ "\n";
}
