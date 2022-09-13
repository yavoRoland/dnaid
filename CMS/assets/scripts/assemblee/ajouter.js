route="assemblee"
function activerEnregisterAssemblee(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		let formData = new FormData()

		let infos = document.getElementsByTagName('input')
		let formulaire = document.getElementById('monformulaire')
		let feedBack = document.getElementById('feed-back')

		feedBack.innerHTML=""
		let exceptions=["menu-chp-rechercher"]
		
		for(var info of infos){
			if(exceptions.find(elt=> elt==info.getAttribute("name")))
				continue
			info.value= valeurClaire(info.value)
			
			if(!formulaire.reportValidity())
				break;


			formData.append(info.getAttribute('name') , valeurClaireForApi(info.value))
			
		}
		formData.append('description', valeurClaireForApi(document.getElementById('description').value))
		formData.append('code','A2-1')

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
	activerEnregisterAssemblee()
	menuResponsiveActivation(route)
})
includeHTML()
