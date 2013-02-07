<?php 

$ontology = fopen("OntologyTerms.sql", "rb"); 			//opens file InferredPhenotype.sql
			$t = 0;
			while (!feof($ontology) ){
			$ontologyRow[] = fgets($ontology);  							//fgets gets line
			$ontologyParts = explode("\t", $ontologyRow[$t]); 				//explode using tab delimiter to get 2 strings.
			$ontologyParts = str_replace(':', '_', $ontologyParts);
			 	
			echo $ontologyParts[0]. "name is ".$ontologyParts[1]."\n";
					$t++;
				}
				
?>