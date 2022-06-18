<?php 

	const BDD='mysql:dbname=dnaid;host=127.0.0.1:3306';
	const USER='root';
	const MDP='';

	//constante de gestion des etats "appartenir" et "rattacher"
	const EJECTE=-1;
	const SUSPENDU=0;
	const ACTIF=1;

	//constante etat "juste"
	const MORT_SPIRITUEL=-1;
	const DECEDE=0;
	const VIVANT=1;

	//constante quantité listing
	const SMALL_QTE=5;
	const STANDARD_QTE=10;
	const HUGE_QTE=15;

	//code de retour des requête http
	const acces_refuse=-1;
	const requete_echoue=0;
	const requete_reussi=1;
	const liste_vide=2;

	//repertoire des ressources
	const REPERTOIRE_PHOTO_JUSTE='../dao/illustration/juste/photo';
	const REPERTOIRE_SYS_LOG='../dao/logs/systeme';

	const FULL_TEXT_ERROR="Full Text Error";

	const WIN_UPDATE_JUSTE_FULL_TEXT_SCRIPT='../assets/scripts/windows/justeFullTextUpadte.bat';
	const UNIX_UPDATE_JUSTE_FULL_TEXT_SCRIPT='../assets/scripts/unix/justeFullTextUpadte.sh';



	//niveau juste (Autorisation dans l'application)
	const JUSTE_LAMBDA=0;
	const JUSTE_ADMIN=1;
	const JUSTE_SUPER_ADMIN=2;



	//fonction juste rattacher
	const MEMBRE="membre";
	const RESPONSABLE="responsable";
	const LEADER="leader";
	const INSPECTEUR='inspecteur';
	const PASTEUR="pasteur";




 ?>