<?php 
//Gets variable from POST method
	if (isset($_POST['searchQuery'])){ 
    $searchQuery = $_POST['searchQuery'];

}
	
//include RAP API RDF library
define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

//assign endpoint to sparql client
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 

//Find the name of all diseases which match the varible passed
$querystring = "
PREFIX phe: <http://phenomebrowser.org/phenomenet/>
PREFIX obo: <http://obofoundry.org/obo>
select *
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>
where {
FILTER regex(?name, '$searchQuery', 'i')
?dis phe:has_name ?name .
FILTER(REGEX(STR(?dis), '^http://phenomebrowser.org/phenomenet'))
}

LIMIT 200";
// ******Comments below refer to query above********
//FROM explains which graph.
//First filter uses the variable to look for only diseases with this word in its name. 'i' means case insensitive
//second filter - allows for onyl diseases, and not phenotypes
// Limit - limits to 200 results.



//To execute the query, we create a new ClientQuery 
//object and pass it to the SPARQL client:
$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all 
//results of the variable 
?>
<!-- Table headings -->
  <table id="hor-minimalist-a" summary="Diseases">
 <thead>
    	<tr>
        	<th scope="col">Disease  name (ID)</th>
            <th scope="col">Phenotype Link</th>
            <th scope="col">Explore</th>
		</tr>
    </thead>
    <tbody>
  <?php
//for every results found.
foreach($result as $line){
// each occurence of ?variable is assigned to a php variable.
  $dis = $line['?dis'];
  $name = $line['?name'];  

	if (preg_match('/"([^"]+)"/', $dis, $m)) { 			//preg_match is used to seperate the ID needed from the URL given.
    $dis = $m[0];   									//assign first instance to variable
	$c=explode("/", $dis); 								//explode variable to get just end of url
	$dis=end($c); 										//gets the end of the array of array
	$dis = str_replace('"', "", $dis); 					//remove quotations from string.
} 

	if (preg_match('/"([^"]+)"/', $name, $n)) {			//preg_match is used to seperate the ID needed from the URL given.
	$name = $n[0]; 										//assign first instance to dis
	$name =str_replace('"', "", $name);					//remove quotations from string.
} 
	$dis2 =str_replace('OMIM_', "", $dis);				//remove part of string from string.

    if($dis != ""){										// if the variable is not empty
//Below, format results into a table, as well as giving url's variables
	echo "<tr>";
        echo "<td>$name (<a href='http://omim.org/entry/$dis2'>$dis</a>)</td>";
		echo "<td><a href='ontologyterms.php?searchQuery=$dis'>Phenotypes</a></td>"; 
     	echo "<td><a href='edge.php?searchQuery=$dis'>Explore</a></td>"; 
		echo "</tr>";
	  }
    else{
      echo "undbound<br>"; // if no results found we print unbound.
	  }
}
echo "</table>";
?>