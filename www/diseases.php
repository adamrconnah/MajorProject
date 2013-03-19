<!DOCTYPE HTML>
<html>
<head>
<title>Phenomanal</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" type="text/css" href="styles/layout.css">
<script src="scripts/jquery-1.7.2.min.js"></script>
<script src="scripts/jquery.carouFredSel-5.5.2.js"></script>
<script src="scripts/jquery.easing.1.3.js"></script>
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
?>
  <table id="hor-minimalist-a" summary="Diseases">
 <thead>
    	<tr>
        	<th scope="col">Disease  name (ID)</th>
            <th scope="col">Phenotype Link</th>
			<!--            <th scope="col">Link</th> -->

          
        </tr>
    </thead>
    <tbody>
  <?php

foreach($result as $line){
  $dis = $line['?dis'];
  $name =$line['?name'];  
	if (preg_match('/"([^"]+)"/', $dis, $m)) { //finds instances that match regex aka gets url
    $dis = $m[0];   	//assign first instance to dis
	$c=explode("/", $dis); //explode dis to get just end of url
	$dis=end($c); 
	$dis = str_replace('"', "", $dis); //remove quotations from string.
} 

	if (preg_match('/"([^"]+)"/', $name, $n)) {
	$name = $n[0]; 
	$name =str_replace('"', "", $name);
} 
    if($dis != ""){
      //echo $dis->toString()."..... ".$name->toString()."<br>"; // printed on same line now.  can easily turn into tables later on.
	 // echo $name->toString()."<br>";
	echo "<tr>";
        echo "<td>$name ($dis)</td>";
       // echo "<td>Phenotypes</td>"; //edge pheno and inferred
		//trim string 
        echo "<td><a href='ontologyterms.php?searchQuery=$dis'>Phenotypes</a></td>"; //edge
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


</body></html>