function activerEnregisterGroupe(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		let formData = new FormData()

		let infos = document.getElementsByTagName('input')
		let formulaire = document.getElementById('monformulaire')
		let feedBack = document.getElementById('feed-back')

		feedBack.innerHTML=""
		
		
		for(var info of infos){

			if(!formulaire.reportValidity())
				break;

			if(info.required && info.value && info.value.trim().length==0){
				info.value=null
				formulaire.reportValidity()
				break;
			}

			formData.append(info.getAttribute('name') , valeurClaire(info.value))
			
		}
		
		formData.append('code','G1-1')

		executeRequete(formData)
		.then(resultat=>{
			if(resultat.code==requete_reussi){
				formulaire.reset()
			}
			feedBack.innerHTML=resultat.message
		})
		.catch(error=>feedBack.innerHTML=msgErreurTechnique)
	})
}


document.addEventListener('included',function(){

	activerEnregisterGroupe()
})
