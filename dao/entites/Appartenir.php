<?php 	
	class Appartenir{
		private $_bdd;
		public function __construct($bdd){
			$this->_bdd=$bdd;
		}

		public function ajouter($juste,$groupe,$role,$statut,$dateDebut){
			try{
				$req=$this->_bdd->prepare('INSERT INTO appartenir(justeappartenir, groupeappartenir, roleappartenir, statutappartenir, datedebutappartenir) VALUES(?,?,?,?,?)');
				return $req->execute(array($juste,$groupe,$role,$statut,$dateDebut));
			}catch(Exception $e){
				return false;
			}
		}

		public function modifier($juste,$groupe,$role,$statut,$dateDebut,$dateFin,$description,$id){
			try{
				
				$req=$this->_bdd->prepare('UPDATE appartenir SET justeappartenir=?, groupeappartenir=?, roleappartenir=?, statutappartenir=?, datedebutappartenir=?, datefinappartenir=?, descriptionappartenir=? WHERE idappartenir=?');
				return $req->execute(array($juste,$groupe,$role,$statut,$dateDebut,$dateFin,$description,$id));
			}catch(Exception $e){
				
				return false;
			}
		}

		public function rechercher($id){
			try{
				$req=$this->_bdd->prepare('SELECT * FROM appartenir WHERE idappartenir=?');
				$req->execute(array($id));
				return $req;
			}catch(Exception $e){
				return false;
			}
		}
		public function rechercherPourUnicite($juste,$groupe,$statut){
			try{
				
				$req=$this->_bdd->prepare('SELECT * FROM appartenir WHERE justeappartenir=? AND groupeappartenir=? AND statutappartenir=?');
				$req->execute(array($juste,$groupe,$statut));
				return $req;
			}catch(Exception $e){
				return false;
			}
		}

		public function supprimer($id){
			try{
				$req=$this->_bdd->prepare('DELETE FROM appartenir WHERE idappartenir=?');
				return $req->execute(array($id));
			}catch(Exception $e){
				return false;
			}
		}

	}
 ?>