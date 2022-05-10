<?php 	

include_once('../metier/MetierSystem.php');

$metier=new MetierSystem();
$resultat=phpversion();



$resultat=$metier->nouveauService("Service test","2022-02-20");





print_r($resultat);


 ?>