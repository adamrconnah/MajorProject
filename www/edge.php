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
		<div id="logo"><img id="logoimage" src="tree.png" alt=""> 
		  <h1 id="logotitle">PhenomeRDF</h1>
		</div>
		<!--Menu-->
		<nav>
		   <ul>
			<li><a href="index.html">Home</a></li>			
			<li><a href="tree.html">Tree</a></li>
			<li><a href="help.html">Help</a></li>
			<li><a href="data.html">Data</a></li>
			<li><a href="contact.html">Contact</a></li>
		  </ul>
		</nav>
	</div>
 	 <header>
  	<!-- Description-->
       <h1><span>Related Diseases</span>  </h1>

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
  
 <p>Search disease or gene name <span>e.g Alzheimer</span></p>


<form name="login" action="diseases.php"  onsubmit="return validateForm()" method="post" >
<input type="text" name="searchQuery" placeholder="e.g Alzheimer"> 

<input type="submit" value="Search">
</form>
<br />
 
 <!--  /********************************** Body goes here**************************/  -->
<!-- List of contents for page. Easy to jump to lower parts -->
<p> The tables below show the diseases which are related to <span><?php echo $searchQuery ?></span></p>
<br />

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
ORDER BY DESC(?value) Limit 200
";
// ******Comments below refer to query above********
//FROM explains which graph.
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

 <a name="MGI"></a>

<?php
//*****************************************************************************************************************************************
//*****************************************************************************************************************************************
//*****************************************************************************************************************************************
//*****************************************************************************************************************************************
//From below onwards, unless otherwise stated, the code is the same as above. Other than Filter options in query.
//
//*****************************************************************************************************************************************
//*****************************************************************************************************************************************
//*****************************************************************************************************************************************
//*****************************************************************************************************************************************

define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
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
		FILTER regex(?node, 'MGI', 'i')
}
ORDER BY DESC(?value) Limit 200";


$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

?>
<p><a href="#content">TOP</a></p><h3> MGI </h3>

  <table id="hor-minimalist-a" summary="Diseases">
 <thead>
    	<tr>
        	<th scope="col">Edge  name (ID)</th>
            <th scope="col">Similarity Value</th>
			<th scope="col">Phenotypes</th>
			<th scope="col">Explore</th>


          
        </tr>
    </thead>
    <tbody>
   <?php

foreach($result as $line){
  $name = $line['?name'];
  $value = $line['?value'];
  $node =$line['?node'];  
	
if (preg_match('/"([^"]+)"/', $node, $m)) { 
    $node = $m[0];   	
	$c=explode("/", $node); 
	$node=end($c); 
	$node = str_replace('"', "", $node); 
} 
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
	
    if($name != ""){
    
	echo "<tr>";
		echo "<td>$name (<a href='http://www.informatics.jax.org/searchtool/Search.do?query=$node'>$node</a>)</td>";
		echo "<td>$value</td>"; 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; 
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; 
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
}
echo "</table>";
?>
<p><a href="#content">TOP</a></p>
 <a name="ORPHANET"></a>  

<?php
//Orphanet

define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
$querystring = "

PREFIX phe: <http://phenomebrowser.org/phenomenet/>
PREFIX obo: <http://obofoundry.org/obo>
select *
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>
where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name .
			  
		FILTER (?node != $searchQuery) .
		FILTER regex(?node, 'ORPHANET', 'i')
}
ORDER BY DESC(?value) Limit 200";

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

?>
<h3> Orphanet </h3>

  <table id="hor-minimalist-a" summary="Diseases">
 <thead>
    	<tr>
        	<th scope="col">Edge  name (ID)</th>
            <th scope="col">Similarity Value</th>
			<th scope="col">Phenotypes</th>
			<th scope="col">Explore</th>          
        </tr>
    </thead>
    <tbody>
   <?php

foreach($result as $line){
  $name = $line['?name'];
  $value = $line['?value'];
  $node =$line['?node'];  
	
if (preg_match('/"([^"]+)"/', $node, $m)) { 
    $node = $m[0];   	
	$c=explode("/", $node);
	$node=end($c); 
	$node = str_replace('"', "", $node); 
} 
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
	$node2 =str_replace('ORPHANET_', "", $node);
    if($name != ""){
     echo "<tr>";
		echo "<td>$name (<a href='http://www.orpha.net/consor/cgi-bin/Disease_Search_Simple.php?lng=EN&Disease_Disease_Search_diseaseType=ORPHA&Disease_Disease_Search_diseaseGroup=$node2'>$node</a>)</td>";
		echo "<td>$value</td>"; 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; 
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; 
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
}
echo "</table>";
?>
<p><a href="#content">TOP</a></p>
 <a name="RGD"></a>  

<?php
//rat RGD

define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
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
		FILTER regex(?node, 'RGD', 'i')
}
ORDER BY DESC(?value) Limit 200";


$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

?>
<h3> RGD </h3>

  <table id="hor-minimalist-a" summary="Diseases">
 <thead>
    	<tr>
        	<th scope="col">Edge  name (ID)</th>
            <th scope="col">Similarity Value</th>
			<th scope="col">Phenotypes</th>
			<th scope="col">Explore</th>          
        </tr>
    </thead>
    <tbody>
   <?php

foreach($result as $line){
  $name = $line['?name'];
  $value = $line['?value'];
  $node =$line['?node'];  
	
if (preg_match('/"([^"]+)"/', $node, $m)) {
    $node = $m[0];   	
	$c=explode("/", $node); 
	$node=end($c); 
	$node = str_replace('"', "", $node); 
} 
if (preg_match('/"([^"]+)"/', $name, $n)) {
	$name = $n[0]; 
	$name =str_replace('"', "", $name);
} 
	if (preg_match('/"([^"]+)"/', $value, $n)) {
	$value = $n[0]; 
	$value =str_replace('"', "", $value);
} 
    if($name != ""){
     
	echo "<tr>";
        echo "<td>$name</td>";
		echo "<td>$name (<a href='http://www.orpha.net/consor/cgi-bin/Disease_Search_Simple.php?lng=EN&Disease_Disease_Search_diseaseType=ORPHA&Disease_Disease_Search_diseaseGroup=$node2'>$node</a>)</td>";
		echo "<td>$value</td>"; 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>";
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; 
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
}
echo "</table>";
?>
<p><a href="#content">TOP</a></p>
 <a name="FB"></a>  

 <?php
//FB fly

define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
$querystring = "

PREFIX phe: <http://phenomebrowser.org/phenomenet/>
PREFIX obo: <http://obofoundry.org/obo>

select *
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name .
			  
		FILTER (?node != $searchQuery) .
		FILTER regex(?node, 'FB', 'i')
}
ORDER BY DESC(?value) Limit 200";

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);
?>
<h3> FB </h3>

  <table id="hor-minimalist-a" summary="Diseases">
 <thead>
    	<tr>
        	<th scope="col">Edge  name (ID)</th>
            <th scope="col">Similarity Value</th>
			<th scope="col">Phenotypes</th>
			<th scope="col">Explore</th>          
        </tr>
    </thead>
    <tbody>
   <?php

foreach($result as $line){
  $name = $line['?name'];
  $value = $line['?value'];
  $node =$line['?node'];  

if (preg_match('/"([^"]+)"/', $node, $m)) { 
    $node = $m[0];   	
	$c=explode("/", $node); 
	$node=end($c); 
	$node = str_replace('"', "", $node); 
} 
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
	$node2 =str_replace('ORPHANET_', "", $node);
    if($name != ""){
	echo "<tr>";
		echo "<td>$name (<a href='http://flybase.org/reports/$node'>$node</a>)</td>";
		echo "<td>$value</td>"; 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; 
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>";
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
}
echo "</table>";
?>
<p><a href="#content">TOP</a></p>
 <a name="WB"></a>  

<?php
//wb worm
define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
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
		FILTER regex(?node, 'WB', 'i')
}
ORDER BY DESC(?value) Limit 200";

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

?>
<h3> worm WB </h3>

  <table id="hor-minimalist-a" summary="Diseases">
 <thead>
    	<tr>
        	<th scope="col">Edge  name (ID)</th>
            <th scope="col">Similarity Value</th>
			<th scope="col">Phenotypes</th>
			<th scope="col">Explore</th>         
        </tr>
    </thead>
    <tbody>
   <?php

foreach($result as $line){
  $name = $line['?name'];
  $value = $line['?value'];
  $node =$line['?node'];  
	
if (preg_match('/"([^"]+)"/', $node, $m)) { 
    $node = $m[0];   	
	$c=explode("/", $node); 
	$node=end($c); 
	$node = str_replace('"', "", $node); 
} 
if (preg_match('/"([^"]+)"/', $name, $n)) {
	$name = $n[0]; 
	$name =str_replace('"', "", $name);
} 
	if (preg_match('/"([^"]+)"/', $value, $n)) {
	$value = $n[0]; 
	$value =str_replace('"', "", $value);
} 
    if($name != ""){
	echo "<tr>";
       	echo "<td>$name (<a href='http://www.wormbase.org/search/all/$node'>$node</a>)</td>";
		echo "<td>$value</td>"; 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; 
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; 
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   
}
echo "</table>";
?>
<p><a href="#content">TOP</a></p>
 <a name="S0"></a>  

<?php
//yeast S0

define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
$querystring = "

PREFIX phe: <http://phenomebrowser.org/phenomenet/>
PREFIX obo: <http://obofoundry.org/obo>

select *
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name .
			  
		FILTER (?node != $searchQuery) .
		FILTER regex(?node, 'S0', 'i')
}
ORDER BY DESC(?value) Limit 200";
$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

?>
<h3> YEAST SO </h3>

  <table id="hor-minimalist-a" summary="Diseases">
 <thead>
    	<tr>
        	<th scope="col">Edge  name (ID)</th>
            <th scope="col">Similarity Value</th>
			<th scope="col">Phenotypes</th>
			<th scope="col">Explore</th>          
        </tr>
    </thead>
    <tbody>
   <?php

foreach($result as $line){
  $name = $line['?name'];
  $value = $line['?value'];
  $node =$line['?node'];  

if (preg_match('/"([^"]+)"/', $node, $m)) { 
    $node = $m[0];   	
	$c=explode("/", $node); 
	$node=end($c); 
	$node = str_replace('"', "", $node); 
} 
if (preg_match('/"([^"]+)"/', $name, $n)) {
	$name = $n[0]; 
	$name =str_replace('"', "", $name);
} 
	if (preg_match('/"([^"]+)"/', $value, $n)) {
	$value = $n[0]; 
	$value =str_replace('"', "", $value);
} 
    if($name != ""){
	echo "<tr>";
		echo "<td>$name (<a href='http://www.wormbase.org/search/all/$node'>$node</a>)</td>";
		echo "<td>$value</td>"; 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; 
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; 
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
}
echo "</table>";
?>
<p><a href="#content">TOP</a></p>
<a name="ZD"></a> 
<?php
//ZD Zebrafish

define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
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
		FILTER regex(?node, 'ZD', 'i')
}
ORDER BY DESC(?value) Limit 200";

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

?>
<h3> Zebrafish ZD</h3>

  <table id="hor-minimalist-a" summary="Diseases">
 <thead>
    	<tr>
        	<th scope="col">Edge  name (ID)</th>
            <th scope="col">Similarity Value</th>
			<th scope="col">Phenotypes</th>
			<th scope="col">Explore</th>       
        </tr>
    </thead>
    <tbody>
   <?php

foreach($result as $line){
  $name = $line['?name'];
  $value = $line['?value'];
  $node =$line['?node'];  

if (preg_match('/"([^"]+)"/', $node, $m)) { 
    $node = $m[0];   	
	$c=explode("/", $node);
	$node=end($c); 
	$node = str_replace('"', "", $node); 
} 
if (preg_match('/"([^"]+)"/', $name, $n)) {
	$name = $n[0]; 
	$name =str_replace('"', "", $name);
} 
	if (preg_match('/"([^"]+)"/', $value, $n)) {
	$value = $n[0]; 
	$value =str_replace('"', "", $value);
} 
    if($name != ""){
	echo "<tr>";
		echo "<td>$name (<a href='http://zfin.org/action/quicksearch/query?query=$node'>$node</a>)</td>";
		echo "<td>$value</td>";
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; 
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; 
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
}
echo "</table>";
?>
<p><a href="#content">TOP</a></p>
 <footer id="footer" class="clearfix">
  <div class="wrapper">
    
    
    <section class="right social clear">
      <!-- Replace with any 32px x 32px icons -->
      <a href="https://plus.google.com/u/0/111044644508972712357/posts"><img class="icon" src="images/icons/google.png" alt=""></a> <a href="https://www.youtube.com/user/dudders666"><img class="icon" src="images/icons/youtube.png" alt=""></a> <a href="http://www.facebook.com/adamrconnah"><img class="icon" src="images/icons/facebook.png" alt=""></a> <a href="http://www.twitter.com/adamrconnah"><img class="icon" src="images/icons/twitter.png" alt=""></a>
      <!-- /icons -->
    </section>
    <!-- /section -->
    <section id="copyright" class="clearfix">
      <p class="left">Adam Connah<a href="#"></a> aoc9@aber.ac.uk</p>
      <p class="right">Website Template By <a target="_blank" href="http://www.birondesign.com/">Chris Biron</a> &amp; Modified By Adam Connah</p>
    </section>
  </div>
</footer>

</body></html>
