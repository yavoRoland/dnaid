<?php 	
	class Assemblee{

		private $_bdd;
		public function __construct($bdd){
			$this->_bdd=$bdd;
		}

		public function ajouter($matricule, $nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $fullText){
			try{
				$req=$this->_bdd->prepare('INSERT INTO assemblee(matassemble, nomassemble, paysassemble, regionassemble, departassemble, villeassemble, communeassemble, quartierassemble, descriptionassemble, fulltextassemble) VALUES(?,?,?,?,?,?,?,?,?,?)');
				return $req->execute(array($matricule, $nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $fullText));
			}catch(Exception $e){
				return false;
			}
		}

		public function modifer($nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $fullText, $id){
			try{
				$req=$this->_bdd->prepare('UPDATE assemblee SET nomassemble=?, paysassemble=?, regionassemble=?, departassemble=?, villeassemble=?, communeassemble=?, quartierassemble=?, descriptionassemble=?, fulltextassemble=? WHERE idassemble=?');
				return $req->execute(array($nom, $pays, $region, $departement, $ville, $commune, $quartier, $description, $fullText, $id));
			}catch(Exception $e){
				return false;
			}
		}

		public function rechercher($id){
			try{
				$req=$this->_bdd->prepare('SELECT * FROM assemblee WHERE idassemble=?');
				$req->execute(array($id));
				return $req;
			}catch(Exception $e){
				return false;
			}
		}

		public function rechercherParMatricule($matricule){
			try{
				$req=$this->_bdd->prepare('SELECT * FROM assemblee WHERE matassemble=?');
				$req->execute(array($matricule));
				return $req;
			}catch(Exception $e){
				return false;
			}
		}

		public function rechercherParFullText($text,$page,$quantite){
			try{
				$total=$this->_bdd->prepare('SELECT COUNT(*) AS A_TOTAL FROM assemblee WHERE MATCH (fulltextassemble) AGAINST(:text IN BOOLEAN MODE)');
				$total->bindValue(':text',$text,PDO::PARAM_STR);
				$total->execute();


				$req=$this->_bdd->prepare('SELECT *, MATCH (fulltextassemble) AGAINST(:text IN BOOLEAN MODE) AS CPT FROM assemblee WHERE MATCH (fulltextassemble) AGAINST(:text IN BOOLEAN MODE) ORDER BY CPT DESC LIMIT :page,:quantite');
				$req->bindValue(':text',$text,PDO::PARAM_STR);
				$req->bindValue(':page',($page*$quantite),PDO::PARAM_INT);
				$req->bindValue(':quantite',$quantite,PDO::PARAM_INT);

				$req->execute();

				return array($req,$total);
			}catch(Exception $e){
				return false;
			}
		}

		public function supprimer($id){
			try{
				$req=$this->_bdd->prepare('DELETE FROM assemblee WHERE idassemble=?');
				return $req->execute(array($id));
			}catch(Exception $e){
				return false;
			}
		}

		public function liste($page, $quantite){
			try{
				$total=$this->_bdd->query('SELECT COUNT(*) AS A_TOTAL FROM assemblee');

				$req=$this->_bdd->prepare('SELECT * FROM assemblee ORDER BY nomassemble ASC LIMIT :page,:quantite');
				$req->bindValue(':page',($page*$quantite), PDO::PARAM_INT);
				$req->bindValue(':quantite',$quantite, PDO::PARAM_INT);
				$req->execute();

				return array($req, $total);
			}catch(Exception $e){
				return false;
			}
		}

		public function listeJuste($assemble, $page, $quantite){
			try{
				$RATTACHER_ACTIF=1;
				$total=$this->_bdd->prepare('SELECT COUNT(*) AS J_TOTAL FROM rattacher WHERE assemblerattacher=? AND statutjusterattacher=?');
				$total->execute(array($assemble,$RATTACHER_ACTIF));

				$req=$this->_bdd->prepare('SELECT * FROM rattacher, juste WHERE assemblerattacher=:assemble AND statutrattacher=:statut AND justerattacher=idjuste ORDER BY nomjuste, prenomjuste ASC LIMIT :page,:quantite');
				$req->bindValue(':statut',$RATTACHER_ACTIF, PDO::PARAM_INT);
				$req->bindValue(':assemble',$assemble, PDO::PARAM_INT);
				$req->bindValue(':page',($page*$quantite), PDO::PARAM_INT);
				$req->bindValue(':quantite',$quantite, PDO::PARAM_INT);
				$req->execute();

				return array($req, $total);
	        }catch(Exception $e){
	            return false;
	        }
		}

		public function listeTotalJuste($assemble){
			try{
				$RATTACHER_ACTIF=1;
				$req=$this->_bdd->prepare('SELECT * FROM rattacher,juste WHERE assemblerattacher=? AND statutrattacher=? AND justerattacher=idjuste');
				$req->execute(array($assemble,$RATTACHER_ACTIF));
				return $req;
			}catch(Exception $e){

			}
		}
	}


 ?>