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
    <h1><span>Phenomeanal</span> is a cross species phenotype network which allows the fast analysis of the similarity between different phenotypes in organisms, (yeast, fish, worm, fly, rat, slime mold and mouse model) as well as human diseases (OMIM and OrphaNet)

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
<?php
//Gets variable from GET method
	if (isset($_GET['searchQuery'])){ 
	$_GET["searchQuery"];
	    $searchQuery = $_GET['searchQuery'];
}

//include RAP API RDF library
define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");

//concatanate two strings. So it can be used directly within sparql query
$searchQuery2="phe:";
$searchQuery=$searchQuery2.$searchQuery;

//assign endpoint to sparql client
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
//Find the name of all phenotypes related to disease
$querystring = "
PREFIX phe: <http://phenomebrowser.org/phenomenet/>
PREFIX obo: <http://obofoundry.org/obo>
select ?pheno ?name
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { 
	$searchQuery phe:has_phenotype ?pheno .
	?pheno phe:has_name  ?name
}
	GROUP BY ?pheno
	ORDER BY ASC(?pheno)
";

// ******Comments below refer to query above********
//FROM explains which graph.
//Group by - this elimiates multiple results which are the same, and only enteres them as one entry.
// Order by - alphabetical order.

//To execute the query, we create a new ClientQuery 
//object and pass it to the SPARQL client:

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

//The following code loops over the result set and prints out all 
//results of the variable
?>
<h3>Phenotypes directly associated with <?php echo $searchQuery ?></h3>

  <table id="hor-minimalist-a" summary="Phenotype table">
 <thead>
    	<tr>
        	<th scope="col">Phenotype ID</th>
            <th scope="col">Name</th>
          
        </tr>
    </thead>
    <tbody>
  <?php
  //for every results found.

foreach($result as $line){
  $pheno = $line['?pheno'];
  $name =$line['?name'];  
  // each occurence of ?variable is assigned to a php variable.

  if (preg_match('/"([^"]+)"/', $pheno, $m)) {  //preg_match is used to seperate the ID needed from the URL given.
    $pheno = $m[0];   							//assign first instance to variable
	$c=explode("/", $pheno); 					//explode variable to get just end of url
	$pheno=end($c); 							//gets the end of the array of array
	$pheno = str_replace('"', "", $pheno); 		//remove quotations from string.
} 

	if (preg_match('/"([^"]+)"/', $name, $n)) {
	$name = $n[0]; 
	$name =str_replace('"', "", $name);
} 
  //Below, format results into a table, as well as giving url's variables

    if($name != ""){						   	// if the variable is not empty

		echo "<tr>";
		$pheno = str_replace('_', ":", $pheno); //maniplulates tring, so the variable can be used to produce a valid url

        echo "<td><a href='https://www.ebi.ac.uk/ontology-lookup/?termId=$pheno'>$pheno</a></td>";
        echo "<td>$name</td>";
        echo "</tr>";
	  }
    else{
      echo "undbound<br>"; 	// if no results found we print unbound.
	  }
	   
}
echo "</table>";

//****************************************************************************************************************
//****************************************************************************************************************
// Same code below, except the query differs and is explained 
//****************************************************************************************************************
//****************************************************************************************************************

	if (isset($_GET['searchQuery'])){ 
	$_GET["searchQuery"];
	    $searchQuery = $_GET['searchQuery'];
}

define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");
$searchQuery2="phe:";
$searchQuery=$searchQuery2.$searchQuery;
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
$querystring = "
PREFIX phe: <http://phenomebrowser.org/phenomenet/>
PREFIX obo: <http://obofoundry.org/obo>
select ?infpheno ?name
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where { 
	$searchQuery phe:has_inferred_phenotype ?infpheno .
	?infpheno phe:has_name  ?name
}
	GROUP BY ?infpheno
	ORDER BY ASC(?infpheno)
";
// searches for inferred phenotypes.

$query = new ClientQuery();
$query->query($querystring);
$result = $client->query($query);

?>
<h3>Mammalian Phenotypes inferred for <?php echo $searchQuery ?></h3>

  <table id="hor-minimalist-a" summary="Inferred Phenotype table">
 <thead>
    	<tr>
        	<th scope="col">Inferred Phenotype ID</th>
            <th scope="col">Name</th>
          
        </tr>
    </thead>
    <tbody>
  <?php
foreach($result as $line){
  $infpheno = $line['?infpheno'];
  $name =$line['?name'];  
  
  if (preg_match('/"([^"]+)"/', $infpheno, $m)) { 
    $infpheno = $m[0];   	
	$c=explode("/", $infpheno); 
	$infpheno=end($c); 
	$infpheno = str_replace('"', "", $infpheno);
} 

	if (preg_match('/"([^"]+)"/', $name, $n)) {
	$name = $n[0]; 
	$name =str_replace('"', "", $name);
} 
  

    if($name != ""){
		echo "<tr>";
		$infpheno = str_replace('_', ":", $infpheno);
        echo "<td><a href='https://www.ebi.ac.uk/ontology-lookup/?termId=$infpheno'>$infpheno</a></td>";
        echo "<td>$name</td>";
        echo "</tr>";
	  }
    else{
      echo "undbound<br>";
	  }
	   
}
echo "</table>";
?>

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
