<?php 
echo "<html><body>\n\n";
$genotype = fopen("Genotype.sql", "r");
$c=1;
  while (($row = fgetcsv($genotype, 1024)) !== false) {
		//$count = count($row);
		//list($id[], $description[], $url[]) = $row;
		
		
		//print_r($row);
		
        $arr = explode("\t", $row);
		
		print $arr[0] . $arr[1]. "<br>";
		//list($names[], $addresses[], $statuses[]) = $row;
		//print $row[$i];
		//$i++;
		
		
		//print $parts[0] . $parts[1]. "<BR>";
		/*foreach ($row as $value){
				echo $value ."<br>  "; //loops round itself.

				}*/
		//for every entry in column 1, print out col 1, col 2, and col 3 from same row.
}
fclose($genotype);
echo "\n</body></html>";
