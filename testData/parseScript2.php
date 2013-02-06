 <?php 
echo "<html><body>\n\n";

$genotype = fopen("Genotype.sql", "rb");

while (!feof($genotype) ) { //feof = while not end of file

$row = fgets($genotype);  //fgets gets line
$parts = explode("\t", $row); //explode using tab delimiter to get 3 strings.

print $parts[0]. "<br>" . $parts[1]. "<br>" . $parts[2] . "<br>"; //prints out the 3 parts

}

fclose($genotype);