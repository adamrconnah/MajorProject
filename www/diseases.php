<?php 
//searches for specific diseases using index.html and POST to get the variable submitted
	if (isset($_POST['searchQuery'])){ 
    $searchQuery = $_POST['searchQuery'];

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
   ?dis phe:has_name ?name .
		          FILTER regex(?name, '$searchQuery', 'i')
}
LIMIT 20";



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
echo "<table>";

foreach($result as $line){
  $value = $line['?dis'];
  $value2 =$line['?name'];  
    if($value != ""){
      //echo $value->toString()."..... ".$value2->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $value2->toString()."<br>";
	echo "<tr>";
        echo "<td>$value2 ($value)</td>";
        echo "<td>Phenotypes</td>"; //edge pheno and inferred
		//trim string 
        echo "<td><a href='ontologyterms.php?searchQuery=$value'>Phenotypes</a></td>"; //edge
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   //   (".*?")   < finds everything in the quotes. 
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
