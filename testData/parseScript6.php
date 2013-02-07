<?php
$genotype = fopen("InferredPhenotype.sql", "rb"); //opens file Genotype.sql
$i=0;
while (!feof($genotype) ) { //feof = while not end of file

	$genoRow[] = fgets($genotype);  //fgets gets line
	$genoParts = explode("\t", $genoRow[$i]); //explode using tab delimiter to get 3 strings.
	$genoParts[0] = str_replace(':', '_', $genoParts[0]);
	//echo "<phenomenet:Genotype rdf:about=\"http://phenomebrowser.org/phenomenet/" .$genoParts[0]. "\" >". "\n";
	//echo "<phenomenet:has_url>http://omim.org/entry/".$genoParts[0]. "</phenomenet:has_url>". "\n";
	//echo "<phenomenet:has_name>". $genoParts[1]."</phenomenet:has_name>". "\n";												//print $genoParts[0]. "<br>" . $genoParts[1]. "<br>" . $genoParts[2] . "<br>"; //prints out the 3 parts
	
	//if ($genoRow[$i] !== ""){ 
	//	echo "genoRow line " . $i. ", genoPart 0: " .$genoParts[0] . "<br />";
	$i++;
	//}
	//open next file.
	$phenotype = fopen("OntologyTerms.sql", "rb"); 			//opens file Phenotype.sql
			$k = 0;
			while (!feof($phenotype) ){
			$phenoRow[] = fgets($phenotype);  							//fgets gets line
			$phenoParts = explode("\t", $phenoRow[$k]); 				//explode using tab delimiter to get 2 strings.
			$phenoParts = str_replace(':', '_', $phenoParts);

			if ($genoParts[1] == $phenoParts[0]){ 						//look for $genoParts[0] and compare it to $phenoParts[0]
				//echo $genoParts[0]. " has " . $phenoParts[1]. "<br />" ; 	//prints out the 3 parts
				
				echo $genoParts[0] . " " . $genoParts[1];

				}
				$k++;
			//fclose($phenotype);	
			}
}
			?>