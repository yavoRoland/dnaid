<?php 	
	include_once('../dao/dao.php');

	class MetierSystem{
		private $_dao;
		public function __construct(){
			$this->_dao=New Dao();
		}


		//GESTION SERVICE
		public function nouveauService($nom,$date){
			try{
				$matricule='S-'.Utilitaire::codeGenerator(4,true);
				$resultat=$this->_dao->ajouterService($matricule,$nom,$date);
				if($resultat){
					return array(
						'resultat'=>true,
						'code'=>requete_reussi,
						'message'=>'Nouveau service enregistré',
						'donnee'=>$matricule
					);
				}else{
					return array(
						'resultat'=>false,
						'code'=>requete_echoue,
						'message'=>"Echec de l'enregistrement d'un nouveau service"
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

		
	}
/*
			try{
	
			}catch(Exception $e){
				return array(
					'resultat'=>false,
					'code'=>requete_echoue,
					'message'=>
				);
			}

*/
 ?>