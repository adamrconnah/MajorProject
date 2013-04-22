<?php

$i=0;
$j=0;
$count=0;
if (isset($_GET['pheno'])){ 
$id = $_GET['pheno'];
$id = str_replace(':', "", $id); 
	}
if (isset($_POST['pheno'])){ 
$id = $_POST['pheno'];
$id = str_replace(':', "", $id); 	

}
else $id="MP0000001";

			$obo = file_get_contents('mammalian_phenotype.obo', true);
			// split each MP by  [Term]
			$terms2 = explode('[Term]', $obo);
			echo " <ul class=tree>";
			echo " <div class=$id>";
			

			foreach ($terms2 as $term2){
			$termRow2 = explode("\n", $term2);					
																					//$termRow is $term split into lines
				foreach ($termRow2 as $value3){										//for every termRow 
					if (preg_match('/^is_a:(.*)/', $value3, $m)) { 					//find the row with "is_a:" at start
						$n = explode("!", $m[0]); 									//explode matching row using the "!" seperator
						$is_a = $n[0];
						$is_a = str_replace('is_a: ', "", $is_a); 					//strip unneccesery stuff from string to leave mp:00012 etc
						$is_a = str_replace(':', "", $is_a); 						//strip unneccesery stuff from string to leave mp:00012 etc

						$is_a = trim ($is_a);
						
						if($id ==$is_a) {
						$termRow2[1] = str_replace('id: ', "", $termRow2[1]);
						$termRow2[1] = str_replace(':', "", $termRow2[1]);

						$termRow2[2] = str_replace('name: ', "", $termRow2[2]); 
						$var1=$termRow2[1];
						
						echo "<li><div class=\"pheno\" id='$termRow2[1]'><a href=\"#!\">".$termRow2[2]."</a><span><a href='phenotypes.php?searchQuery=$termRow2[1]'>   <img src=\"mag.png\" ></a></span></div></li>";
						$count++;		//adds 1 to the count if if finds a child element																			
						}
						}
						
					}
						
				}
				if ($count==0) //if count is zero, it is end of branch
					echo "<li><div class=\"pheno\">End of branch</div></li>";

								echo "</div>";
								echo "</ul>";
	
?>
