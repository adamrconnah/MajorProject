<?php
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
        'http://richard.cyganiak.de/foaf.rdf');
 
// Create a SPARQL query
$query = new LibRDF_Query("
PREFIX foaf:   <http://xmlns.com/foaf/0.1/>
SELECT ?name1 ?name2
WHERE
  {
    ?person1 foaf:knows ?person2 .
    ?person1 foaf:name ?name1 .
    ?person2 foaf:name ?name2 .
  }
", null, 'sparql');
 
// Execute the query. The results of a SPARQL SELECT provide
// array access by using the variables used in the query as keys:
$results = $query->execute($model);
foreach ($results as $result) {
    echo $result['name1'] . " knows " . $result['name2'] . "\n";
}
?>
<!--<form>
<input type="text" name="keyword">
</form>-->