route="service"
urlParams=["service"]//tableau declaré dans utilitaires.js

function chargerInfos(){
	let service = getInfoByPasseur()
	if(!service)
		return
	document.getElementById('nom').value=service.nomservice
	if(service.datecreatservice)
		document.getElementById('datecreat').value=service.datecreatservice
}

function activerModifierService(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		let service=getInfoByPasseur()
		let formulaire=document.getElementById('monformulaire')
		
		let feedBack=document.getElementById('feed-back')
		feedBack.innerHTML=''

		let nom=document.getElementById('nom')
		let dateCreat= document.getElementById('datecreat')
		
		nom.value=valeurClaire(nom.value)
		if(!formulaire.reportValidity())
			return
		if(valeurClaire(nom.value)== service.nomservice && service.datecreatservice== dateCreat.value){//pas de modification
			return
		}

		var formData = new FormData()
		formData.append('nom', valeurClaireForApi(nom.value))
		formData.append('datecreat',dateCreat.value)
		formData.append('service',service.idservice)
		formData.append('code',"S1-2")
		executeRequete(formData)
		.then(resultat=>{
			if(resultat.code == requete_reussi){
				service.nom= nom.value
				service.datecreatservice= dateCreat.value
				saveToken(passeur,JSON.stringify(service))
			}
			feedBack.innerHTML=resultat.message
		})
	})
}





document.addEventListener('included',()=>{
	menuResponsiveActivation(route)
	chargerInfos()
	activerModifierService()
})
includeHTML()
