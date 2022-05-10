<?php 	
	class Groupe{

		private $_bdd;
		public function __construct($bdd){
			$this->_bdd=$bdd;
		}

		public function ajouter($matricule, $nom, $dateCreat, $service){
			try{
				$req=$this->_bdd->prepare('INSERT INTO groupe(matgroupe, nomgroupe, datecreatgroupe, idservicegroupe) VALUES(?,?,?,?)');
				return $req->execute(array($matricule, $nom, $dateCreat, $service));
			}catch(Exception $e){
				return false;
			}
		}

		public function modifier($nom, $dateCreat, $service, $id){
			try{
				$req=$this->_bdd->prepare('UPDATE groupe SET nomgroupe=?, datecreatgroupe=? ,idservicegroupe=? WHERE idgroupe=?');
				return $req->execute(array($nom, $dateCreat, $service, $id));
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
				$total=$this->_bdd->query('SELECT COUNT(*) FROM groupe');

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
				$total=$this->_bdd->prepare('SELECT COUNT(*) FROM appartenir WHERE groupeappartenir=? AND statutappartnir=?');
				$total->execute(array($groupe,$APPARTENIR_ACTIF));

				$req=$this->_bdd->prepare('SELECT * FROM appartenir, juste WHERE groupeappartenir=:groupe AND statutappartenir=:statut AND justeappartenir=idjuste');
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
	}



 ?>