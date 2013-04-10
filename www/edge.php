<!DOCTYPE HTML>
<html>
<head>
<title>Phenomanal</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" type="text/css" href="styles/layout.css">

<!--[if lt IE 9]>
<script src="scripts/html5.js"></script>
<script src="scripts/css3-mediaqueries.min.js"></script>
<![endif]-->
</head>
<body>
<div class="wrapper">
	<div id="top" class="clearfix">
		<!-- add in logo -->
		<div id="logo"><img id="logoimage" src="" alt=""> 
		  <h1 id="logotitle">Phenomanal</h1>
		</div>
		<!--/logo-->
		<nav>
		  <ul>
			<li><a href="index.html">Home</a></li>
			<li><a href="#">Data</a></li>
			<li><a href="work.html">Help</a></li>
			<li><a href="#">Contact</a></li>
		  </ul>
		</nav>
	</div>
  <header>
    <h1><span>Kitty</span> ipsum dolor sit amet, attack et orci turpis quis vehicula, pellentesque kittens stuck in a tree I don't like that food feed me hiss. </h1>
    <h2>Fluffy fur et bat tortor in viverra</h2>
  </header>
 
 <!--  /********************************** Body goes here**************************/  -->
<a name="content"></a>
 <ul>
	<li><a href="#OMIM">For OMIM prefix OMIM_</a></li>
	<li><a href="#MGI">For mouse prefix MGI</a></li>
	<li><a href="#ORPHANET">For  ORPHANET prefix  ORPHANET_</a></li>
	<li><a href="#RGD">For RAT RGD</a></li>
	<li><a href="#FB">For fly FB</a></li>
	<li><a href="#WB">For worm WB</a></li>
	<li><a href="#S0">For yeast S0</a></li>
	<li><a href="#ZD">For zebrafish ZD</a></li>
	<li><a href="#ZD">For slime mold DBS</a></li>
	
</ul>
 <a name="OMIM"></a>  
  <?php 
//searches for specific diseases using index.html and POST to get the variable submitted
	if (isset($_GET['searchQuery'])){ 
    $searchQuery = $_GET['searchQuery'];

}

$searchQuery2="phe:";
$searchQuery=$searchQuery2.$searchQuery;



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
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name .
			 
		FILTER(?value > '0.2') .
		FILTER (?node != $searchQuery)  .
		FILTER regex(?node, 'OMIM', 'i')
}
ORDER BY DESC(?value)
";


$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all 
//results of the variable ?title.
?>
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
	/*if (preg_match('/"([^"]+)"/', $edge, $m)) { //finds instances that match regex aka gets url
    $edge = $m[0];   	//assign first instance to dis
	$c=explode("/", $edge); //explode dis to get just end of url
	$edge=end($c); 
	$edge = str_replace('"', "", $edge); //remove quotations from string.
} */
if (preg_match('/"([^"]+)"/', $node, $m)) { //finds instances that match regex aka gets url
    $node = $m[0];   	//assign first instance to dis
	$c=explode("/", $node); //explode dis to get just end of url
	$node=end($c); 
	$node = str_replace('"', "", $node); //remove quotations from string.
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
	$dis2 =str_replace('OMIM_', "", $node);
	
    if($name != ""){
      //echo $dis->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $name->toString()."<br>";
	echo "<tr>";
        echo "<td>$name (<a href='http://omim.org/entry/$dis2'>$node</a>)</td>";
		echo "<td>$value</td>"; //edge

       // echo "<td>Phenotypes</td>"; //edge pheno and inferred
		//trim string 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; //edge
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; //edge

        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   //   (".*?")   < finds everything in the quotes. 
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
?>
<a href="#content">TOP</a>

 <a name="MGI"></a>  

<?php

//mgi

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
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name
			  
		FILTER (?node != $searchQuery) 
		FILTER regex(?node, 'MGI', 'i')
}
ORDER BY DESC(?value)
";


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
?>
<a href="#content">TOP</a>
<h3> MGI </h3>

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
	/*if (preg_match('/"([^"]+)"/', $edge, $m)) { //finds instances that match regex aka gets url
    $edge = $m[0];   	//assign first instance to dis
	$c=explode("/", $edge); //explode dis to get just end of url
	$edge=end($c); 
	$edge = str_replace('"', "", $edge); //remove quotations from string.
} */
if (preg_match('/"([^"]+)"/', $node, $m)) { //finds instances that match regex aka gets url
    $node = $m[0];   	//assign first instance to dis
	$c=explode("/", $node); //explode dis to get just end of url
	$node=end($c); 
	$node = str_replace('"', "", $node); //remove quotations from string.
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
	//$dis2 =str_replace('OMIM_', "", $node);
	
    if($name != ""){
      //echo $dis->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $name->toString()."<br>";
	echo "<tr>";
		echo "<td>$name (<a href='http://www.informatics.jax.org/searchtool/Search.do?query=$node'>$node</a>)</td>";

		echo "<td>$value</td>"; //edge

       // echo "<td>Phenotypes</td>"; //edge pheno and inferred
		//trim string 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; //edge
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; //edge

        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   //   (".*?")   < finds everything in the quotes. 
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
?>
<a href="#content">TOP</a>

 <a name="ORPHANET"></a>  

<?php
//Orphanet

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
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name
			  
		FILTER (?node != $searchQuery) 
		FILTER regex(?node, 'ORPHANET', 'i')
}
ORDER BY DESC(?value)
";


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
?>
<h3> Orphanet </h3>

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
	/*if (preg_match('/"([^"]+)"/', $edge, $m)) { //finds instances that match regex aka gets url
    $edge = $m[0];   	//assign first instance to dis
	$c=explode("/", $edge); //explode dis to get just end of url
	$edge=end($c); 
	$edge = str_replace('"', "", $edge); //remove quotations from string.
} */
if (preg_match('/"([^"]+)"/', $node, $m)) { //finds instances that match regex aka gets url
    $node = $m[0];   	//assign first instance to dis
	$c=explode("/", $node); //explode dis to get just end of url
	$node=end($c); 
	$node = str_replace('"', "", $node); //remove quotations from string.
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
      //echo $dis->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $name->toString()."<br>";
	echo "<tr>";
		echo "<td>$name (<a href='http://www.orpha.net/consor/cgi-bin/Disease_Search_Simple.php?lng=EN&Disease_Disease_Search_diseaseType=ORPHA&Disease_Disease_Search_diseaseGroup=$node2'>$node</a>)</td>";

		echo "<td>$value</td>"; //edge

       // echo "<td>Phenotypes</td>"; //edge pheno and inferred
		//trim string 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; //edge
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; //edge

        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   //   (".*?")   < finds everything in the quotes. 
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
?>
<a href="#content">TOP</a>

 <a name="RGD"></a>  

<?php
//rat RGD

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
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name
			  
		FILTER (?node != $searchQuery) 
		FILTER regex(?node, 'RGD', 'i')
}
ORDER BY DESC(?value)
";


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
?>
<h3> RGD </h3>

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
	/*if (preg_match('/"([^"]+)"/', $edge, $m)) { //finds instances that match regex aka gets url
    $edge = $m[0];   	//assign first instance to dis
	$c=explode("/", $edge); //explode dis to get just end of url
	$edge=end($c); 
	$edge = str_replace('"', "", $edge); //remove quotations from string.
} */
if (preg_match('/"([^"]+)"/', $node, $m)) { //finds instances that match regex aka gets url
    $node = $m[0];   	//assign first instance to dis
	$c=explode("/", $node); //explode dis to get just end of url
	$node=end($c); 
	$node = str_replace('"', "", $node); //remove quotations from string.
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
      //echo $dis->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $name->toString()."<br>";
	echo "<tr>";
        echo "<td>$name</td>";
		echo "<td>$name (<a href='http://www.orpha.net/consor/cgi-bin/Disease_Search_Simple.php?lng=EN&Disease_Disease_Search_diseaseType=ORPHA&Disease_Disease_Search_diseaseGroup=$node2'>$node</a>)</td>";

		echo "<td>$value</td>"; //edge

       // echo "<td>Phenotypes</td>"; //edge pheno and inferred
		//trim string 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; //edge
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; //edge

        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   //   (".*?")   < finds everything in the quotes. 
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
?>
<a href="#content">TOP</a>

 <a name="FB"></a>  

 <?php
//FB fly

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
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name
			  
		FILTER (?node != $searchQuery) 
		FILTER regex(?node, 'FB', 'i')
}
ORDER BY DESC(?value)
";


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
?>
<h3> FB </h3>

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
	/*if (preg_match('/"([^"]+)"/', $edge, $m)) { //finds instances that match regex aka gets url
    $edge = $m[0];   	//assign first instance to dis
	$c=explode("/", $edge); //explode dis to get just end of url
	$edge=end($c); 
	$edge = str_replace('"', "", $edge); //remove quotations from string.
} */
if (preg_match('/"([^"]+)"/', $node, $m)) { //finds instances that match regex aka gets url
    $node = $m[0];   	//assign first instance to dis
	$c=explode("/", $node); //explode dis to get just end of url
	$node=end($c); 
	$node = str_replace('"', "", $node); //remove quotations from string.
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
      //echo $dis->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $name->toString()."<br>";
	echo "<tr>";
		echo "<td>$name (<a href='http://flybase.org/reports/$node'>$node</a>)</td>";
		
		echo "<td>$value</td>"; //edge

       // echo "<td>Phenotypes</td>"; //edge pheno and inferred
		//trim string 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; //edge
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; //edge

        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   //   (".*?")   < finds everything in the quotes. 
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
?>
<a href="#content">TOP</a>

 <a name="WB"></a>  

<?php
//wb worm
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
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name
			  
		FILTER (?node != $searchQuery) 
		FILTER regex(?node, 'WB', 'i')
}
ORDER BY DESC(?value)
";


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
?>
<h3> worm WB </h3>

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
	/*if (preg_match('/"([^"]+)"/', $edge, $m)) { //finds instances that match regex aka gets url
    $edge = $m[0];   	//assign first instance to dis
	$c=explode("/", $edge); //explode dis to get just end of url
	$edge=end($c); 
	$edge = str_replace('"', "", $edge); //remove quotations from string.
} */
if (preg_match('/"([^"]+)"/', $node, $m)) { //finds instances that match regex aka gets url
    $node = $m[0];   	//assign first instance to dis
	$c=explode("/", $node); //explode dis to get just end of url
	$node=end($c); 
	$node = str_replace('"', "", $node); //remove quotations from string.
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
      //echo $dis->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $name->toString()."<br>";
	echo "<tr>";
        
		echo "<td>$name (<a href='http://www.wormbase.org/search/all/$node'>$node</a>)</td>";

		echo "<td>$value</td>"; //edge

       // echo "<td>Phenotypes</td>"; //edge pheno and inferred
		//trim string 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; //edge
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; //edge

        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   //   (".*?")   < finds everything in the quotes. 
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
?>
<a href="#content">TOP</a>

 <a name="S0"></a>  

<?php
//yeast S0

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
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name
			  
		FILTER (?node != $searchQuery) 
		FILTER regex(?node, 'S0', 'i')
}
ORDER BY DESC(?value)
";


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
?>
<h3> YEAST SO </h3>

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
	/*if (preg_match('/"([^"]+)"/', $edge, $m)) { //finds instances that match regex aka gets url
    $edge = $m[0];   	//assign first instance to dis
	$c=explode("/", $edge); //explode dis to get just end of url
	$edge=end($c); 
	$edge = str_replace('"', "", $edge); //remove quotations from string.
} */
if (preg_match('/"([^"]+)"/', $node, $m)) { //finds instances that match regex aka gets url
    $node = $m[0];   	//assign first instance to dis
	$c=explode("/", $node); //explode dis to get just end of url
	$node=end($c); 
	$node = str_replace('"', "", $node); //remove quotations from string.
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
      //echo $dis->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $name->toString()."<br>";
	echo "<tr>";
		echo "<td>$name (<a href='http://www.wormbase.org/search/all/$node'>$node</a>)</td>";
		echo "<td>$value</td>"; //edge

       // echo "<td>Phenotypes</td>"; //edge pheno and inferred
		//trim string 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; //edge
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; //edge

        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   //   (".*?")   < finds everything in the quotes. 
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
?>
<a href="#content">TOP</a>

<a name="ZD"></a> 
<?php
//ZD Zebrafish

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
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { $searchQuery phe:has_edge ?edge .
        ?edge phe:has_value ?value ;
              phe:has_node ?node .
		?node phe:has_name ?name
			  
		FILTER (?node != $searchQuery) 
		FILTER regex(?node, 'ZD', 'i')
}
ORDER BY DESC(?value)
";


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
?>
<h3> Zebrafish ZD</h3>

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
	/*if (preg_match('/"([^"]+)"/', $edge, $m)) { //finds instances that match regex aka gets url
    $edge = $m[0];   	//assign first instance to dis
	$c=explode("/", $edge); //explode dis to get just end of url
	$edge=end($c); 
	$edge = str_replace('"', "", $edge); //remove quotations from string.
} */
if (preg_match('/"([^"]+)"/', $node, $m)) { //finds instances that match regex aka gets url
    $node = $m[0];   	//assign first instance to dis
	$c=explode("/", $node); //explode dis to get just end of url
	$node=end($c); 
	$node = str_replace('"', "", $node); //remove quotations from string.
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
      //echo $dis->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $name->toString()."<br>";
	echo "<tr>";
		echo "<td>$name (<a href='http://zfin.org/action/quicksearch/query?query=$node'>$node</a>)</td>";
		echo "<td>$value</td>"; //edge

       // echo "<td>Phenotypes</td>"; //edge pheno and inferred
		//trim string 
		echo "<td><a href='ontologyterms.php?searchQuery=$node'>Phenotypes</a></td>"; //edge
		echo "<td><a href='edge.php?searchQuery=$node'>Explore</a></td>"; //edge

        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   //   (".*?")   < finds everything in the quotes. 
}
echo "</table>";
//SPARQLEngine::writeQueryResultAsHtmlTable($result); 
?>
<a href="#content">TOP</a>

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
      <p class="right">Website Template By <a target="_blank" href="http://www.birondesign.com/">Chris Biron</a> &amp; Modified By <a href="http://www.os-templates.com/">OS Templates</a></p>
    </section>
  </div>
</footer>

</body></html>
