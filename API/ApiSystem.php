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
			$message .= " user : ".$jwtManager->getJwtPayload($jwtManager->getBearerToken());
			$resultat["jwt"]= $metier->increaseJwtTime();
		}

		Utilitaire::log($message,REPERTOIRE_SYS_LOG);
		echo json_encode($resultat,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}



	




	if(isset($_POST["code"])){
		switch($_POST['code']){
			/*case "0":
				$resultat=$metier->ajouterJuste($_POST["nom"], $_POST["prenom"], $_POST["surnom"], $_POST["datenaiss"], $_POST["genre"], $_POST["etat"], $_POST["adresse"], $_POST["phone"], $_POST["grade"], $_POST["nvelNais"], $_POST["profession"], $_POST["statutMatri"], $_POST["ethnie"], null);

				echo json_encode($resultat,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			break;

			case "1":
				$resultat=$metier->modifierNiveauJuste($_POST["niveau"], $_POST["juste"]);
				echo json_encode($resultat,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			break;*/

			//GESTION APPARTENIR CODE A1-...
			case "A1-1":

			//Adhesion d'un juste à un groupe donné

				if($jwtManager->checkSession()){
					$resultat=$metier->ajouterAppartenir($_POST["juste"],$_POST["groupe"],$_POST["role"],$_POST["statut"],$_POST["dateDebut"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "A1-2":

			//Modification des informations concernant l'adhésion d'un juste à un groupe donné

				if($jwtManager->checkSession()){
					$resultat=$metier->modifierAppartenir($_POST["juste"],$_POST["groupe"],$_POST["role"],$_POST["statut"],$_POST["dateDebut"],$_POST["id"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "A1-3":

			//Retrait d'un juste d'un groupe donné

				if($jwtManager->checkSession()){
					$resultat=$metier->termierAppartenir($_POST["dateFin"], $_POST["description"], $_POST["id"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;









			//GESTION ASSEMBLEE CODE A2-...
			case "A2-1":

			//Enregistrement ou création d'une assemblée de justes

				if($jwtManager->checkSession()){
					$resultat=$metier->ajouterAssemblee($_POST["nom"], $_POST["pays"], $_POST["region"], $_POST["departement"], $_POST["ville"], $_POST["commune"], $_POST["quartier"], $_POST['description']);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "A2-2":

			//Modification des informations d'une assemblée de justes

				if($jwtManager->checkSession()){
					$resultat=$metier->modifierAssemblee($_POST["nom"], $_POST["pays"], $_POST["region"], $_POST["departement"], $_POST["ville"], $_POST["commune"], $_POST["quartier"], $_POST["description"] ,$_POST["assemblee"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "A2-3":

			//Recherche des informations d'une assemblée de juste à partir de son identifiant ("idassemblee")

				if($jwtManager->checkSession()){
					$resultat=$metier->rechercherAssemblee($_POST["assemblee"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "A2-4":

			//Recherche des informations d'une assemblée de juste à partir de son matricule ("matassemblee")

				if($jwtManager->checkSession()){
					$resultat=$metier->rechercherAssembleeParMatricule($_POST["matricule"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "A2-5":

			//Recherche des assemblées de justes à partir de mots clés 

				if($jwtManager->checkSession()){
					$resultat=$metier->rechercherAssembleeParFullText($_POST["text"], $_POST["page"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "A2-6":

			//Liste paginée des assemblées de justes

				if($jwtManager->checkSession()){
					$resultat=$metier->listeAssemblee($_POST["page"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "A2-7":

			//Liste paginée des justes d'une assemblée

				if($jwtManager->checkSession()){
					$resultat=$metier->listeJusteAssemble($_POST["assemblee"], $_POST["page"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "A2-8":
			//Modification des champs fulltext des justes d'une assemblée
				if($jwtManager->checkSession()){
					$resultat=$metier->modifierJusteFullTextParAssemblee($_POST["id"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;







			//GESTION GROUPE CODE G1-...
			case "G1-1":

			//Enregistrement ou création d'un groupe de juste (groupe de music, de théatre, nettoyage ...)

				if($jwtManager->checkSession()){
					$resultat=$metier->ajouterGroupe($_POST["nom"], $_POST["dateCreat"], $_POST["service"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "G1-2":

			//Modification des informations d'un groupe de juste

				if($jwtManager->checkSession()){
					$resultat=$metier->modifierGroupe($_POST["nom"], $_POST["dateCreat"], $_POST["service"], $_POST["groupe"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "G1-3":

			//Recherche des informations d'un groupe à partir de son identifiant ("idgroupe")

				if($jwtManager->checkSession()){
					$resultat=$metier->rechercherGroupe($_POST["groupe"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "G1-4":

			//Recherche des informations d'un groupe à partir de son matricule ("matgroupe")

				if($jwtManager->checkSession()){
					$resultat=$metier->rechercherGroupeParMatricule($_POST["matricule"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "G1-5":

			//Liste paginée des groupes

				if($jwtManager->checkSession()){
					$resultat=$metier->listeGroupe($_POST["page"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "G1-6":

			//Liste paginée des justes qui constitue un groupe donné

				if($jwtManager->checkSession()){
					$resultat=$metier->listeJusteGroupe($_POST["groupe"], $_POST["page"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "G1-7":

			//Modification des champs fulltext des juste par groupe
				if($jwtManager->checkSession()){
					$resultat=$metier->modifierJusteFullTextParGroupe($_POST["id"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;









			//GESTION JUSTE CODE J1-...
			case "J1-1":

			//Enregistrement des informations d'un nouveau juste

				if($jwtManager->checkSession()){
					$photo=null;

					if(isset($_FILES['photo'])){
						$photo=array(
							'donnee'=>$_FILES['photo'],
							'ext'=>$_POST['ext']
						);
					}

					$resultat=$metier->ajouterJuste($_POST["nom"], $_POST["prenom"], $_POST["surnom"], $_POST["datenaiss"], $_POST["genre"], $_POST["etat"], $_POST["adresse"], $_POST["phone"], $_POST["grade"], $_POST["nvelNais"], $_POST["profession"], $_POST["statutMatri"], $_POST["ethnie"], $photo, $_POST["assemblee"], $_POST["dateRattache"], $_POST["fonction"]);

					reponse($resultat);

				}else{
					reponse();
				}
			break;
				
			case "j1-2":

			//Modification des informations d'un juste

				if($jwtManager->checkSession()){
					$resultat=$metier->modifierJuste($_POST["nom"], $_POST["prenom"], $_POST["surnom"], $_POST["datenaiss"], $_POST["genre"], $_POST["etat"], $_POST["adresse"], $_POST["phone"], $_POST["grade"], $_POST["nvelNais"], $_POST["profession"], $_POST["statutMatri"], $_POST["ethnie"], $id);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "j1-3":

			//Modification de la photo d'un juste

				if($jwtManager->checkSession()){
					$photo=null;
					if(isset($_FILES["photo"])){
						$photo=array(
							"donnee"=>$_FILES["photo"],
							'extension'=>$_POST["ext"]
						);
					}
					$resultat=$metier->modifierPhotoJuste($photo, $_POST["juste"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "j1-4":

			//Transformation d'un compte de juste en compte "Lambda" , "Administrateur" ou "Super Administrateur"

				if($jwtManager->checkSession()){
					$resultat=$metier->modifierNiveauJuste($_POST["niveau"], $_POST["juste"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "J1-5":
				$resultat=$metier->connexionParLoginJuste($_POST["login"], $_POST["mdp"]);
				$message=$resultat["message"]." user : ".$jwtManager->getJwtPayload($resultat["donnee"]['jwt']);
				Utilitaire::log($message,REPERTOIRE_SYS_LOG);
				echo json_encode($resultat,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
			break;

			case "J1-6":

			//Recherche des informations d'un juste à partir de son identifiant ("idjuste")

				if($jwtManager->checkSession()){
					$resultat=$metier->rechercherJuste($_POST["juste"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;
			case "J1-7":
				//Recherche profonde des informations d'un juste à partir de son identifiant ("idjuste")

				if($jwtManager->checkSession()){
					$resultat=$metier->rechercheProfondeJuste($_POST["juste"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;
			case "J1-8":

			//Determination de l'assemblée à laquelle appartient un juste

				if($jwtManager->checkSession()){
					$resultat=$metier->assembleeJuste($_POST["juste"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "J1-9":

			//Liste paginée des justes

				if($jwtManager->checkSession()){
					$resultat=$metier->listeJuste($_POST["page"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "J1-10":

			//Liste des assemblées auxquelles un juste a appartenues

				if($jwtManager->checkSession()){
					$resultat=$metier->ListeAssembleeJuste($_POST["juste"], $_POST["page"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "J1-11":
				//Recherche des informations d'un juste à partir de mots clés ("fulltext")

				if($jwtManager->checkSession()){
					$resultat=$metier->rechercherJusteParFulltext($_POST["text"],$_POST["page"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;

			case "J1-12":
				//Modifier le champ fulltext du juste
				if($jwtManager->checkSession()){
					$resultat=$metier->modifierJusteFullText($_POST["id"]);
					reponse($resultat);
				}else{
					reponse();
				}
			break;








			//GESTION RATTACHER CODE R1-...
			case "R1-1":

			//Affiliation d'un juste à une assemblée de juste donnée

				if($jwtManager->checkSession()){
						$resultat=$metier->ajouterRattacher($_POST["juste"], $_POST["assemblee"], $_POST["fonction"], $_POST["statut"], $_POST["dateDebut"]);
						reponse($resultat);
					}else{
						reponse();
					}
			break;

			case "R1-2":

			//Modification de l'affiliation d'un juste à une assemblée données

				if($jwtManager->checkSession()){
						$resultat=$metier->modifierRattacher($_POST["juste"], $_POST["assemblee"], $_POST["fonction"], $_POST["statut"], $_POST["id"]);
						reponse($resultat);
					}else{
						reponse();
					}
			break;

			case "R1-3":

			//Départ d'un juste d'une assemblée pour une autre (ou décès ou excomuniation)
				if($jwtManager->checkSession()){
						$resultat=$metier->terminerRattacher($_POST["dateFin"], $_POST["description"], $_POST["id"]);
						reponse($resultat);
					}else{
						reponse();
					}
			break;






			//GESTION SERVICE CODE S1-...
			case "S1-1":

			//Enregistrement d'un service (service des chantres ou nettoyage ...)

				if($jwtManager->checkSession()){
						$resultat=$metier->nouveauService($_POST["nom"],$_POST["date"]);
						reponse($resultat);
					}else{
						reponse();
					}
			break;

			case "S1-2":

			//Modification des informations d'un service

				if($jwtManager->checkSession()){
						$resultat=$metier->modifierService($_POST["nom"],$_POST["date"],$_POST["service"]);
						reponse($resultat);
					}else{
						reponse();
					}
			break;

			case "S1-3":

			//Recherche des informations d'un service donné à partir de son identifiant ("idservice")

				if($jwtManager->checkSession()){
						$resultat=$metier->rechercherService($_POST["service"]);
						reponse($resultat);
					}else{
						reponse();
					}
			break;

			case "S1-4":

			//Recherche des informations d'un service donné à partir de son matricule ("matservice")

				if($jwtManager->checkSession()){
						$resultat=$metier->rechercherServiceParMatricule($_POST["matricule"]);
						reponse($resultat);
					}else{
						reponse();
					}
			break;

			case "S1-5":

			//Liste paginée des services

				if($jwtManager->checkSession()){
						$resultat=$metier->listeService($_POST["page"]);
						reponse($resultat);
					}else{
						reponse();
					}
			break;

			case "S1-6":

			//Liste paginée des groupes associés à un service

				if($jwtManager->checkSession()){
						$resultat=$metier->listeGroupeService($_POST["service"], $_POST["page"]);
						reponse($resultat);
					}else{
						reponse();
					}
			break;

			default:
				reponse(array('code'=> -9, "message"=>"Requête REST echouée; code inconnu."), REPERTOIRE_SYS_LOG);	
		}	
	}else{
		reponse(array("code"=> -10,"message"=>"Requête REST echouée; Requête mal formée."), REPERTOIRE_SYS_LOG);
	}


/*
	TEMPLATE SWITCH CASE
				if($jwtManager->checkSession()){
					$resultat=$metier->
					reponse($resultat);
				}else{
					reponse();
				}
*/

 ?>