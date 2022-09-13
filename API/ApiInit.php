<?php 	

include_once('../metier/MetierSystem.php');

$metier=new MetierSystem();
$resultat=phpversion();
$resultat=$metier->ajouterAssemblee("Base de niangon nord", "Côte d'ivoire","Lagunes","Abidjan","Abidjan","Yopougon","Niangon nord", "Église mère");
$resultat=$metier->listeAssemblee(0);
$resultat=$metier->ajouterJuste("Super","Administrateur","Admin",date('Y-m-d'),"masculin homme","vivant","Abidjan yopougon niangon nord","0759169534","Laïc",date('Y'),"Informaticien","Celibataire","Ivoirien",null,"Côte d'ivoire Abidjan Yopougon niangon ivoirien","",$resultat["donnee"][0]["matassemble"],date('Y-m-d'),"Membre");
$resultat=$metier->modifierNiveauJuste(2,1);
print_r($resultat);
$resultat=$metier->modifierMDPJuste($resultat["donnee"]["mdp"],"123456",1);

print_r($resultat);


 ?>