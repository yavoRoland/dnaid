<?php 	
	include_once('../dao/dao.php');
	include_once('../technique/JwtAuth.php');

	class MetierSystem{
		private $_dao;
		private $_authManager;
		public function __construct(){
			$this->_dao=New Dao();
			$this->_authManager=new JwtManager();
		}






		//GESTION APPARTENIR
		public function ajouterAppartenir($juste,$groupe,$role,$dateDebut){
			try{
				$infoJuste=$this->_dao->rechercherJuste($juste);
				if(!$infoJuste || $infoJuste->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Impossible d'ajouter l'appartenance! Juste inexistant."
					);
				}

				$infoGroupe=$this->_dao->rechercherGroupeParMatricule($groupe);
				if(!$infoGroupe || $infoGroupe->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Impossible d'ajouter l'appartenance! Juste inexistant."
					);
				}
				$infoGroupe=$infoGroupe->fetch();
				$infoUnicite=$this->_dao->rechercherAppartenirUnicite($juste,$infoGroupe["idgroupe"],ACTIF);
				if($infoUnicite && $infoUnicite->rowCount()>0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Impossible d'ajouter l'appartenance! Le juste appartient déjà à ce groupe."
					);
				}

				$resultat=$this->_dao->ajouterAppartenir($juste,$infoGroupe["idgroupe"],$role,ACTIF,$dateDebut);
				if($resultat){
					if (substr(php_uname(), 0, 7) == "Windows"){
						Utilitaire::ExecuterScript(WIN_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($juste,"J1-12",$this->_authManager->getBearerToken()));
					}else{
						Utilitaire::ExecuterScript(UNIX_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($juste,"J1-12",$this->_authManager->getBearerToken())); 
					}
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Appartenance enregistrée'
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec d'enregistrement d'appartenance."
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec d'enregistrement de l'appartenance; Une exception s'est produite!"
				);
			}
		}

		public function modifierAppartenir($groupe,$role,$statut,$dateDebut,$description,$id){
			try{
				$infoAppartenir=$this->_dao->rechercherAppartenir($id);
				if(!$infoAppartenir || $infoAppartenir->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Modification de l'appartenance impossible! Appartenance inexistante."
					);
				}

				/*$infoJuste=$this->_dao->rechercherJuste($juste);
				if(!$infoJuste || $infoJuste->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Impossible de modifier l'appartenance! Juste inexistant."
					);
				}*/

				$infoGroupe=$this->_dao->rechercherGroupeParMatricule($groupe);
				if(!$infoGroupe || $infoGroupe->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Impossible de modifier l'appartenance! Juste inexistant."
					);
				}
				$infoGroupe=$infoGroupe->fetch();
				$infoUnicite=$this->_dao->rechercherAppartenirUnicite($juste,$infoGroupe["idgroupe"],ACTIF);
				if($infoUnicite && $infoUnicite->rowCount()>0){//si le juste appartient déja a ce groupe dans un enregistrement
					$infoUnicite=$infoUnicite->fetch();
					if($infoUnicite["idappartenir"]!=$id){//si l'enregistrement en question est autre que celui que nous modifions cela implique qu'il pourrait y avoir deux enregistrement actif indiquant que le juste appartient a ce groupe
						return array(
							'resultat'=>false,
							'code'=>requete_echoue,
							'message'=>"Impossible d'ajouter l'appartenance! Le juste appartient déjà à ce groupe."
						);
					}
						
				}
				$a=$infoAppartenir->fetch();
				$resultat=$this->_dao->modifierAppartenir($a['justeappartenir'],$infoGroupe['idgroupe'],$role,$statut,$dateDebut,$a["datefinappartenir"], $description,$id);
				if($resultat){
					if (substr(php_uname(), 0, 7) == "Windows"){
						Utilitaire::ExecuterScript(WIN_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($a['justeappartenir'],"J1-12",$this->_authManager->getBearerToken()));
					}else{
						Utilitaire::ExecuterScript(UNIX_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($a['justeappartenir'],"J1-12",$this->_authManager->getBearerToken())); 
					}
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>"Appartenance modifiée"
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification de l'appartenance "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de modification de l'appartenance; Une exception s'est produite!"
				);
			}
		}

		public function termierAppartenir($dateFin, $description, $id){
			try{
				$infoAppartenir=$this->_dao->rechercherAppartenir($id);
				if(!$infoAppartenir || $infoAppartenir->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Modification de rattachement impossible! Rattachement inexistant."
					);
				}
				$a=$infoAppartenir->fetch();
				$resultat=$this->_dao->modifierAppartenir($a["justeappartenir"],$a["groupeappartenir"],$a["roleappartenir"],INACTIF,$a["datedebutappartenir"],$dateFin,trim($description),$id);
				if($resultat){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Appartenance cloturée'
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de clôture d'appartenance "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de clôture d'appartenance ; Une exception s'est produite!"
				);
			}
		}








		//GESTION ASSEMBLEE
		public function ajouterAssemblee($nom, $pays, $region, $departement, $ville, $commune, $quartier, $description){
			try{
				if(!Utilitaire::textCorrect($nom)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de l'enregistrement de la nouvelle assemblée! Veuillez specifier un nom d'assemblée correct"
					);
				}
				$matricule='';
				do{
					$matricule='A-'.Utilitaire::codeGenerator(6,true);
					$assemblee=$this->_dao->rechercherAssembleeParMatricule($matricule);
				}while($assemblee && $assemblee->rowCount()>0);

				$fullText=$matricule.' '.Utilitaire::textTrim($nom).' '.Utilitaire::textTrim($pays).' '.Utilitaire::textTrim($region).' '.Utilitaire::textTrim($departement).' '.Utilitaire::textTrim($ville).' '.Utilitaire::textTrim($commune).' '.Utilitaire::textTrim($quartier).' '.Utilitaire::textTrim($description);
				$fullText=Utilitaire::epuration($fullText);
				$resultat=$this->_dao->ajouterAssemblee($matricule, $nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $fullText);
				if($resultat){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Assemblée enregistré.',
						'donnee'=>$matricule
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec d'enregistrement de l'assemblée "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec d'enregistrement de l'assemblée; Une exception s'est produite!"
				);
			}
		}

		public function modifierAssemblee($nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $id){
			try{
				$infoAssemblee=$this->_dao->rechercherAssemblee($id);
				if(!$infoAssemblee || $infoAssemblee->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>'Modification impossible! Assemblée inexistante'
					);
				}
				if(!Utilitaire::textCorrect($nom)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de la modification de l'assemblée! Veuillez specifier un nom d'assemblée correct"
					);
				}
				$ia=$infoAssemblee->fetch();
				$fullText=$ia["matassemble"].' '.Utilitaire::textTrim($nom).' '.Utilitaire::textTrim($pays).' '.Utilitaire::textTrim($region).' '.Utilitaire::textTrim($departement).' '.Utilitaire::textTrim($ville).' '.Utilitaire::textTrim($commune).' '.Utilitaire::textTrim($quartier).' '.Utilitaire::textTrim($description);
				$fullText=Utilitaire::epuration($fullText);
				$resultat=$this->_dao->modifierAssemblee($nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $fullText, $id);
				if($resultat){
					// A FAIRE: mettre le code d'execution de l'api pour la fonction modifierJusteFullText
					if (substr(php_uname(), 0, 7) == "Windows"){
						Utilitaire::ExecuterScript(WIN_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($id,"A2-8",$this->_authManager->getBearerToken()));
					}else{
						Utilitaire::ExecuterScript(UNIX_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($id,"A2-8",$this->_authManager->getBearerToken())); 
					}
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Assemblée modifiée.'
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification de l'assemblée."
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de modification de l'assemblée ; Une exception s'est produite!"
				);
			}
		}

		public function rechercherAssemblee($id){
			try{
				$resultat=$this->_dao->rechercherAssemblee($id);

				if($resultat && $resultat->rowCount()>0){

					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Recherche par identifiant éffectué! Assemblée trouvée',
						'donnee'=>$resultat->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Recherche par identifiant éffectué! Assemblée introuvable."
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Requête de recherche d'assemblée par identifiant échouée; Une exception s'est produite!"
				);
			}
		}

		public function rechercherAssembleeParMatricule($matricule){
			try{
				$resultat=$this->_dao->rechercherAssembleeParMatricule($matricule);
				if($resultat && $resultat->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Recherche par matricule éffectuée! Assemblée trouvée.',
						'donnee'=>$resultat->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Recherche par matricule éffectuée! Assemblée introuvable"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Requête de recherche par matricule échouée; Une exception s'est produite!"
				);
			}
		}

		public function rechercherAssembleeParFullText($text, $page){
			try{
				$resultat=$this->_dao->rechercherAssembleeParFullText(Utilitaire::epuration($text), $page, STANDARD_QTE);

				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>"Requête d'assemblée par full text éffectuée! Liste des assemblées obtenues.",
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête  d'assemblée par full text éffectuée! Aucunes Assemblée correspondante"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de recherche d'assemblée par full text; Une exception s'est produite!"
				);
			}
		}

		public function listeAssemblee($page){
			try{
				$resultat=$this->_dao->listeAssemblee($page,STANDARD_QTE);
				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Requête liste des assemblées! Liste des assemblées obtenues',
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête liste des assemblées! Aucune assemblée n'est repertoriée"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Requête liste des assemblées echouée! Une exception s'est produite!"
				);
			}
		}

		public function listeJusteAssemble($assemblee, $page){
			try{
				$resultat=$this->_dao->listeJusteAssemble($assemblee, $page, STANDARD_QTE);
				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>"Requête liste des justes de l'assemblée! Liste des justes obtnus",
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête liste des justes de l'assemblée! Aucun justes n'est affiliés à cette assemblée"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Requête liste des justes de l'assemblée echouée; Une exception s'est produite!"
				);
			}
		}











		//GESTION GROUPE
		public function ajouterGroupe($nom, $dateCreat, $description, $service){//matricule du service
			try{
				if(!Utilitaire::textCorrect($nom)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de l'enregistrement du groupe! Veuillez specifier un nom d'assemblée correct"
					);
				}
				$matricule='';
				do{
					$matricule='G-'.Utilitaire::codeGenerator(6,true);
					$groupe=$this->_dao->rechercherGroupeParMatricule($matricule);
				}while($groupe && $groupe->rowCount()>0);

				$serviceInfo=$this->_dao->rechercherServiceParMatricule($service);
				if(!$serviceInfo || $serviceInfo->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec d'enregistrement de groupe! Service inexistant."
					);
				}
				$serviceInfo=$serviceInfo->fetch();
				$resultat=$this->_dao->ajouterGroupe($matricule, $nom, $dateCreat, $description, $serviceInfo["idservice"]);
				if($resultat){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>"groupe enregistré.",
						'donnee'=>$matricule
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec d'enregistrement de groupe "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de d'enregistrement de groupe; Une exception s'est produite!"
				);
			}
		}

		public function modifierGroupe($nom, $dateCreat, $description, $service, $id){
			try{
				$infoGroupe=$this->_dao->rechercherGroupe($id);
				if(!$infoGroupe || $infoGroupe->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Modification impossible! Groupe inexistant"
					);
				}

				if(!Utilitaire::textCorrect($nom)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Modification impossible! Veuillez specifier un nom de groupe correct"
					);
				}

				$serviceInfo=$this->_dao->rechercherServiceParMatricule($service);
				if(!$serviceInfo || $serviceInfo->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification du groupe! Service inexistant."
					);
				}
				$serviceInfo=$serviceInfo->fetch();

				$resultat=$this->_dao->modifierGroupe($nom, $dateCreat, $description, $serviceInfo["idservice"], $id);
				if($resultat){
					// A FAIRE: mettre le code d'execution de l'api pour la fonction modifierJusteFullTextParGroupe
					if (substr(php_uname(), 0, 7) == "Windows"){
						Utilitaire::ExecuterScript(WIN_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($id,"G1-7",$this->_authManager->getBearerToken()));
					}else{
						Utilitaire::ExecuterScript(UNIX_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($id,"G1-7",$this->_authManager->getBearerToken())); 
					}
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Groupe modifié'
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification du groupe "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de modification du groupe ; Une exception s'est produite!"
				);
			}
		}

		public function rechercherGroupe($id){
			try{
				$resultat=$this->_dao->rechercherGroupe($id);
				if($resultat && $resultat->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Recherche groupe par identifiant éffectuée! Groupe trouvé',
						'donnee'=>$resultat->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de recherche groupe par identifiant! Groupe introuvable."
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de recherche de groupe par identifiant; Une exception s'est produite!"
				);
			}
		}

		public function rechercherGroupeParMatricule($matricule){
			try{
				$resultat=$this->_dao->rechercherGroupeParMatricule($matricule);
				if($resultat && $resultat->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Recherche groupe par matricule éffectuée! Groupe touvé.',
						'donnee'=>$resultat->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de recherche groupe par matricule! "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de recherche groupe par matricule; Une exception s'est produite!"
				);
			}
		}

		public function rechercherGroupeParFullText($text, $page){
			try{
				$resultat=$this->_dao->rechercherGroupeParFullText($text, $page, STANDARD_QTE);

				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>"Requête de groupe par full text éffectuée! Liste des groupe obtenus.",
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête de groupe par full text éffectuée! Aucun groupe correspondant"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de requête de groupe par full text ; Une exception s'est produite!"
				);
			}
		}

		public function listeGroupe($page){
			try{
				$resultat=$this->_dao->listeGroupe($page, STANDARD_QTE);
				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Requête liste des groupes! Liste des groupes obtnus',
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête liste des groupes! Aucun groupes repertorié."
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de Requête liste des groupes; Une exception s'est produite!"
				);
			}
		}

		public function listeJusteGroupe($groupe, $page){
			try{
				$resultat=$this->_dao->listeJusteGroupe($groupe, $page, SMALL_QTE);
				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Requête liste des justes du groupe; Liste des justes obtnus',
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Requête liste des justes du groupe; Aucun justes affiliés au groupe"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de requête liste des justes du groupe ; Une exception s'est produite!"
				);
			}
		}










		//GESTION JUSTE
		public function ajouterJuste($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $photo, $origine, $datedeces, $assemblee, $dateRattacher,$fonction){
			try{
				if(!Utilitaire::textCorrect($nom)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec d'enregistrement d'un nouveau juste; Veuillez specifier un nom correct"
					);
				}

				if(!Utilitaire::textCorrect($prenom)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec d'enregistrement d'un nouveau juste; Veuillez specifier un prenom correct"
					);
				}
				$photoJuste='';
				if(!is_null($photo)){
					$photoJuste=Utilitaire::enregistrerFichier($photo["donnee"],$photo["ext"],REPERTOIRE_PHOTO_JUSTE);
					if(!$photoJuste){
						return array(
							'resultat'=>false,
							'code'=>requete_echoue,
							'message'=>"Echec d'enregistrement d'un nouveau juste; Une exception s'est produite lors de l'enregistrement de la photo"
						);
					}
				}

				$infoAssemblee=$this->_dao->rechercherAssembleeParMatricule($assemblee);
				if(!$infoAssemblee || $infoAssemblee->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de l'enregistrement du juste. L'assemblee n'existe pas."
					);
				}
				$assemblee = $infoAssemblee->fetch();


				$fullText=Utilitaire::textTrim($nom).' '.Utilitaire::textTrim($prenom).' '.Utilitaire::textTrim($surnom).' '.Utilitaire::textTrim($genre).' '.Utilitaire::textTrim($adresse).' '.Utilitaire::textTrim($grade).' '.Utilitaire::textTrim($profession).' '.Utilitaire::textTrim($statutMatri).' '.Utilitaire::textTrim($ethnie).' '.Utilitaire::textTrim($origine);
				$fullText=Utilitaire::epuration($fullText);

				$addJuste=$this->_dao->ajouterJuste($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $photoJuste, $origine, $datedeces, $fullText);
				

				$resultat=false;

				if($addJuste["resultat"]){
					$resultat=$this->_dao->ajouterRattacher($addJuste["id"], $assemblee['idassemble'], $fonction, ACTIF, $dateRattacher);
				}
				if($addJuste["resultat"] && $resultat){

					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Juste enregistré'
					);
				}else{
					$this->_dao->supprimerJuste($addJuste["id"]);
					if($photoJuste!=''){
						Utilitaire::supprimerFichier($photoJuste, REPERTOIRE_PHOTO_JUSTE);
					}
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de l'enregistrement du juste "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de l'enregistrement du juste ; Une exception s'est produite!"
				);
			}
		}

		public function modifierJuste($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $origine, $datedeces, $id){
			try{
				$infoJuste=$this->_dao->rechercheProfondeJuste($id);
				if(!$infoJuste || $infoJuste->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification des infos du juste; Juste inexistant"
					);
				}
				if(!Utilitaire::textCorrect($nom)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification des infos du juste; Veuillez specifier un nom correct"
					);
				}

				if(!Utilitaire::textCorrect($prenom)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification des infos du juste; Veuillez specifier un prenom correct"
					);
				}


				$j=$infoJuste->fetch();

				$resultat=$this->_dao->modifierJuste($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $j["photojuste"], $origine, $datedeces, $j["fulltextjuste"], $id);
				if($resultat){
					// A FAIRE: mettre le code d'execution de l'api pour la fonction modifierJusteFullText
					if (substr(php_uname(), 0, 7) == "Windows"){
						Utilitaire::ExecuterScript(WIN_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($id,"J1-12",$this->_authManager->getBearerToken()));
					}else{
						Utilitaire::ExecuterScript(UNIX_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($id,"J1-12",$this->_authManager->getBearerToken())); 
					}
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Modification des informations du juste éffectuée!',
					);

				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification des informations du juste! "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de modification des informations du juste ; Une exception s'est produite!"
				);
			}

		}

		public function modifierPhotoJuste($photo, $juste){
			try{
				$infoJuste=$this->_dao->rechercheProfondeJuste($juste);
				if(!$infoJuste || $infoJuste->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification de la photo du juste; Juste inexistant"
					);
				}
				if(is_null($photo)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification de la photo du juste; photo incorrecte"
					);
				}
				$photoJuste=Utilitaire::enregistrerFichier($photo["donnee"], $photo["extension"], REPERTOIRE_PHOTO_JUSTE);
				if(!$photoJuste){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification de la photo du juste; Echec de l'enregistrement de l'image"
					);
				}
				$j=$infoJuste->fetch();
				$resultat=$this->_dao->modifierJuste($j["nomjuste"], $j["prenomjuste"], $j["surnomjuste"], $j["datenaissjuste"], $j["genrejuste"], $j["etatjuste"], $j["adressejuste"], $j["phonejuste"], $j["gradejuste"], $j["anneenvelnaissjuste"], $j["professionjuste"], $j["statutmatrijuste"], $j["ethniejuste"], $photoJuste, $j["originejuste"],$j["datedecesjuste"], $j["fulltextjuste"], $juste);
				if($resultat){
					if(!is_null($j["photojuste"]) && $j["photojuste"]!=''){
						Utilitaire::supprimerFichier($j["photojuste"], REPERTOIRE_PHOTO_JUSTE);
					}
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Modification de la photo du juste effectué'
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification de la photo du juste "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de modification de la photo du juste ; Une exception s'est produite!"
				);
			}
		}

		public function modifierMDPJuste($ancien, $nouveau, $id){
			try{
				$juste=$this->_dao->rechercheProfondeJuste($id);
				if(!$juste || $juste->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Requête de modification de mot de passe échouée; juste introuvable."
					);
				}
				$j=$juste->fetch();

				if(strlen($j["loginjuste"])==0 || $j["niveaujuste"]<=JUSTE_LAMBDA){
					return array(
						'resultat'=>false,
						'code'=>acces_refuse,
						'message'=>"Requête de modification de mot de passe échouée; Accès interdit."
					);
				}
				if(!password_verify($ancien, $j['mdpjuste'])){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Requête de modification de mot de passe échouée; mot de passe incorrect."
					);
				}
				$mdp=password_hash($nouveau, PASSWORD_BCRYPT);
				$resultat=$this->_dao->modifierNiveauJuste($j['niveaujuste'], $j['loginjuste'], $mdp, $id);
				if($resultat){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Mot de passe du juste modifié!',
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification du mot de passe du juste "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de modification du mot de passe du juste ; Une exception s'est produite!"
				);
			}
		}

		public function modifierNiveauJuste($niveau, $juste){
			try{
				$justeInfo=$this->_dao->rechercheProfondeJuste($juste);
				if(!$justeInfo || $justeInfo->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Requête de modification du niveau du juste échouée; juste introuvable."
					);
				}
				$justeInfo=null;
				$login="";
				$mdp="";
				$clearMdp="";
				if($niveau>JUSTE_LAMBDA){
					do{
						$login='J-'.Utilitaire::codeGenerator(6,true);
						$justeInfo=$this->_dao->rechercherJusteParLogin($login);//on s'assure que le login n'est pas deja attribué
					}while($justeInfo && $justeInfo->rowCount()>0);
					$clearMdp=Utilitaire::codeGenerator(6);
					$mdp=password_hash($clearMdp, PASSWORD_BCRYPT);
				}
				$resultat=$this->_dao->modifierNiveauJuste($niveau, $login, $mdp, $juste);
				if($resultat){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Niveau du juste modifié!',
						'donnee'=>array(
							'login'=>$login,
							'mdp'=> $clearMdp,
							'niveau'=>$niveau
						)
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification du niveau du juste "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de modification du niveau du juste ; Une exception s'est produite!"
				);
			}
		}

		public function connexionParLoginJuste($login, $mdp){
			try{
				if(strlen(trim($login))==0){
					return array(
						'resultat'=>false,
						'code'=>acces_refuse,
						'message'=>"Requête de connexion de juste par login échouée; Accès interdit."
					);
				}
				
				$juste=$this->_dao->rechercherJusteParLogin($login);
				if(!$juste || $juste->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Requête de connexion de juste par login échouée; login ou mot de passe incorrect."
					);
				}

				$j=$juste->fetch();
				if($j["niveaujuste"]<=JUSTE_LAMBDA){
					return array(
						'resultat'=>false,
						'code'=>acces_refuse,
						'message'=>"Requête de connexion de juste par login échouée; Accès interdit."
					);
				}

				if(!password_verify($mdp, $j['mdpjuste'])){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Requête de connexion de juste par login échouée; login ou mot de passe incorrect."
					);
				}
				$headers = array('alg'=>'HS256','typ'=>'JWT');
				$payload = array('userid'=>$j['idjuste'], 'userlevel'=>$j["niveaujuste"], 'exp'=>(time() + 1800));
				$jwt=$this->_authManager->generateJwt($headers, $payload);
				if($juste && $juste->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Connexion par login éffectuée.',
						'donnee'=>array(
							'user'=>$j,
							'jwt'=>$jwt
						)
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de connexion par login."
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de connexion ; Une exception s'est produite!"
				);
			}
		}

		public function rechercherJuste($id){
			try{
				$resultat=$this->_dao->rechercherJuste($id);
				if($resultat && $resultat->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Recherche juste par identifiant effectuée; juste trouvé',
						'donnee'=>$resultat->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Requête de recherche de juste par identifiant échouée; juste introuvable "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Requête de recherche de juste par identifiant échouée; Une exception s'est produite!"
				);
			}
		}

		public function rechercheProfondeJuste($id){
			try{
				$resultat=$this->_dao->rechercheProfondeJuste($id);
				if($resultat && $resultat->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Recherche profonde juste par identifiant effectuée; juste trouvé',
						'donnee'=>$resultat->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Requête de recherche profonde de juste par identifiant échouée; juste introuvable "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Requête de recherche profonde de juste par identifiant échouée; Une exception s'est produite!"
				);
			}
		}

		public function rechercherJusteParFulltext($text, $page){
			try{
				$resultat=$this->_dao->rechercherJusteParFulltext(Utilitaire::epuration($text), $page, STANDARD_QTE);

				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>"Requête de juste par full text éffectuée! Liste des justes obtenus.",
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête de juste par full text éffectuée! Aucun juste correspondant"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de requête de juste par full text ; Une exception s'est produite!"
				);
			}
		}

		public function listeAssembleeJusteParStatut($juste,$page,$statut){
			$resultat=$this->_dao->listeAssembleeJusteParStatut($juste, $page, $statut, STANDARD_QTE);
			try{
				if($resultat && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>"Requête assemblée du juste; assemblée obtenue.",
						'donnee'=>$resultat[0]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête assemblée du juste; Aucune Assemblée obtenue."
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de Requête assemblée du juste; Une exception s'est produite!"
				);
			}
		}

		public function listeJuste($page){
			try{
				$resultat=$this->_dao->listeJuste($page, STANDARD_QTE);
				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Requête liste des justes; Liste des justes obtnus',
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête liste des justes; Aucun justes repertorié"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de Requête liste des justes; Une exception s'est produite!"
				);
			}
		}

		public function ListeAssembleesJuste($juste, $page){
			$resultat=$this->_dao->listeAssembleeJuste($juste, $page, STANDARD_QTE);
			try{
				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>"Requête historique des assemblées du juste; Liste des assemblées obtenues.",
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête historique des assemblées du juste; Aucune Assemblée affiliée"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de Requête historique des assemblées du juste; Une exception s'est produite!"
				);
			}
		}

		public function ListeGroupeJuste($juste, $page){
			$resultat=$this->_dao->listeGroupeJuste($juste, $page, STANDARD_QTE);
			try{
				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>"Requête liste des groupes du juste; Liste des assemblées obtenues.",
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête liste des groupes du juste; Aucun groupe affiliée"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de Requête liste des groupes du juste; Une exception s'est produite!"
				);
			}
		}


		




		


		//GESTION RATTACHER
		
		public function ajouterRattacher($juste, $assemblee, $fonction, $statut, $dateDebut){
			try{
				$infoJuste=$this->_dao->rechercheProfondeJuste($juste);
				if(!$infoJuste || $infoJuste->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Rattachement impossible! Juste inexistant."
					);
				}

				$infoAssemblee=$this->_dao->rechercherAssembleeParMatricule($assemblee);
				if(!$infoAssemblee || $infoAssemblee->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Rattachement impossible! Assemblee inexistante."
					);
				}
				$infoAssemblee=$infoAssemblee->fetch();
				$infoUnicite=$this->_dao->rechercherRattacherUnicite($juste,$infoAssemblee["idassemble"], ACTIF);
				if($infoUnicite && $infoUnicite->rowCount()>0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Rattachement impossible! Le juste appartient déjà à cette assemblée."
					);
				}
				

				$resultat=$this->_dao->ajouterRattacher($juste, $infoAssemblee["idassemble"], $fonction, $statut, $dateDebut);
				if($resultat){
					if (substr(php_uname(), 0, 7) == "Windows"){
						Utilitaire::ExecuterScript(WIN_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($juste,"J1-12",$this->_authManager->getBearerToken()));
					}else{
						Utilitaire::ExecuterScript(UNIX_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($juste,"J1-12",$this->_authManager->getBearerToken())); 
					}
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Rattachement effectué!'
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de rattachement!"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de rattachement; Une exception s'est produite!"
				);
			}
		}


		public function modifierRattacher($juste, $assemblee, $fonction, $statut, $id){
			try{
				$infoRattacher=$this->_dao->rechercherRattacher($id);
				if(!$infoRattacher || $infoRattacher->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Modification de rattachement impossible! Rattachement inexistant."
					);
				}
				$infoJuste=$this->_dao->rechercheProfondeJuste($juste);
				if(!$infoJuste || $infoJuste->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Modification de rattachement impossible! Juste inexistant."
					);
				}

				$infoAssemblee=$this->_dao->rechercherAssembleeParMatricule($assemblee);
				if(!$infoAssemblee || $infoAssemblee->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Modification de rattachement impossible! Assemblee inexistante."
					);
				}
				$infoAssemblee=$infoAssemblee->fetch();
				$infoUnicite=$this->_dao->rechercherRattacherUnicite($juste,$infoAssemblee["idassemble"], ACTIF);
				if($infoUnicite && $infoUnicite->rowCount()>0){//s'il existe un enregistrement indiquant que le juste appartient deja à cette assemblée
					$infoUnicite=$infoUnicite->fetch();
					if($infoUnicite["idrattacher"]!=$id){//si l'enregistrement est autre que celui que nous voulons modifier, cela implique qu'on aura deux enregistrement actifs indiquant que le juste appartient à cette assemblée
						return array(
							'resultat'=>false,
							'code'=>requete_echoue,
							'message'=>"Rattachement impossible! Le juste appartient déjà à cette assemblée."
						);
					}		
				}
				$rattacher=$infoRattacher->fetch();
				$resultat=$this->_dao->modifierRattacher($juste, $infoAssemblee["idassemble"], $fonction, $statut, $rattacher["datedebutrattacher"], $rattacher["datefinrattacher"], $rattacher["descriptionrattacher"], $id);

				if($resultat){
					if (substr(php_uname(), 0, 7) == "Windows"){
						Utilitaire::ExecuterScript(WIN_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($juste,"J1-12",$this->_authManager->getBearerToken()));
					}else{
						Utilitaire::ExecuterScript(UNIX_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($juste,"J1-12",$this->_authManager->getBearerToken())); 
					}
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Rattachement modifié',
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de modification du rattachement."
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de modification du rattachement; Une exception s'est produite!"
				);
			}
		}

		public function terminerRattacher($dateFin, $description, $id){
			try{
				$infoRattacher=$this->_dao->rechercherRattacher($id);
				if(!$infoRattacher || $infoRattacher->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Modification de rattachement impossible! Rattachement inexistant."
					);
				}

				$r=$infoRattacher->fetch();
				$resultat=$this->_dao->modifierRattacher($r["justerattacher"], $r["assemblerattacher"], $r["fonctionrattacher"], INACTIF, $r["datedebutrattacher"], $dateFin, trim($description), $id);
				if($resultat){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Rattachement terminer.'
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de finalisation de rattachement"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de finalisation de rattachement; Une exception s'est produite!"
				);
			}
		}

		public function muterRattacher($oldId,$dateMutation,$description,$assemblee,$fonction){
			try{
				$infoRattacher=$this->_dao->rechercherRattacher($oldId);
				if(!$infoRattacher || $infoRattacher->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Mutation impossible! Rattachement inexistant."
					);
				}
				$infoAssemblee=$this->_dao->rechercherAssembleeParMatricule($assemblee);
				if(!$infoAssemblee || $infoAssemblee->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Mutation impossible! Assemblee inexistante."
					);
				}
				$infoAncien=$infoRattacher->fetch();
				$infoAssemblee=$infoAssemblee->fetch();
				$infoUnicite=$this->_dao->rechercherRattacherUnicite($infoAncien['justerattacher'],$infoAssemblee["idassemble"], ACTIF);
				if($infoUnicite && $infoUnicite->rowCount()>0){//si il existe deja un enregistrement actif indiquant que le juste apppartient à cette même assemblée
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Mutation impossible! Le juste appartient déjà à cette assemblée."
					);	
				}
				
				$terminerAncien=$this->_dao->modifierRattacher($infoAncien['justerattacher'], $infoAncien['assemblerattacher'], $infoAncien['fonctionrattacher'], INACTIF, $infoAncien["datedebutrattacher"], $dateMutation, $description, $oldId);
				if($terminerAncien){
					$resultat=$this->_dao->ajouterRattacher($infoAncien['justerattacher'], $infoAssemblee["idassemble"], $fonction, ACTIF, $dateMutation);
					if($resultat){
						if (substr(php_uname(), 0, 7) == "Windows"){
							Utilitaire::ExecuterScript(WIN_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($infoAncien['justerattacher'],"J1-12",$this->_authManager->getBearerToken()));
						}else{
							Utilitaire::ExecuterScript(UNIX_UPDATE_JUSTE_FULL_TEXT_SCRIPT,array($infoAncien['justerattacher'],"J1-12",$this->_authManager->getBearerToken())); 
						}
						return array(
							'resultat'=>true,
							'code'=>requete_reussi,
							'message'=>'Mutation effectuée!'
						);
					}else{
						$this->_dao->modifierRattacher($infoAncien['justerattacher'], $infoAncien['assemblerattacher'], $infoAncien['fonctionrattacher'], $infoAncien['statutrattacher'], $infoAncien["datedebutrattacher"], $infoAncien['datefinrattacher'], $infoAncien['descriptionrattacher'], $oldId);

						return array(
							'resultat'=>false,
							'code'=>requete_echoue,
							'message'=>"Echec de mutation du rattachement. Enregistrement du nouveau rattachement impossible!"
						);
					}
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de mutation du rattachement. interruption du précédent rattachement impossible!"
					);
				}
			}catch(Exception $e){

			}
		}











		//GESTION SERVICE
		public function nouveauService($nom,$date){
			try{
				if($this->getNiveauUtilisateur()<JUSTE_SUPER_ADMIN){
					return array(
						'resultat'=>false,
						'code'=>acces_refuse,
						'message'=>"Echec de l'enregistrement d'un nouveau service! Accès refusé. Niveau d'autorité superieur requis."
					);
				}
				if(!Utilitaire::textCorrect($nom)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de l'enregistrement d'un nouveau service! Veuillez specifier un nom de service correct"
					);
				}
				$matricule='';
				do{
					$matricule='S-'.Utilitaire::codeGenerator(6,true);
					$service=$this->_dao->rechercherServiceParMatricule($matricule);
				}while($service && $service->rowCount()>0);
				
				$resultat=$this->_dao->ajouterService($matricule,$nom,$date);

				if($resultat){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Nouveau service enregistré!',
						'donnee'=>$matricule
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de l'enregistrement d'un nouveau service!"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec d'enregistrement d'un nouveau service; Une exception s'est produite!"
				);
			}
		}

		public function modifierService($nom,$date,$id){
			try{
				if(!Utilitaire::textCorrect($nom)){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de de la modification du service! Veuillez specifier un nom de service correct"
					);
				}

				$service=$this->_dao->rechercherService($id);
				if(!$service || $service->rowCount()==0){
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de de la modification du service! Service introuvable."
					);
				}

				$resultat=$this->_dao->modifierService($nom,$date,$id);
				if($resultat){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Service modifié!'
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de la modification du service! "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de la modification du service; Une exception s'est produite!"
				);
			}
		}

		public function rechercherService($id){
			try{
				$resultat=$this->_dao->rechercherService($id);
				if($resultat && $resultat->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Service trouvé',
						'donnee'=>$resultat->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Requête de recherche de service par identifiant echouée! Service introuvable."
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Requête de recherche de service échouée; Une exception s'est produite!"
				);
			}
		}

		public function rechercherServiceParMatricule($matricule){
			try{
				$resultat=$this->_dao->rechercherServiceParMatricule($matricule);
				if($resultat && $resultat->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Service trouvé',
						'donnee'=>$resultat->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Requête echouée! Service introuvable."
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Requête échouée; Une exception s'est produite!"
				);
			}
		}

		public function rechercherServiceParFullText($text,$page){
			try{
				$resultat=$this->_dao->rechercherServiceParFullText($text, $page, STANDARD_QTE);

				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>"Requête de service par full text éffectuée! Liste des service obtenus.",
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Requête de service par full text éffectuée! Aucun service correspondant"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de requête de service par full text ; Une exception s'est produite!"
				);
			}
		}

		public function listeService($page){
			try{
				$resultat=$this->_dao->listeService($page, STANDARD_QTE);
				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Liste des services!',
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Aucun service repertorié!"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Requête échouée; Une exception s'est produite!"
				);
			}
		}

		public function listeGroupeService($service, $page){
			try{
				$resultat=$this->_dao->listeGroupeService($service, $page, STANDARD_QTE);
				if($resultat[0] && $resultat[0]->rowCount()>0){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Liste des groupes du service!',
						'donnee'=>$resultat[0]->fetchAll(),
						'total'=>$resultat[1]->fetch()
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>liste_vide,
						'message'=>"Aucun groupe repertorié dans ce service!"
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Requête échouée; Une exception s'est produite!"
				);
			}
		}









		//UTILITAIRES METIER
		public function modifierJusteFullText($juste){
			try{
				
				$ji=$this->_dao->rechercheProfondeJuste($juste)->fetch();//justeInfo
				$justeGroupeInfo=$this->_dao->listeTotalGroupeJuste($juste);//justeGroupeInfo
				$jai=$this->_dao->listeAssembleeJuste($juste, 0 , SMALL_QTE)[0];


				$fullText=Utilitaire::textTrim($ji["nomjuste"]).' '.Utilitaire::textTrim($ji["prenomjuste"]).' '.Utilitaire::textTrim($ji["surnomjuste"]).' '.Utilitaire::textTrim($ji["phonejuste"]."").' '.Utilitaire::textTrim($ji["genrejuste"]).' '.Utilitaire::textTrim($ji["adressejuste"]).' '.Utilitaire::textTrim($ji["gradejuste"]).' '.Utilitaire::textTrim($ji["professionjuste"]).' '.Utilitaire::textTrim($ji["statutmatrijuste"]).' '.Utilitaire::textTrim($ji["ethniejuste"]).' '.Utilitaire::textTrim($ji["originejuste"]);
				if($jai->rowCount()>0){
					$jai=$jai->fetch();
					$fullText =$fullText .' '.Utilitaire::textTrim($jai["fulltextassemble"]);
				}
				
				if($justeGroupeInfo->rowCount()>0){
					$jgi=$justeGroupeInfo->fetchAll();
					for($i=0; $i<sizeof($jgi); $i++){
						$fullText= $fullText .' '.$jgi[$i]["nomgroupe"].' '.$jgi[$i]["nomservice"];
					}
				}
				$fullText=Utilitaire::epuration($fullText);
				$resultat=$this->_dao->modifierJuste($ji["nomjuste"], $ji["prenomjuste"], $ji["surnomjuste"], $ji["datenaissjuste"], $ji["genrejuste"], $ji["etatjuste"], $ji["adressejuste"], $ji["phonejuste"], $ji["gradejuste"], $ji["anneenvelnaissjuste"], $ji["professionjuste"], $ji["statutmatrijuste"], $ji["ethniejuste"], $ji["photojuste"], $ji["originejuste"], $ji["datedecesjuste"], $fullText, $juste);
				
				return true;
				

			}catch(Exception $e){
				$resultat=$this->_dao->modifierJuste($ji["nomjuste"], $ji["prenomjuste"], $ji["surnomjuste"], $ji["datenaissjuste"], $ji["genrejuste"], $ji["etatjuste"], $ji["adressejuste"], $ji["phonejuste"], $ji["gradejuste"], $ji["anneenvelnaissjuste"], $ji["professionjuste"], $ji["statutmatrijuste"], $ji["ethniejuste"], $ji["photojuste"], $ji["originejuste"], $ji["datedecesjuste"], FULL_TEXT_ERROR, $juste);
				return false;
			}


		}


		public function modifierJusteFullTextParGroupe($groupe){
			try{
				$justesInfos=$this->_dao->listeTotalJusteGroupe($groupe)->fetchAll();
				foreach($justesInfos as $elt){
					$this->modifierJusteFullText($elt['idjuste']);
				}
			}catch(Exception $e){
				return false;
			}
		}

		public function modifierJusteFullTextParAssemblee($assemblee){
			try{
				$justesInfos=$this->_dao->listeTotalJusteAssemble($assemblee)->fetchAll();
				foreach($justesInfos as $elt){
					$this->modifierJusteFullText($elt['idjuste']);
				}
			}catch(Exception $e){
				return false;
			}
		}


		public function increaseJwtTime(){
			$jwt=$this->_authManager->getBearerToken();
			$payload=$this->_authManager->getJwtPayload($jwt);
			$user=$this->_dao->rechercheProfondeJuste(json_decode($payload)->userid);
			if($user && $user->rowCount()>0){
				$j=$user->fetch();
				$headers = array('alg'=>'HS256','typ'=>'JWT');
				$payload = array('userid'=>$j['idjuste'], 'userlevel'=>$j["niveaujuste"], 'exp'=>(time() + 1800));
				return $this->_authManager->generateJwt($headers, $payload);
			}else{
				return false;
			}
		}

		private function getNiveauUtilisateur(){
			$jwt=$this->_authManager->getBearerToken();
			$payload=$this->_authManager->getJwtPayload($jwt);
			return json_decode($payload)->userlevel;
		}

		private function getUserId(){
			$jwt=$this->_authManager->getBearerToken();
			$payload=$this->_authManager->getJwtPayload($jwt);
			return json_decode($payload)->userid;
		}

		



		
	}









/*
TEMPLATE DES FONCTIONS


			try{
				if($resultat){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'',
						'donnee'=>
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de ... "
					);
				}
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>"Echec de ... ; Une exception s'est produite!"
				);
			}

		*/
 ?>