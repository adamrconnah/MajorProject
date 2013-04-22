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

<body>
<div class="wrapper">
	<div id="top" class="clearfix">
		<!-- add in logo -->
		<div id="logo"><img id="logoimage" src="tree.png" alt="Image of a wasp"> 
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
  <p>Search disease or gene name <span>e.g Alzheimer</span></p>


<form name="login" action="diseases.php"  onsubmit="return validateForm()" method="post" >
<input type="text" name="searchQuery" placeholder="e.g Alzheimer"> 

<input type="submit" value="Search">
</form>
<br />
<br />
<?php 
//Gets variable from GET method
	if (isset($_GET['searchQuery'])){ 
    $searchQuery = $_GET['searchQuery'];
	$searchQuery = str_replace('MP', "MP_", $searchQuery); //changes variable to appropriate format for query.

}
 echo "<p>The table below provides a list of diseases which are related to the phenotype ID: <span>$searchQuery</span></p>";


//include RAP API RDF library
define("RDFAPI_INCLUDE_DIR", "rdfapi-php/api/");
include(RDFAPI_INCLUDE_DIR . "RdfAPI.php");
//concatanates two strings to form a varialbe which can be used directly in a sparql query.
$searchQuery2="obo:";
$searchQuery=$searchQuery2.$searchQuery;
//assign endpoint to sparql client
$client = ModelFactory::getSparqlClient("http://biocrunch.dcs.aber.ac.uk:8890/sparql"); 
//Final all diseases which are related to the phenotype selected
$querystring = "
PREFIX phe: <http://phenomebrowser.org/phenomenet/>
PREFIX obo: <http://obofoundry.org/obo/>
select *
FROM <http://biocrunch.dcs.aber.ac.uk:8890/DAV/complete>

where {
   ?dis phe:has_inferred_phenotype $searchQuery .
      ?dis phe:has_name ?name .


	
}
GROUP BY ?dis
ORDER BY ASC(?name)
";

//Group by - this elimates replications of identical entries and only prints it once.


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
            <th scope="col">Explore</th>
			<th scope="col">Phenotype Link</th>        
        </tr>
</thead>
    <tbody>
  <?php
//for every results found.
// each occurence of ?variable is assigned to a php variable.

foreach($result as $line){
  $name = $line['?name'];
    $dis = $line['?dis'];
  $pheno =$line['?pheno'];  
  
	if (preg_match('/"([^"]+)"/', $name, $m)) { //preg_match is used to seperate the ID needed from the URL given.
    $name = $m[0];   							//assign first instance to variable
	$c=explode("/", $name); 					//explode variable to get just end of url
	$name=end($c); 								//gets the end of the array of array
	$name = str_replace('"', "", $name); 		//remove quotations from string.
} 
if (preg_match('/"([^"]+)"/', $dis, $m)) {
    $dis = $m[0];   	
	$c=explode("/", $dis); 
	$dis=end($c); 
	$dis = str_replace('"', "", $dis); 
} 


    if($dis != ""){// if the variable is not empty
//Below, format results into a table, as well as giving url's variables      
	echo "<tr>";
        echo "<td>$name</td>";
		echo "<td><a href='edge.php?searchQuery=$dis'>Explore</a></td>";     
        echo "<td><a href='ontologyterms.php?searchQuery=$dis'>Phenotypes</a></td>"; 
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
      <p class="right">Website Template By <a target="_blank" href="http://www.birondesign.com/">Chris Biron</a> &amp; Modified By Adam Connah</p>
    </section>
  </div>
</footer>

</body></html>
