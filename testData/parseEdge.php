<?php

$fileNum=1;
$read=0;
$i=0;
$edge = fopen("Edge.sql", "r"); 			//opens file InferredPhenotype.sql
	$n= 0;
	while (!feof($edge) ){

	clearstatcache();

	$filename = "Edge".$fileNum .".rdf"; 


	$edgetypeout = fopen($filename, "a");


      if (filesize($filename) > 1000000000) {                                                                                                                                  
	  fclose($edgetypeout);
	  $edgetypeout = fopen("Edge".$fileNum .".rdf", "a");
	  $read = 0;
	  $fileNum++;
		}
								
								
			$edgeRow[] = fgets($edge);  							//fgets gets line
			$edgeParts = explode("\t", $edgeRow[$n]); 				//explode using tab delimiter to get 2 strings.
			$edgeParts = str_replace(':', '_', $edgeParts);
			$edgeParts[0] = trim($edgeParts[0], "\r\n");
			$edgeParts[0] = htmlentities($edgeParts[0]);
			$edgeParts[1] = trim($edgeParts[1], "\r\n");
			$edgeParts[1] = htmlentities($edgeParts[1]);
			$edgeParts[2] = trim($edgeParts[2], "\r\n");
			$edgeParts[2] = htmlentities($edgeParts[2]);
			 
			fwrite($edgetypeout,  "<phenomenet:Genotype rdf:about=\"http://phenomebrowser.org/phenomenet/" .$edgeParts[0]. "\" >". "\n");
			fwrite($edgetypeout, "<phenomenet:has_edge>");
			fwrite($edgetypeout, "<phenomenet:Edge rdf:about=\"http://phenomebrowser.org/phenomenet/".$edgeParts[0]."_".$edgeParts[1] ."\">". "\n");
			fwrite($edgetypeout, "<phenomenet:has_node rdf:resource=\"http://phenomebrowser.org/phenomenet/".$edgeParts[0]."\" />". "\n");
			fwrite($edgetypeout, "<phenomenet:has_node rdf:resource=\"http://phenomebrowser.org/phenomenet/".$edgeParts[1]."\" />". "\n");
			fwrite($edgetypeout, "<phenomenet:has_value>".$edgeParts[2]."</phenomenet:has_value>". "\n");
			fwrite($edgetypeout, "</phenomenet:Edge>". "\n");
			fwrite($edgetypeout, "</phenomenet:has_edge>". "\n");
			fwrite($edgetypeout, "</phenomenet:Genotype>");


		    fclose($edgetypeout);
					$n++;	
					unset($edgeParts);
	}
			fclose($edge);	
			
		

?>
