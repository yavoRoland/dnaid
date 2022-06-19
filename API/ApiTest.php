<?php 	

include_once('../metier/MetierSystem.php');

$metier=new MetierSystem();
$resultat=phpversion();



//$resultat=$metier->nouveauService("Service test","2022-02-20");
//$resultat=$metier->rechercherJusteParFulltext("Yavo",0);

$resultat=$metier->modifierJusteFullText(15);
//$resultat=$metier->modifierJusteFullTextParAssemblee(1);
//$resultat=$metier->modifierJusteFullTextParGroupe(1);





print_r($resultat);


 ?>