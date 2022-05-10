<?php 
 class Juste{
 	private $_bdd;
 	public function __construct($bdd){
 		$this->_bdd=$bdd;
 	}


 	public function ajouter($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $photo, $fullText){
 		try{
 			$req=$this->_bdd->prepare('INSERT INTO juste(nomjuste, prenomjuste, surnomjuste, datenaissjuste, genrejuste, etatjuste, adressejuste, phonejuste, gradejuste, anneenvelnaisjuste, professionjuste, statutmatrijuste, ethniejuste, photojuste, fulltextjuste) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
 			return $req->execute(array($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $photo, $fullText));
 		}catch(Exception $e){

 		}
 	}

    public function modifier($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $photo, $id, $fullText){
        try{
            $req=$this->_bdd->prepare('UPDATE juste SET nomjuste=?, prenomjuste=?, surnomjuste=?, datenaissjuste=?, genrejuste=?, etatjuste=?, adressejuste=?, phonejuste=?, gradejuste=?, anneenvelnaisjuste=?, professionjuste=?, statutmatrijuste=?, ethniejuste=?, phonejuste=?, fulltextjuste=? WHERE idjuste=?');
            return $req->execute(array($nom, $prenom, $surnom, $datenaiss, $genre, $etat, $adresse, $phone, $grade, $nvelNais, $profession, $statutMatri, $ethnie, $photo, $fullText, $id));
        }catch(Exception $e){
            return false;
        }
    }


    public function rechercher($id){
        try{
            $RATTACHER_ACTIF=1;
            $req=$this->_bdd->prepare('SELECT * FROM juste, rattacher, assemblee WHERE idjuste=? AND justerattacher=idjuste AND assemblerattacher=idassemble AND statutjusterattacher=?');
            $req->execute(array($id, $RATTACHER_ACTIF));
            return $req;
        }catch(Exception $e){
            return false;
        }
    }

    public function supprimer($id){
        try{
            $req=$this->_bdd->prepare('DELETE FROM juste WHERE idjuste=?');
            return $req->execute(array($id));
        }catch(Exception $e){
            return false;
        }
    }

    public function liste($page,$quantite){
        try{
            $total=$this->_bdd->query('SELECT COUNT(*) FROM juste');

            $req=$this->_bdd->prepare('SELECT * FROM juste ORDER BY nomjuste, prenomjuste ASC LIMIT :page,:quantite');
            $req->bindValue(':page',($page*$quantite), PDO::PARAM_INT);
            $req->bindValue(':quantite',$quantite, PDO::PARAM_INT);
            $req->execute();

            return array($req, $total);
        }catch(Exception $e){
            return false;
        }
    }
    //donne la liste de tous les groupes auquels appartient un juste
    public function listeGroupe($juste,$page,$quantite){
        try{
            $APPARTENIR_ACTIF=1;
            $total=$this->_bdd->prepare('SELECT COUNT(*) FROM appartenir WHERE justeappartenir=? AND statutappartnir=?');
            $total->execute(array($juste,$APPARTENIR_ACTIF));

            $req=$this->_bdd->prepare('SELECT * FROM appartenir, groupe WHERE justeappartenir=:juste AND statutappartnir=:statut AND groupeappartenir=idgroupe ORDER BY nomgroupe ASC LIMIT:page,:quantite');
            $req->bindValue(':statut',$APPARTENIR_ACTIF,PDO::PARAM_INT);
            $req->bindValue(':juste',$juste,PDO::PARAM_INT);
            $req->bindValue(':page',($page*$quantite), PDO::PARAM_INT);
            $req->bindValue(':quantite',$quantite, PDO::PARAM_INT);
            $req->execute();

            return array($req, $total);
        }catch(Exception $e){
            return false;
        }
    }
    //donne la liste de toutes les assemblées auxquelles a appartenu un juste y compris son assemblée actuelle
    public function listeAssemble($juste, $page, $quantite){
        try{
            $total=$this->_bdd->prepare('SELECT COUNT(*) FROM rattacher WHERE justerattacher=?');
            $total->execute(array($juste));


            $req=$this->_bdd->prepare('SELECT * FROM rattacher, assemblee WHERE justerattacher=:juste AND assemblerattacher=idassemble ORDER BY datedebutrattacher DESC LIMIT :page,:quantite');
            
            $req->bindValue(':juste',$juste,PDO::PARAM_INT);
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