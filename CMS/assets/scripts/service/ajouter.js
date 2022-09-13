route="service"
function activerEnregistrerService(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		let formData= new FormData()
		let formulaire= document.getElementById('monformulaire')
		let feedBack= document.getElementById('feed-back')
		feedBack.innerHTML= ''
		let nom=document.getElementById('nom')
		nom.value = valeurClaire(nom.value)
		if(formulaire.reportValidity()){
			
			formData.append('nom', valeurClaireForApi(nom.value))
			formData.append('datecreat',document.getElementById('datecreat').value)
			formData.append('code','S1-1')

			executeRequete(formData)
			.then(resultat=>{
				if(resultat.code==requete_reussi){
					formulaire.reset()
				}
				feedBack.innerHTML=resultat.message
			})
			.catch(error=>feedBack.innerHTML=msgErreurTechnique)
		}

		
	})
}

document.addEventListener('included',function(){
	activerEnregistrerService()
	menuResponsiveActivation(route)
})
includeHTML()
