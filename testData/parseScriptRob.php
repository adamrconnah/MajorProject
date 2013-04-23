<?php 
echo "<?xml version=\"1.0\"?>
<rdf:RDF 
xmlns:obo=\"http://obofoundry.org/obo/\" 
xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
xmlns:phenomenet=\"http://phenomebrowser.org/phenomenet/\">" . "\n";

$genotype = fopen("Genotype.sql", "r"); //opens file Genotype.sql
$genotypeout = fopen("Genotype.rdf", "w");
$fileNum=1;
$i=0;
while (!feof($genotype) ) { //feof = while not end of file

	$genoRow[] = fgets($genotype);  //fgets gets line
	$genoParts = explode("\t", $genoRow[$i]); //explode using tab delimiter to get 3 strings.
	$genoParts[0] = str_replace(':', '_', $genoParts[0]);
	$genoParts[0] = trim($genoParts[0], "\r\n");
	$genoParts[1] = trim($genoParts[1], "\r\n");

  $genoParts[0] =  htmlentities($genoParts[0]);
  $genoParts[1] =  htmlentities($genoParts[1]);

	fwrite($genotypeout, "<phenomenet:Genotype rdf:about=\"http://phenomebrowser.org/phenomenet/" .$genoParts[0]. "\" >". "\n");
	fwrite($genotypeout, "<phenomenet:has_url>http://omim.org/entry/".$genoParts[0]. "</phenomenet:has_url>". "\n");
	fwrite($genotypeout, "<phenomenet:has_name>". $genoParts[1]."</phenomenet:has_name>". "\n");
	fwrite($genotypeout,  "</phenomenet:Genotype>");	 //prints out the 3 parts
	$i++;
	 unset($genoParts);
	}
	fclose($genotype);	
	fclose($genotypeout);	

	
	$phenotype = fopen("Phenotype.sql", "r"); 			//opens file Phenotype.sql
	$phenotypeout = fopen("Phenotype.rdf", "w"); 			//opens file Phenotype.sql
			$k = 0;
			while (!feof($phenotype) ){
			$phenoRow[] = fgets($phenotype);  							//fgets gets line
			$phenoParts = explode("\t", $phenoRow[$k]); 				//explode using tab delimiter to get 2 strings.
			$phenoParts = str_replace(':', '_', $phenoParts);
			$phenoParts[0] = trim($phenoParts[0], "\r\n");
			$phenoParts[1] = trim($phenoParts[1], "\r\n");
			fwrite($phenotypeout, "<phenomenet:Genotype rdf:about=\"http://phenomebrowser.org/phenomenet/" .$phenoParts[0]. "\" >". "\n");
			fwrite($phenotypeout, "<phenomenet:has_phenotype rdf:resource=\"http://obofoundry.org/obo/".$phenoParts[1]."\" />". "\n");
			$k++;
			fwrite($phenotypeout, "</phenomenet:Genotype>");
				 unset($phenoParts);

			}
				fclose($phenotype);	
				fclose($phenotypeout);	

			
	$inferred = fopen("InferredPhenotype.sql", "r"); 			//opens file InferredPhenotype.sql
	$inferredtypeout = fopen("inferredPhenotype.rdf", "w"); 			//opens file Phenotype.sql

			$j = 0;
			while (!feof($inferred) ){
			$inferredRow[] = fgets($inferred);  							//fgets gets line
			$inferredParts = explode("\t", $inferredRow[$j]); 				//explode using tab delimiter to get 2 strings.
			$inferredParts = str_replace(':', '_', $inferredParts);
			$inferredParts[0] = trim($inferredParts[0], "\r\n");
			$inferredParts[1] = trim($inferredParts[1], "\r\n");
			
			fwrite($inferredtypeout, "<phenomenet:Genotype rdf:about=\"http://phenomebrowser.org/phenomenet/" .$inferredParts[0]. "\" >". "\n");
			fwrite($inferredtypeout, "<phenomenet:has_inferred_phenotype rdf:resource=\"http://obofoundry.org/obo/". $inferredParts[1]. "\" />". "\n");
			fwrite($inferredtypeout, "</phenomenet:Genotype>");
				$j++;
			 unset($inferredParts);

			}
			fclose($inferred);	
			fclose($inferredtypeout);	

				
	$ontology = fopen("OntologyTerms.sql", "r"); 			//opens file InferredPhenotype.sql
	$ontologytypeout = fopen("OntologyTerms.rdf", "w"); 			//opens file Phenotype.sql

			$t = 0;
			while (!feof($ontology) ){
			$ontologyRow[] = fgets($ontology);  							//fgets gets line
			$ontologyParts = explode("\t", $ontologyRow[$t]); 				//explode using tab delimiter to get 2 strings.
			$ontologyParts = str_replace(':', '_', $ontologyParts);
                        $ontologyParts[0] = trim($ontologyParts[0], "\r\n");
                         $ontologyParts[0] = htmlentities($ontologyParts[0]);
 			$ontologyParts[1] = trim($ontologyParts[1], " ");
 			$ontologyParts[1] = trim($ontologyParts[1], "\r\n");
			 $ontologyParts[1] = htmlentities($ontologyParts[1]);
	
			fwrite($ontologytypeout, "<phenomenet:OntologyTerm rdf:about=\"http://obofoundry.org/obo/".$ontologyParts[0]."\">");
			fwrite($ontologytypeout, "<phenomenet:has_name>". $ontologyParts[1]."</phenomenet:has_name>". "\n");				
					$t++;
			fwrite($ontologytypeout, "</phenomenet:OntologyTerm>");	
			unset($ontologyParts);
		
				}
			fclose($ontology);	
			fclose($ontologytypeout);



				
			echo "</rdf:RDF>";

