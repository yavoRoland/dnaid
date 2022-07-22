<?php 

	class JwtManager{
		public function __construct(){

		}
		private function base64url_encode($str){
			return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
		}

		public function generateJwt($headers, $payload, $secret="dnaidadj2@22"){
			$headers_encoded = $this->base64url_encode(json_encode($headers));
			$payload_encoded = $this->base64url_encode(json_encode($payload));

			$signature = hash_hmac('SHA256',"$headers_encoded.$payload_encoded", $secret, true);
			$signature_encoded = $this->base64url_encode($signature);
			$jwt= "$headers_encoded.$payload_encoded.$signature_encoded";

			return $jwt;
		}


		public function checkJwt($jwt, $secret="dnaidadj2@22"){
			if(is_null($jwt))
				return array("message"=>"Token invalid","checked"=>false, "code"=>-8);
			// split the jwt
			$tokenParts = explode('.', trim($jwt));
			if(sizeof($tokenParts)!=3)
				return array("message"=>"Token invalid","checked"=>false, "code"=>-8);

			$header = base64_decode($tokenParts[0]);
			$payload = base64_decode($tokenParts[1]);
			$signature_provided = str_replace('"','',$tokenParts[2]);


			// check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
			$expiration = json_decode($payload)->exp;
			$is_token_expired = ($expiration - time()) < 0;


			// build a signature based on the header and payload using the secret
			$base64_url_header = $this->base64url_encode($header);
			$base64_url_payload = $this->base64url_encode($payload);
			$signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
			$base64_url_signature = $this->base64url_encode($signature);
			
			// verify it matches the signature provided in the jwt
			$is_signature_valid = ($base64_url_signature === $signature_provided);
			if($is_token_expired){
				return array("message"=>"Token expiré", "checked"=>false, "code"=>-7);
			}
			if(!$is_signature_valid){
				return array("message"=>"Signature token invalide","checked"=>false, "code"=>-6);
			}
			return array("message"=>"Authentification valide","checked"=>true);
		}

		public function getJwtPayload($jwt){
			$tokenParts = explode('.', $jwt);
			return base64_decode($tokenParts[1]);
		}

		public function getJwtEncodedPayload($jwt){
			return explode('.',$jwt)[1];
		}

		private function getAuthorizationHeader(){
			$headers=null;

			if(isset($_SERVER['Authorization'])){
				$headers=trim($_SERVER['Authorization']);
			}else if(isset($_SERVER['HTTP_AUTHORIZATION'])){//NGinx or CGI
				$headers=trim($_SERVER['Authorization']);
			}else if(function_exists('apache_request_headers')){
				$requestHeaders = apache_request_headers();
				//Server side fix for old android versions (a nice side-effect of this fix  means we don't care about capitalization for authorization)
				$requestHeaders = array_combine(array_map('ucwords',array_keys($requestHeaders)), array_values($requestHeaders));

				if(isset($requestHeaders["Authorization"])){
					$headers=trim($requestHeaders["Authorization"]);
				}
			}
			return $headers;
		}

		public function getBearerToken(){
			$headers = $this->getAuthorizationHeader();

			//HEADER: Get the access token from the header
			if(!empty($headers)){
				if(preg_match('/Bearer\s(\S+)/', $headers, $matches)){
					return $matches[1];
				}
			}
			return null;
		}

		public function checkSession(){
			$token=$this->getBearerToken();
			if(is_null($token))
				return false;

			return $this->checkJwt($token);
		}
	}


 ?>