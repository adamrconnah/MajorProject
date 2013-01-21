<?php
ini_set('display_errors', 1);
 ini_set('log_errors', 1);
 ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
 error_reporting(E_ALL);
// Include RAP
define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

// Filename of an RDF document
$base="testrdf.rdf";

// Create a new MemModel
$model = ModelFactory::getDefaultModel();

// Load and parse document
$model->load($base);

// Output model as HTML table
$model->writeAsHtmlTable();
echo "<P>";
// Create new statements and add them to model
$statement1 = new Statement(new Resource("http://www.example.co.uk/genotype/disease#A"),
new Resource("http://dublincore.org/documents/2012/06/14/dcmi-terms/?v=elements#title"),
new Literal("disease A"));

$statement2 = new Statement(new Resource("http://www.example.co.uk/genotype/disease#B"),
new Resource("http://dublincore.org/documents/2012/06/14/dcmi-terms/?v=elements#title"),
new Literal("disease B"));

$model->add($statement1);
$model->add($statement2);

$model->writeAsHtmlTable();
echo "<P>";
// Build search index to speed up searches.
$model->index();

// Search model 1
$search = new Resource("http://www.example.co.uk/genotype/disease#B");
$res = $model->find($search, NULL, NULL);

$res->writeAsHtmlTable();
echo "<P>";


// Search model 2
$description = new Resource("http://dublincore.org/documents/2012/06/14/dcmi-terms/?v=elements#title");
$statement = $model->findFirstMatchingStatement($search, $description, NULL);

// Check if something was found and output result
if ($statement) {
echo $statement->toString();
} else {
echo "Sorry, I didn't find anything.";
}
echo "<P>";

// Search model 3
$res3 = $model->findVocabulary("");
$res3->writeAsHtmlTable();
echo "<P>";
$model->writeAsHtml();