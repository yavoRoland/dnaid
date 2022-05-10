<?php 
	class Rattacher{
		private $_bdd;
		public function __construct($bdd){
			$this->_bdd=$bdd;
		}

		public function ajouter($juste, $assemble, $fonction, $statut, $dateDebut){
			try{
				$req=$this->_bdd->prepare('INSERT INTO rattacher(justerattacher, assemblerattacher, fonctionrattacher, statutrattacher, datedebutrattacher) VALUES(?,?,?,?,?)');
				return $req->execute(array($juste, $assemble, $fonction, $statut, $dateDebut));
			}catch(Exception $e){
				return false;
			}
		}

		public function modifier($juste, $assemble, $fonction, $statut, $dateDebut, $dateFin, $description, $id){
			try{
				$req=$this->_bdd->prepare('UPDATE rattacher SET justerattacher=?, assemblerattacher=?, fonctionrattacher=?, statutrattacher=?, datedebutrattacher=?, datefinrattacher=?, descriptionrattacher=? WHERE idrattacher=?');
				return $req->execute(array($juste, $assemble, $fonction, $statut, $dateDebut, $dateFin, $description, $id));
			}catch(Exception $e){
				return false;
			}
		}

		public function supprimer($id){
			try{
				$req=$this->_bdd->prepare('DELETE FROM rattacher WHERE idrattacher=?');
				return $req->execute(array($id));
			}catch(Exception $e){
				return false;
			}
		}
	}

 ?>