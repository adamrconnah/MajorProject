 <?php 
echo "<?xml version=\"1.0\"?>
<rdf:RDF 
xmlns:obo=\"http://obofoundry.org/obo/\" 
xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
xmlns:phenomenet=\"http://phenomebrowser.org/phenomenet/\">" . "\n";

$genotype = fopen("Genotype.sql", "r"); //opens file Genotype.sql
$i=0;
while (!feof($genotype) ) { //feof = while not end of file

	$genoRow[] = fgets($genotype);  //fgets gets line
	$genoParts = explode("\t", $genoRow[$i]); //explode using tab delimiter to get 3 strings.
	$genoParts[0] = str_replace(':', '_', $genoParts[0]);
	$genoParts[0] = trim($genoParts[0]);
	$genoParts[1] = trim($genoParts[1]);

	echo "<phenomenet:Genotype rdf:about=\"http://phenomebrowser.org/phenomenet/" .$genoParts[0]. "\" >". "\n";
	echo "<phenomenet:has_url>http://omim.org/entry/".$genoParts[0]. "</phenomenet:has_url>". "\n";
	echo "<phenomenet:has_name>". $genoParts[1]."</phenomenet:has_name>". "\n";	
	echo "</phenomenet:Genotype>";	//print $genoParts[0]. "<br>" . $genoParts[1]. "<br>" . $genoParts[2] . "<br>"; //prints out the 3 parts
	$i++;
	}
	//if ($genoRow[$i] !== ""){ 
	//	echo "genoRow line " . $i. ", genoPart 0: " .$genoParts[0] . "<br />";
	
	//}
	//open next file.
	$phenotype = fopen("Phenotype.sql", "r"); 			//opens file Phenotype.sql
			$k = 0;
			while (!feof($phenotype) ){
			$phenoRow[] = fgets($phenotype);  							//fgets gets line
			$phenoParts = explode("\t", $phenoRow[$k]); 				//explode using tab delimiter to get 2 strings.
			$phenoParts = str_replace(':', '_', $phenoParts);
			$phenoParts[0] = trim($phenoParts[0]);
			$phenoParts[1] = trim($phenoParts[1]);

			//if ($genoParts[0] == $phenoParts[0]){ 						//look for $genoParts[0] and compare it to $phenoParts[0]
			//echo $genoParts[0]. " has " . $phenoParts[1]. "<br />" ; 	//prints out the 3 parts
				echo "<phenomenet:Genotype rdf:about=\"http://phenomebrowser.org/phenomenet/" .$phenoParts[0]. "\" >". "\n";
				echo "<phenomenet:has_phenotype rdf:resource=\"http://obofoundry.org/obo/".$phenoParts[1]."\" />". "\n";
			$k++;
				//}
			
			//fclose($phenotype);	
			}
	$inferred = fopen("InferredPhenotype.sql", "r"); 			//opens file InferredPhenotype.sql
			$j = 0;
			while (!feof($inferred) ){
			$inferredRow[] = fgets($inferred);  							//fgets gets line
			$inferredParts = explode("\t", $inferredRow[$j]); 				//explode using tab delimiter to get 2 strings.
			$inferredParts = str_replace(':', '_', $inferredParts);
			$inferredParts[0] = trim($inferredParts[0]);
			$inferredParts[1] = trim($inferredParts[1]);
			//if ($genoParts[0] == $inferredParts[0]){ 						//look for $genoParts[0] and compare it to $phenoParts[0]
				//echo $genoParts[0]. " has " . $inferredParts[1]. "<br />" ; 	//prints out the 3 parts
				echo "<phenomenet:Genotype rdf:about=\"http://phenomebrowser.org/phenomenet/" .$inferredParts[0]. "\" >". "\n";
				echo "<phenomenet:has_inferred_phenotype rdf:resource=\"http://obofoundry.org/obo/". $inferredParts[1]. "\" />". "\n";
				//}
				//if genoPart[0] is equal to inferredPart[0], print stuff
				//open ontologyTerms.sql
				//if inferred part[1], is equal to ontologyPart[0] print stuff 
			$j++;
		
			}
			
	$edge = fopen("Edge.sql", "r"); 			//opens file InferredPhenotype.sql
			$n= 0;
			while (!feof($edge) ){
			$edgeRow[] = fgets($edge);  							//fgets gets line
			$edgeParts = explode("\t", $edgeRow[$n]); 				//explode using tab delimiter to get 2 strings.
			$edgeParts = str_replace(':', '_', $edgeParts);
			$edgeParts[0] = trim($edgeParts[0]);
			$edgeParts[1] = trim($edgeParts[1]);
			$edgeParts[2] = trim($edgeParts[2]);
			
			echo"<phenomenet:has_edge>";
			
			//if ($genoParts[0] == $edgeParts[0]){ 						//look for $genoParts[0] and compare it to $edgeParts[0] //$edgeparts is 3 strings
				//echo $genoParts[0]. " similar to " . $edgeParts[1]. " by " . $edgeParts[2] . "<br />" ; 	//prints out the 3 parts
				//echo "<phenomenet:Edge rdf:about=\"http://phenomebrowser.org/phenomenet/".$edgeParts[0]."_".$edgeParts[1] ."\">";  //need to
			echo "<phenomenet:Edge rdf:about=\"http://phenomebrowser.org/phenomenet/".$edgeParts[0]."_".$edgeParts[1] ."\">". "\n";
			echo "<phenomenet:has_node rdf:resource=\"http://phenomebrowser.org/phenomenet/".$edgeParts[0]."\" />". "\n";
			echo "<phenomenet:has_node rdf:resource=\"http://phenomebrowser.org/phenomenet/".$edgeParts[1]."\" />". "\n";
			echo "<phenomenet:has_value>".$edgeParts[2]."</phenomenet:has_value>". "\n";
			echo "</phenomenet:Edge>". "\n";
			//}
				$n++;
			
			echo "</phenomenet:has_edge>". "\n";
			
			}

			
	
	
	$ontology = fopen("OntologyTerms.sql", "r"); 			//opens file InferredPhenotype.sql
			$t = 0;
			while (!feof($ontology) ){
			$ontologyRow[] = fgets($ontology);  							//fgets gets line
			$ontologyParts = explode("\t", $ontologyRow[$t]); 				//explode using tab delimiter to get 2 strings.
			$ontologyParts = str_replace(':', '_', $ontologyParts);
			 	
			echo "<phenomenet:OntologyTerm rdf:about=\"http://obofoundry.org/obo/".$ontologyParts[0]."\">";
			echo "<phenomenet:has_url>\"http://obofoundry.org/obo/"	.$ontologyParts[0]. "\"</phenomenet:has_url>". "\n";
			echo "<phenomenet:has_name>". $ontologyParts[1]."</phenomenet:has_name>". "\n";				
			//echo $ontologyParts[0]. "name is ".$ontologyParts[1]."\n";
					$t++;
					echo "</phenomenet:OntologyTerm>";	
				}



				
			echo "</rdf:RDF>";
/*
While the file still has rows to search.
if the genoParts[0] is equal to phenoParts[0] - print statement.
then goto next line of phenotype.sql and compare if genoparts[0] is still equal to phenoparts[0]
when it gets to a line of phenotype.sql where the genoparts[0] is not equal to phenoparts[0],
 move onto next row or genotype.sql
 */
	//echo $genoRow[1];

fclose($genotype);	
