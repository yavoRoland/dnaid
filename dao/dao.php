<?php 	

	$chemin_base='/Applications/MAMP/htdocs/dnaid';
	spl_autoload_register(function($className){
		$chemin_base='/Applications/MAMP/htdocs/dnaid';
		$file=$chemin_base.'/dao/entites/'.$className.'.php';
		if(file_exists($file)){
			include_once($file);
		}
	});
	include_once($chemin_base.'/technique/utilitaire.php');

	class Dao{
		private $_bdd;
		public function __construct(){
			try{
				$this->_bdd=new PDO(BDD,USER,MDP);
			}catch(Exception $e){

			}
		}





		/**************************************************************************/
		/**************************************************************************/
		//GESTION APPARTENIR
		public function ajouterAppartenir($juste,$groupe,$role,$statut,$dateDebut){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$appartenir=new Appartenir($this->_bdd);
			return $appartenir->ajouter($juste,$groupe,$role,$statut,$dateDebut);
		}

		public function modifierAppartenir($juste,$groupe,$role,$statut,$dateDebut,$dateFin,$description,$id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$appartenir=new Appartenir($this->_bdd);
			return $appartenir->modifier($juste,$groupe,$role,$statut,$dateDebut,$dateFin,$description,$id);
		}

		public function rechercherAppartenir($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$appartenir=new Appartenir($this->_bdd);
			return $appartenir->rechercher($id);
		}

		public function supprimerAppartenir($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$appartenir=new Appartenir($this->_bdd);
			return $appartenir->supprimer($id);
		}







		/**************************************************************************/
		/**************************************************************************/
		//GESTION ASSEMBLEE
		public function ajouterAssemblee($matricule, $nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $fullText){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$assemblee=new Assemblee($this->_bdd);
			return $assemblee->ajouter($matricule, $nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $fullText);
		}

		public function modifierAssemblee($nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $fullText, $id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$assemblee=new Assemblee($this->_bdd);
			return $assemblee->modifer($nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $fullText, $id);
		}

		public function rechercherAssemblee($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$assemblee=new Assemblee($this->_bdd);
			return $assemblee->rechercher($id);
		}

		public function rechercherAssembleeParMatricule($matricule){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$assemblee=new Assemblee($this->_bdd);
			return $assemblee->rechercherParMatricule($matricule);
		}

		public function rechercherAssembleeParFullText($text, $page, $quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$assemblee=new Assemblee($this->_bdd);
			return $assemblee->rechercherParFullText($text, $page, $quantite);
		}

		public function supprimerAssemblee($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$assemblee=new Assemblee($this->_bdd);
			return $assemblee->supprimer($id);
		}

		public function listeAssemblee($page, $quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$assemblee=new Assemblee($this->_bdd);
			return $assemblee->liste($page, $quantite);
		}

		public function listeJusteAssemble($id, $page, $quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$assemblee=new Assemblee($this->_bdd);
			return $assemblee->listeJuste($id, $page, $quantite);
		}
		public function listeTotalJusteAssemble($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$assemblee=new Assemblee($this->_bdd);
			return $assemblee->listeTotalJuste($id);
		}



		/**************************************************************************/
		/**************************************************************************/
		//GESTION GROUPE
		public function ajouterGroupe($matricule, $nom, $dateCreat, $service){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$groupe=new Groupe($this->_bdd);
			return $groupe->ajouter($matricule, $nom, $dateCreat, $service);
		}

		public function modifierGroupe($nom, $dateCreat, $service, $id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$groupe=new Groupe($this->_bdd);
			return $groupe->modifier($nom, $dateCreat, $service, $id);
		}

		public function rechercherGroupe($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$groupe=new Groupe($this->_bdd);
			return $groupe->rechercher($id);
		}

		public function rechercherGroupeParMatricule($matricule){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$groupe=new Groupe($this->_bdd);
			return $groupe->rechercherParMatricule($matricule);
		}

		public function supprimerGroupe($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$groupe=new Groupe($this->_bdd);
			return $groupe->supprimer($id);
		}

		public function listeGroupe($page, $quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$groupe=new Groupe($this->_bdd);
			return $groupe->liste($page, $quantite);
		}

		public function listeJusteGroupe($id, $page, $quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$groupe=new Groupe($this->_bdd);
			return $groupe->listeJuste($id, $page, $quantite);
		}
		public function listeTotalJusteGroupe($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$groupe=new Groupe($this->_bdd);
			return $groupe->listeTotalJuste($id);
		}




		/**************************************************************************/
		/**************************************************************************/
		//GESTION JUSTE
		public function ajouterJuste($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $photo, $fullText){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->ajouter($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $photo, $fullText);
		}

		public function modifierJuste($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $photo, $fullText, $id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
				
			$juste=new Juste($this->_bdd);
			return $juste->modifier($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $photo, $fullText, $id);
		}

		public function modifierNiveauJuste($niveau, $login, $mdp, $id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->modifierNiveau($niveau, $login, $mdp, $id);
		}

		public function rechercherJuste($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->rechercher($id);
		}
		
		public function rechercheProfondeJuste($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->rechercheProfonde($id);
		}
		public function rechercherJusteParFulltext($text, $page, $quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->rechercherParFullText($text, $page, $quantite);
		}

		public function rechercherJusteParLogin($login){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->rechercherParLogin($login);
		}

		public function supprimerJuste($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->supprimer($id);
		}

		public function listeJuste($page,$quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->liste($page,$quantite);
		}

		public function listeGroupeJuste($id,$page,$quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->listeGroupe($id,$page,$quantite);
		}
		public function listeTotalGroupeJuste($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->listeTotalGroupe($id);
		}
		public function listeAssembleeJuste($id, $page, $quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$juste=new Juste($this->_bdd);
			return $juste->listeAssemble($id, $page, $quantite);
		}



		/**************************************************************************/
		/**************************************************************************/
		//GESTION RATTACHER
		public function ajouterRattacher($juste, $assemble, $fonction, $statut, $dateDebut){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$rattacher=new Rattacher($this->_bdd);
			return $rattacher->ajouter($juste, $assemble, $fonction, $statut, $dateDebut);
		}

		public function modifierRattacher($juste, $assemble, $fonction, $statut, $dateDebut, $dateFin, $description, $id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$rattacher=new Rattacher($this->_bdd);
			return $rattacher->modifier($juste, $assemble, $fonction, $statut, $dateDebut, $dateFin, $description, $id);
		}
		public function rechercherRattacher($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$rattacher=new Rattacher($this->_bdd);
			return $rattacher->rechercher($id);
		}
		public function supprimerRattacher($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$rattacher=new Rattacher($this->_bdd);
			return $rattacher->supprimer($id);
		}



		/**************************************************************************/
		/**************************************************************************/
		//GESTION SERVICE
		public function ajouterService($matricule,$nom,$date){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$service=new Service($this->_bdd);
			return $service->ajouter($matricule,$nom,$date);
		}

		public function modifierService($nom,$date,$id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$service=new Service($this->_bdd);
			return $service->modifier($nom,$date,$id);
		}

		public function rechercherService($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$service=new Service($this->_bdd);
			return $service->rechercher($id);
		}

		public function rechercherServiceParMatricule($matricule){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$service=new Service($this->_bdd);
			return $service->rechercherParMatricule($matricule);
		}

		public function supprimerService($id){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$service=new Service($this->_bdd);
			return $service->supprimer($id);
		}

		public function listeService($page, $quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$service=new Service($this->_bdd);
			return $service->liste($page, $quantite);
		}

		public function listeGroupeService($id, $page, $quantite){
			if(!$this->_bdd){
				throw new Exception('Echec de connexion à la base de données');
			}
			$service=new Service($this->_bdd);
			return $service->listeGroupe($id, $page, $quantite);
		}
	}




















 ?>