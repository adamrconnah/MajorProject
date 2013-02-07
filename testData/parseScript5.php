 <?php 

$genotype = fopen("Genotype.sql", "rb"); //opens file Genotype.sql
$i=0;
while (!feof($genotype) ) { //feof = while not end of genotype file

	$genoRow[] = fgets($genotype);  //fgets gets line
	$genoParts = explode("\t", $genoRow[$i]); //explode using tab delimiter to get 3 strings.
	$genoParts[0] = str_replace(':', '_', $genoParts[0]); //replace occurance of colon to underscore
	echo $genoParts[0]."\n";
	
	$i++;
	
			$inferred = fopen("InferredPhenotype.sql", "rb"); 			//opens file InferredPhenotype.sql
			$j = 0;														//set counter to zero
			while (!feof($inferred) ){									//feof = while not end of inferred file
				$inferredRow[] = fgets($inferred);  							//fgets gets line
				$inferredParts = explode("\t", $inferredRow[$j]); 				//explode using tab delimiter to get  strings.
				$inferredParts = str_replace(':', '_', $inferredParts);
			if ($genoParts[0] == $inferredParts[0]){ 						//look for $genoParts[0] and compare it to $inferredParts[0]
				//echo $genoParts[0]. " has " . $inferredParts[1]. "<br />" ; 	//prints out the 3 parts
				//echo "<phenomenet:has_inferred_phenotype rdf:resource=\"http://obofoundry.org/obo/". $inferredParts[1]. "\" />". "\n";
				echo $inferredParts[1]."\n";
				}
				//if genoPart[0] is equal to inferredPart[0], print stuff
				//open ontologyTerms.sql
				//if inferred part[1], is equal to ontologyPart[0] print stuff 
			$j++;
			
				$ontology = fopen("OntologyTerms.sql", "rb"); 			//opens file InferredPhenotype.sql
				$t = 0;													//set counter to zero
				while (!feof($ontology) ){								//feof = while not end of ontology file
					$ontologyRow[] = fgets($ontology);  							//fgets gets line
					$ontologyParts = explode("\t", $ontologyRow[$t]); 				//explode using tab delimiter to get  strings.
					$ontologyParts = str_replace(':', '_', $ontologyParts);
				if ($inferredParts[1] == $ontologyParts[0]){ 						
					echo $ontologyParts[1];
						}
						$t++;
					}
			}
			}
		
		?>