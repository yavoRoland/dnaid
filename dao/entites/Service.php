<?php 

	class Service{
		private $_bdd;
		public function __construct($bdd){
			$this->_bdd=$bdd;
		}


		public function ajouter($matricule,$nom,$date){
			try{
				$req=$this->_bdd->prepare('INSERT INTO service(matservice, nomservice, datecreatservice	) VALUES(?,?,?)');
				return $req->execute(array($matricule,$nom,$date));
			}catch(Exception $e){
				return false;
			}

		}

		public function modifier($nom,$date,$id){
			try{
				$req=$this->_bdd->prepare('UPDATE service SET nomservice=?, datecreatservice=? WHERE idservice=?');
				return $req->execute(array($nom,$date,$id));
			}catch(Exception $e){
				return false;
			}
		}

		public function rechercher($id){
			try{
				$req=$this->_bdd->prepare('SELECT * FROM service WHERE idservice=?');
				$req->execute(array($id));
				return $req;
			}catch(Exception $e){
				return false;
			}
		}

		public function rechercherParMatricule($matricule){
			try{
				$req=$this->_bdd->prepare('SELECT * FROM service WHERE matservice=?');
				$req->execute(array($matricule));
				return $req;
			}catch(Exception $e){
				return false;
			}
		}

		public function supprimer($id){
			try{
				$req=$this->_bdd->prepare('DELETE FROM service WHERE idservice=?');
				return $req->execute(array($id));
			}catch(Exception $e){
				return false;
			}
		}

		public function liste($page, $quantite){
			try{
				$total=$this->_bdd->query('SELECT COUNT(*) AS S_TOTAL FROM service');

				$req=$this->_bdd->prepare('SELECT * FROM service ORDER BY nomservice ASC LIMIT :page,:quantite');
				$req->bindValue(':page',($page*$quantite), PDO::PARAM_INT);
				$req->bindValue(':quantite',$quantite, PDO::PARAM_INT);
				$req->execute();

				return array($req, $total);
			}catch(Exception $e){
				return false;
			}	
		}

		public function listeGroupe($service, $page, $quantite){
			try{
				$total=$this->_bdd->prepare('SELECT COUNT(*) AS S_TOTAL FROM groupe WHERE idservicegroupe=?');
				$total->execute(array($service));


				$req=$this->_bdd->prepare('SELECT * FROM groupe WHERE idservicegroupe=:service ORDER BY nomgroupe ASC LIMIT :page,:quantite');
				$req->bindValue(':service',$service,PDO::PARAM_INT);
				$req->bindValue(':page',($page*$quantite), PDO::PARAM_INT);
				$req->bindValue(':quantite',$quantite, PDO::PARAM_INT);
				$req->execute();

				return array($req, $total);
			}catch(Exception $e){
				return false;
			}
		}
	}






 ?>