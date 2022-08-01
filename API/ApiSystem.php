<?php 
	include_once('../metier/MetierSystem.php');
	

	$resultat =array();
	$metier =new MetierSystem();
	$jwtManager = new JwtManager();
	$GLOBALS["metier"]=$metier;
	$GLOBALS['jwtManager'] = $jwtManager;



	function reponse($resultat=array('code'=> -8,'message'=>"Requête REST echouée; Accès refusé. Authentification requise."),$repertoireLog=REPERTOIRE_SYS_LOG){
		$jwtManager=$GLOBALS["jwtManager"];
		$metier=$GLOBALS["metier"];
		$message=$resultat["message"];

		if($resultat["code"]>0){
			$message .= " user : ".$jwtManager->getJwtEncodedPayload($jwtManager->getBearerToken());//il faudra decoder le payload dans le log pour savoir qui a executer la requete
			$resultat["jwt"]= $metier->increaseJwtTime();
		}

		Utilitaire::log($message,REPERTOIRE_SYS_LOG);
		echo json_encode($resultat,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}



	




	if(isset($_POST["code"])){
		$check=$jwtManager->checkSession();

		if($_POST["code"]=="0"){
			$resultat=$metier->ajouterJuste($_POST["nom"], $_POST["prenom"], $_POST["surnom"], $_POST["datenaiss"], $_POST["genre"], $_POST["etat"], $_POST["adresse"], $_POST["phone"], $_POST["grade"], $_POST["nvelNais"], $_POST["profession"], $_POST["statutMatri"], $_POST["ethnie"], null);

			echo json_encode($resultat,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}elseif($_POST["code"]=="1"){
			$resultat=$metier->modifierNiveauJuste($_POST["niveau"], $_POST["juste"]);
			echo json_encode($resultat,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}elseif($_POST["code"]=="J1-5"){
			//connexion à un compte admnistrateur à partir du login et du mot de passe
			$resultat=$metier->connexionParLoginJuste($_POST["login"], $_POST["mdp"]);
						
			$message=$resultat["message"];
			$message .=array_key_exists("donnee",$resultat)?" user : ".$jwtManager->getJwtEncodedPayload($resultat["donnee"]['jwt']):"";
			Utilitaire::log($message,REPERTOIRE_SYS_LOG);
			echo json_encode($resultat,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}else{
			if(!$check["checked"]){
				reponse($check);
			}else{
				switch($_POST['code']){

					//GESTION APPARTENIR CODE A1-...
					case "A1-1":
					//Adhesion d'un juste à un groupe
						$resultat=$metier->ajouterAppartenir($_POST["juste"],$_POST["groupe"],$_POST["role"],$_POST["statut"],$_POST["dateDebut"]);
						reponse($resultat);
					break;

					case "A1-2":
					//Modification des informations concernant l'adhésion d'un juste à un groupe donné
						$resultat=$metier->modifierAppartenir($_POST["juste"],$_POST["groupe"],$_POST["role"],$_POST["statut"],$_POST["dateDebut"],$_POST["id"]);
						reponse($resultat);
					break;

					case "A1-3":
					//Retrait d'un juste d'un groupe donné
						$resultat=$metier->termierAppartenir($_POST["dateFin"], $_POST["description"], $_POST["id"]);
						reponse($resultat);
					break;









					//GESTION ASSEMBLEE CODE A2-...
					case "A2-1":
					//Enregistrement ou création d'une assemblée de justes
						$resultat=$metier->ajouterAssemblee($_POST["nom"], $_POST["pays"], $_POST["region"], $_POST["departement"], $_POST["ville"], $_POST["commune"], $_POST["quartier"], $_POST['description']);
						reponse($resultat);
					break;

					case "A2-2":
					//Modification des informations d'une assemblée de justes
						$resultat=$metier->modifierAssemblee($_POST["nom"], $_POST["pays"], $_POST["region"], $_POST["departement"], $_POST["ville"], $_POST["commune"], $_POST["quartier"], $_POST["description"] ,$_POST["assemblee"]);
						reponse($resultat);
					break;

					case "A2-3":
					//Recherche des informations d'une assemblée de juste à partir de son identifiant ("idassemblee")
						$resultat=$metier->rechercherAssemblee($_POST["assemblee"]);
						reponse($resultat);
					break;

					case "A2-4":
					//Recherche des informations d'une assemblée de juste à partir de son matricule ("matassemblee")
						$resultat=$metier->rechercherAssembleeParMatricule($_POST["matricule"]);
						reponse($resultat);
					break;

					case "A2-5":
					//Recherche des assemblées de justes à partir de mots clés 
						$resultat=$metier->rechercherAssembleeParFullText($_POST["text"], $_POST["page"]);
						reponse($resultat);
					break;

					case "A2-6":
					//Liste paginée des assemblées de justes
						$resultat=$metier->listeAssemblee($_POST["page"]);
						reponse($resultat);
					break;

					case "A2-7":
					//Liste paginée des justes d'une assemblée
						$resultat=$metier->listeJusteAssemble($_POST["assemblee"], $_POST["page"]);
						reponse($resultat);
					break;

					case "A2-8":
					//Modification des champs fulltext des justes d'une assemblée
						$resultat=$metier->modifierJusteFullTextParAssemblee($_POST["id"]);
						reponse($resultat);
					break;







					//GESTION GROUPE CODE G1-...
					case "G1-1":
					//Enregistrement ou création d'un groupe de juste (groupe de music, de théatre, nettoyage ...)
					//On demande le matricule du service pas son id
						$resultat=$metier->ajouterGroupe($_POST["nom"], $_POST["datecreat"], $_POST["description"], $_POST["service"]);
						reponse($resultat);
					break;

					case "G1-2":
					//Modification des informations d'un groupe de juste
						$resultat=$metier->modifierGroupe($_POST["nom"], $_POST["datecreat"], $_POST["description"], $_POST["service"], $_POST["groupe"]);
						reponse($resultat);
					break;

					case "G1-3":
					//Recherche des informations d'un groupe à partir de son identifiant ("idgroupe")
						$resultat=$metier->rechercherGroupe($_POST["groupe"]);
						reponse($resultat);
					break;

					case "G1-4":

					//Recherche des informations d'un groupe à partir de son matricule ("matgroupe")
						$resultat=$metier->rechercherGroupeParMatricule($_POST["matricule"]);
						reponse($resultat);
					break;

					case "G1-5":
					//Liste paginée des groupes
						$resultat=$metier->listeGroupe($_POST["page"]);
						reponse($resultat);
					break;

					case "G1-6":
					//Liste paginée des justes qui constitue un groupe donné
						$resultat=$metier->listeJusteGroupe($_POST["groupe"], $_POST["page"]);
						reponse($resultat);
					break;

					case "G1-7":
					//Modification des champs fulltext des juste par groupe
						$resultat=$metier->modifierJusteFullTextParGroupe($_POST["id"]);
						reponse($resultat);
					break;









					//GESTION JUSTE CODE J1-...
					case "J1-1":
					//Enregistrement des informations d'un nouveau juste
						$photo=null;

						if(isset($_FILES['photo'])){
							$photo=array(
								'donnee'=>$_FILES['photo'],
								'ext'=>$_POST['ext']
							);
						}

						$resultat=$metier->ajouterJuste($_POST["nom"], $_POST["prenom"], $_POST["surnom"], $_POST["datenaiss"], $_POST["genre"], $_POST["etat"], $_POST["adresse"], $_POST["phone"], $_POST["grade"], $_POST["nvelNais"], $_POST["profession"], $_POST["statutMatri"], $_POST["ethnie"], $photo, $_POST["assemblee"], $_POST["dateRattache"], $_POST["fonction"]);
						//PS certaines informations sont destinées à renseigner la table rattacher car à la creation le juste est systematiquement rattaché à une base.

						reponse($resultat);
					break;
						
					case "J1-2":
					//Modification des informations d'un juste
						$resultat=$metier->modifierJuste($_POST["nom"], $_POST["prenom"], $_POST["surnom"], $_POST["datenaiss"], $_POST["genre"], $_POST["etat"], $_POST["adresse"], $_POST["phone"], $_POST["grade"], $_POST["nvelNais"], $_POST["profession"], $_POST["statutMatri"], $_POST["ethnie"], $_POST["juste"]);
						reponse($resultat);
					break;

					case "J1-3":
					//Modification de la photo d'un juste
						$photo=null;
						if(isset($_FILES["photo"])){
							$photo=array(
								"donnee"=>$_FILES["photo"],
								'extension'=>$_POST["ext"]
							);
						}
						$resultat=$metier->modifierPhotoJuste($photo, $_POST["juste"]);
						reponse($resultat);
					break;

					case "J1-4":
					//Transformation d'un compte de juste en compte "Lambda" , "Administrateur" ou "Super Administrateur"
						$resultat=$metier->modifierNiveauJuste($_POST["niveau"], $_POST["juste"]);
						reponse($resultat);
					break;

					//case J1-5: connexion par login

					case "J1-6":
					//Recherche des informations d'un juste à partir de son identifiant ("idjuste")
						$resultat=$metier->rechercherJuste($_POST["juste"]);
						reponse($resultat);
					break;
					case "J1-7":
					//Recherche profonde des informations d'un juste à partir de son identifiant ("idjuste")
						$resultat=$metier->rechercheProfondeJuste($_POST["juste"]);
						reponse($resultat);
					break;
					case "J1-8":

					//Determination de l'assemblée à laquelle appartient un juste
						$resultat=$metier->assembleeJuste($_POST["juste"]);
						reponse($resultat);
					break;

					case "J1-9":

					//Liste paginée des justes
						$resultat=$metier->listeJuste($_POST["page"]);
						reponse($resultat);
					break;

					case "J1-10":
					//Liste des assemblées auxquelles un juste a appartenues
						$resultat=$metier->ListeAssembleesJuste($_POST["juste"], $_POST["page"]);
						reponse($resultat);
					break;

					case "J1-11":
					//Liste des groupes auquels appartient le juste
						$resultat=$metier->ListeGroupeJuste($_POST['juste'],$_POST['page']);
						reponse($resultat);
					break;

					case "J1-12":
					//Recherche des informations d'un juste à partir de mots clés ("fulltext")
						$resultat=$metier->rechercherJusteParFulltext($_POST["text"],$_POST["page"]);
						reponse($resultat);
					break;

					case "J1-13":
					//Modifier le champ fulltext du juste
						$resultat=$metier->modifierJusteFullText($_POST["id"]);
						reponse($resultat);
					break;








					//GESTION RATTACHER CODE R1-...
					case "R1-1":
					//Affiliation d'un juste à une assemblée de juste donnée
						$resultat=$metier->ajouterRattacher($_POST["juste"], $_POST["assemblee"], $_POST["fonction"], $_POST["statut"], $_POST["dateDebut"]);
						reponse($resultat);
					break;

					case "R1-2":
					//Modification de l'affiliation d'un juste à une assemblée données
						$resultat=$metier->modifierRattacher($_POST["juste"], $_POST["assemblee"], $_POST["fonction"], $_POST["statut"], $_POST["id"]);
						reponse($resultat);
					break;

					case "R1-3":
					//Départ d'un juste d'une assemblée pour une autre (ou décès ou excomuniation)
						$resultat=$metier->terminerRattacher($_POST["dateFin"], $_POST["description"], $_POST["id"]);
						reponse($resultat);
					break;






					//GESTION SERVICE CODE S1-...
					case "S1-1":
					//Enregistrement d'un service (service des chantres ou nettoyage ...)
						$resultat=$metier->nouveauService($_POST["nom"],$_POST["datecreat"]);
						reponse($resultat);
					break;

					case "S1-2":
					//Modification des informations d'un service
						$resultat=$metier->modifierService($_POST["nom"],$_POST["datecreat"],$_POST["service"]);
						reponse($resultat);
					break;

					case "S1-3":
					//Recherche des informations d'un service donné à partir de son identifiant ("idservice")
						$resultat=$metier->rechercherService($_POST["service"]);
						reponse($resultat);
					break;

					case "S1-4":
					//Recherche des informations d'un service donné à partir de son matricule ("matservice")
						$resultat=$metier->rechercherServiceParMatricule($_POST["matricule"]);
						reponse($resultat);
					break;

					case "S1-5":
					//Liste paginée des services
						$resultat=$metier->listeService($_POST["page"]);
						reponse($resultat);
					break;

					case "S1-6":
					//Liste paginée des groupes associés à un service
						$resultat=$metier->listeGroupeService($_POST["service"], $_POST["page"]);
						reponse($resultat);
					break;

					default:
						reponse(array('code'=> -9, "message"=>"Requête REST echouée; code inconnu."), REPERTOIRE_SYS_LOG);	
				}
			}
		}
	}else{
		reponse(array("code"=> -10,"message"=>"Requête REST echouée; Requête mal formée."), REPERTOIRE_SYS_LOG);
	}


/*
	TEMPLATE SWITCH CASE
					$resultat=$metier->
					reponse($resultat)
*/

 ?>