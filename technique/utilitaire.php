<?php 	
	include_once('CONSTANTE.php');
	class Utilitaire{

		public static function guidGenerator($data = null){
			// Generate 16 bytes (128 bits) of random data or use the data passed into the function.
			$data = $data ?? random_bytes(16);
			assert(strlen($data) == 16);

		    // Set version to 0100
			$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		    // Set bits 6-7 to 10
			$data[8] = chr(ord($data[8]) & 0x3f | 0x80);

		    // Output the 36 character UUID.
			return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
		}

		public static function codeGenerator($taille,$maj=false){

			$code= Utilitaire::guidGenerator();
			$code=str_replace('-', "", $code);
			$code=substr($code, 0,$taille);
			if($maj)
				return strtoupper($code);

			return $code;
		}

		public static function textTrim($text){
			if($text)
				return trim($text);
			else
				return '';
		}

		public static function textCorrect($text){
			if(!$text)
				return false;
			if(strlen(trim($text))==0)
				return false;

			return true;
		}

		public static function enregistrerFichier($fichier, $extension, $repertoire){
			try{
				$nom="";
				do{
					$nom=sha1(uniqid().time()).'.'.$extension;
				}while(file_exists($repertoire.$nom));
				move_uploaded_file($fichier['tmp_name'], $repertoire.$nom);
				return $nom;
			}catch(Exception $e){
				return false;
			}
		}

		public static function supprimerFichier($nomFichier,$repertoire){
			try{
				if(!file_exists($repertoire.$nomFichier))
					return false;

				unlink($repertoire.$nomFichier);
				return true;
			}catch(Exception $e){
				return false;
			}
		}

		public static function ExecuterScript($script,$parametres){
			$params='';
			foreach ($parametres as $param) {
				//$params=$params." \"".utf8_encode($param)."\"";
				$params.=$param;
			}
			$cmd=realpath($script).''.$params;
			if (substr(php_uname(), 0, 7) == "Windows"){
				pclose(popen("start /B ". $cmd, "r")); 
				//print_r(exec($cmd));
			}
			else {
				$output=array();
				$reslt;
				exec($cmd . " > /dev/null &",$output,$reslt); 
				
			}
		}

		public static function log($message, $dossier){
			try{
				$annee=(new DateTime())->format('Y');
				$mois=(new DateTime())->format('m');
				$chemin=$dossier.'/'.$annee.'/'.$mois;
				if(!file_exists($chemin)){
					mkdir($chemin,0777,true);
				}
				$fichier=(new DateTime())->format('d-m-Y');
				$temp=(new DateTime())->format('d-m-Y H:i');
				file_put_contents($chemin.'/'.$fichier.'.log', $temp.' >>>> '.$message ."\r\n",FILE_APPEND);
			}catch(Exception $e){
				return false;
			}
		}

		public static function epuration($text){
			$aRemplacer=array('â','à','ä','é','è','ê','ë','ô','ö','ù','û','ü','î','ï','ç','-','_');
			$remplacant=array('a','a','a','e','e','e','e','o','o','u','u','u','i','i','c',' ',' ');
			return str_replace($aRemplacer ,$remplacant, $text);
		}
	}



 ?>