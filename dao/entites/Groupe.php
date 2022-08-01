<?php 	
	class Groupe{

		private $_bdd;
		public function __construct($bdd){
			$this->_bdd=$bdd;
		}

		public function ajouter($matricule, $nom, $dateCreat, $description, $service){
			try{
				$req=$this->_bdd->prepare('INSERT INTO groupe(matgroupe, nomgroupe, datecreatgroupe, descriptiongroupe, idservicegroupe) VALUES(?,?,?,?,?)');
				return $req->execute(array($matricule, $nom, $dateCreat, $description, $service));
			}catch(Exception $e){
				return false;
			}
		}

		public function modifier($nom, $dateCreat, $description, $service, $id){
			try{
				$req=$this->_bdd->prepare('UPDATE groupe SET nomgroupe=?, datecreatgroupe=?, descriptiongroupe=?,idservicegroupe=? WHERE idgroupe=?');
				return $req->execute(array($nom, $dateCreat, $description, $service, $id));
			}catch(Exception $e){
				return false;
			}
		}

		public function rechercher($id){
			try{
				$req=$this->_bdd->prepare('SELECT * FROM groupe WHERE idgroupe=?');
				$req->execute(array($id));
				return $req;
			}catch(Exception $e){
				return false;
			}
		}

		public function rechercherParMatricule($matricule){
			try{
				$req=$this->_bdd->prepare('SELECT * FROM groupe WHERE matgroupe=?');
				$req->execute(array($matricule));
				return $req;
			}catch(Exception $e){
				return false;
			}
		}

		public function supprimer($id){
			try{
				$req=$this->_bdd->prepare('DELETE FROM groupe WHERE idgroupe=?');
				return $req->execute(array($id));
			}catch(Exception $e){
				return false;
			}
		}

		public function liste($page, $quantite){
			try{
				$total=$this->_bdd->query('SELECT COUNT(*) AS G_TOTAL FROM groupe');

				$req=$this->_bdd->prepare('SELECT * FROM groupe ORDER BY nomgroupe ASC LIMIT :page,:quantite');
				$req->bindValue(':page',($page*$quantite), PDO::PARAM_INT);
				$req->bindValue(':quantite',$quantite, PDO::PARAM_INT);
				$req->execute();

				return array($req, $total);
			}catch(Exception $e){
				return false;
			}
		}

		public function listeJuste($groupe, $page, $quantite){
			try{
				$APPARTENIR_ACTIF=1;
				$total=$this->_bdd->prepare('SELECT COUNT(*) AS G_TOTAL FROM appartenir WHERE groupeappartenir=? AND statutappartnir=?');
				$total->execute(array($groupe,$APPARTENIR_ACTIF));

				$req=$this->_bdd->prepare('SELECT * FROM appartenir, juste WHERE groupeappartenir=:groupe AND statutappartenir=:statut AND justeappartenir=idjuste ORDER BY nomgroupe ASC LIMIT :page,:quantite');
				$req->bindValue(':statut',$APPARTENIR_ACTIF,PDO::PARAM_INT);
	            $req->bindValue(':groupe',$groupe,PDO::PARAM_INT);
	            $req->bindValue(':page',($page*$quantite), PDO::PARAM_INT);
	            $req->bindValue(':quantite',$quantite, PDO::PARAM_INT);
	            $req->execute();

	            return array($req, $total);
	        }catch(Exception $e){
	            return false;
	        }
		}
		public function listeTotalJuste($groupe){
			try{
				$APPARTENIR_ACTIF=1;
				$req=$this->_bdd->prepare('SELECT * FROM appartenir, juste WHERE groupeappartenir=? AND statutappartnir=? AND justeappartenir=idjuste');
				$req->execute(array($groupe,$APPARTENIR_ACTIF));
				return $req;
			}catch(Exception $e){
				return false;
			}
		}
	}



 ?>