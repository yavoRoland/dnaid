
clearSession()


var mdpVisible= false;
function valeurClaire(valeur){
	if(!(valeur==null))
		return valeur.trim()
	else
		return valeur
}

document.getElementById('visibilite-mdp').addEventListener('click',function(){
	let eye= document.getElementById('eye')
	let eyeSlash= document.getElementById('eye-slash')
	let mdp= document.getElementById('mdp')
	if(mdpVisible){
		mdp.type="password"
		eye.classList.remove("invisible")
		eyeSlash.classList.add('invisible')
	}else{
		mdp.type="text"
		eye.classList.add("invisible")
		eyeSlash.classList.remove('invisible')
	}

	mdpVisible = !mdpVisible
})

document.getElementById('btn-valider').addEventListener('click',function(event){
	event.preventDefault()
	let formulaire= document.getElementById('monformulaire')
	var formData=new FormData()

	let infos= document.getElementsByTagName('input')
	let feedBack=document.getElementById('feed-back')

	feedBack.innerHTML=''
	if(formulaire.reportValidity()){
		for(i=0; i<infos.length; i++){
			if(infos[i].required && infos[i].value && infos[i].value.trim().length==0){
				infos[i].value=null
				formulaire.reportValidity()
				break;
			}
			formData.append(infos[i].getAttribute('name') , valeurClaire(infos[i].value))
		}

		formData.append('code','J1-5')
		
		
		executeRequete(formData,false)
		.then((resultat)=>{
			feedBack.innerHTML=resultat.message
			if(resultat.code==requete_reussi){
				saveToken(jwtToken,JSON.stringify(resultat.donnee.jwt))
				saveToken(userInfoToken,JSON.stringify(resultat.donnee.user))
				setTimeout(()=>{
					location.replace(homePage)
				},300)
				
			}
		})
		.catch(error=>console.log(error))
	}

	
		
	
})