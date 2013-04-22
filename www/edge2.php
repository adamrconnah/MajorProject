<!DOCTYPE HTML>
<html>
<head>
<title>PhenomeRDF</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" type="text/css" href="styles/layout.css">

<!--[if lt IE 9]>
<script src="scripts/html5.js"></script>
<script src="scripts/css3-mediaqueries.min.js"></script>
<![endif]-->
</head>
<?php
//Gets variable from GET method
	if (isset($_GET['searchQuery'])){ 
    $searchQuery = $_GET['searchQuery'];

}
?>
<body>
<div class="wrapper">
	<div id="top" class="clearfix">
		<!-- add in logo -->
		<div id="logo"><img id="logoimage" src="" alt=""> 
		  <h1 id="logotitle">PhenomeRDF</h1>
		</div>
		<!--Menu-->
		<nav>
		   <ul>
			<li><a href="index.html">Home</a></li>
			<li><a href="data.html">Data</a></li>
			<li><a href="tree.html">Tree</a></li>
			<li><a href="work.html">Help</a></li>
			<li><a href="contact.html">Contact</a></li>
		  </ul>
		</nav>
	</div>
 	 <header>
  	<!-- Description-->
    <h1><span>PhenomeRDF</span> is a cross species phenotype network which allows the fast analysis of the similarity between different phenotypes in organisms, (yeast, fish, worm, fly, rat, slime mold and mouse model) as well as human diseases (OMIM and OrphaNet)

The application can be used to find diseases which are related using their phenotypic similarity value.
  </h1>
    <!--<h2>Fluffy fur et bat tortor in viverra</h2> -->
  </header>
<aside id="about" class="search">
 <script>
function validateForm()
{
var x=document.forms["login"]["searchQuery"].value;
if (x==null || x=="")
  {
  alert("Input keyword");
  return false;
  }
}</script>
  
 <h4>Search disease name e.g Alzheimer</h4>
<form name="login" action="diseases.php"  onsubmit="return validateForm()" method="post" >
<input type="text" name="searchQuery" placeholder="e.g Alzheimer"> 

<input type="submit" value="Search">
</form>
 
 <!--  /********************************** Body goes here**************************/  -->
<!-- List of contents for page. Easy to jump to lower parts -->
<p> The tables below show the diseases which are related to <span><?php echo $searchQuery ?></span></p>


 <a name="content"></a>
 <ul>
	<li><a href="#OMIM">Related Human diseases, prefix OMIM</a></li>
	<li><a href="#MGI">Related Mouse diseases, prefix MGI</a></li>
	<li><a href="#ORPHANET">Related Human diseases, ORPHANET</a></li>
	<li><a href="#RGD">Related Rat diseases, prefix RGD</a></li>
	<li><a href="#FB">Related Fly diseases, prefix FB</a></li>
	<li><a href="#WB">Related Worm diseases, prefix WB</a></li>
	<li><a href="#S0">Related Yeast diseases, prefix S0</a></li>
	<li><a href="#ZD">Related Zebrafish diseases, prefix ZD</a></li>
	<li><a href="#ZD">Related Slime Mold diseases, prefix DBS</a></li>
	
</ul>

 <a name="OMIM"></a>  
  <?php 

//Concatinating two strings, as the "phe:" is needed so it can be used in the query.
$searchQuery2="phe:";
$searchQuery=$searchQuery2.$searchQuery;

//include RAP API RDF library
define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

//assign endpoint to sparql client
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 

//Find the name of all diseases which have an edge, where the 
//two nodes are not the same. (aka it is not compared to itself)
$querystring = "

PREFIX phe: <http://phenomebrowser.org/phenomenet/>
PREFIX obo: <http://obofoundry.org/obo>

select *
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name .
			 
		
		FILTER (?node != $searchQuery)  .
		FILTER regex(?node, 'OMIM', 'i')
}
ORDER BY DESC(?value)
";
//First filter uses the variable to look for only results which do not match itself. "i" means case insensitive
//Order - orders the results by value.
//FILTER(?value > '0.2') .

//To execute the query, we create a new ClientQuery 
//object and pass it to the SPARQL client:
$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all 
//results of the variable 
?>
<!-- Table headings -->
<h3> OMIM </h3>
  <table id="hor-minimalist-a" summary="Diseases">
 <thead>
    	<tr>
        	<th scope="col">Edge  name (ID)</th>
            <th scope="col">Similarity Value</th>
			<th scope="col">Phenotypes</th>
			<th scope="col">Explore</th>

			

			<!--            <th scope="col">Link</th> -->

          
        </tr>
    </thead>
    <tbody>
   <?php

foreach($result as $line){
  $name = $line['?name'];

  $value = $line['?value'];
  $node =$line['?node'];  
	
if (preg_match('/"([^"]+)"/', $node, $m)) { //preg_match is used to seperate the ID needed from the URL given.
    $node = $m[0];   						//assign first instance to variable
	$c=explode("/", $node); 				//explode variable to get just end of url
	$node=end($c); 							//gets the end of the array of array
	$node = str_replace('"', "", $node); 	//remove quotations from string.
} 
//Above comments explain similar methods below unless stated.
if (preg_match('/"([^"]+)"/', $name, $n)) {
	$name = $n[0]; 
	$name =str_replace('"', "", $name);
} 
	if (preg_match('/"([^"]+)"/', $value, $n)) {
	$value = $n[0]; 
	$value =str_replace('"', "", $value);
} 
	if (preg_match('/"([^"]+)"/', $node, $n)) {
	$node = $n[0]; 
	$node =str_replace('"', "", $node);
}
	$dis2 =str_replace('OMIM_', "", $node);	//remove part of string from string.
	
    if($name != ""){						// if the variable is not empty
   //Below, format results into a table, as well as giving url's variables

	echo "<tr>";
        echo "<td>$name (<a href='http://omim.org/entry/$dis2'>$node</a>)</td>";
		echo "<td>$value</td>"; 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>";
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; 
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";	// if no results found we print unbound.
	  }
}
echo "</table>";
?>