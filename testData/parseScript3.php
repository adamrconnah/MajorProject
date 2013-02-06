 <?php 
echo "<html><body>\n\n";

$genotype = fopen("Genotype.sql", "rb"); //opens file Genotype.sql
$i=0;
while (!feof($genotype) ) { //feof = while not end of file

	$genoRow[] = fgets($genotype);  //fgets gets line
	$genoParts = explode("\t", $genoRow[$i]); //explode using tab delimiter to get 3 strings.
	//print $genoParts[0]. "<br>" . $genoParts[1]. "<br>" . $genoParts[2] . "<br>"; //prints out the 3 parts
	
	//if ($genoRow[$i] !== ""){ 
//	echo "genoRow line " . $i. ", genoPart 0: " .$genoParts[0] . "<br />";
	$i++;
	//}
//open next file.
	$phenotype = fopen("Phenotype.sql", "rb"); 			//opens file Phenotype.sql
			$k = 0;
			while (!feof($phenotype) ){
			$phenoRow[] = fgets($phenotype);  							//fgets gets line
			$phenoParts = explode("\t", $phenoRow[$k]); 				//explode using tab delimiter to get 2 strings.
			if ($genoParts[0] == $phenoParts[0]){ 						//look for $genoParts[0] and compare it to $phenoParts[0]
				echo $genoParts[0]. " has " . $phenoParts[1]. "<br />" ; 	//prints out the 3 parts
				//fseek($phenotype,$i);
				//$i++;
				}
				$k++;
			
			}//fclose($phenotype);
			
/*
While the file still has rows to search.
if the genoParts[0] is equal to phenoParts[0] - print statement.
then goto next line of phenotype.sql and compare if genoparts[0] is still equal to phenoparts[0]
when it gets to a line of phenotype.sql where the genoparts[0] is not equal to phenoparts[0],
 move onto next row or genotype.sql
 */
}	//echo $genoRow[1];

fclose($genotype);	
