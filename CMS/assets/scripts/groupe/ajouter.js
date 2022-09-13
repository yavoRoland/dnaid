route="groupe"



function chargerService(page){
	let service=getInfoByPasseur()
	if(service && service.matservice)
		document.getElementById('service').value=`${service.matservice} ${service.nomservice}`

	var formData = new FormData()
	formData.append('code','S1-5')
	formData.append('page',page)

	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code == requete_reussi){
			let serviceListe=document.getElementById('service-list')
			resultat.donnee.forEach((elt,index)=>{
				serviceListe.innerHTML+=`<option value="${elt.matservice} ${elt.nomservice}">`
			})

			if(parseInt(resultat.total.S_TOTAL) > ((page+1) * qte_standard)){
				chargerService(page + 1)
			}
		}
	})
}
function activerEnregisterGroupe(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		let formData = new FormData()

		let infos = document.getElementsByTagName('input')
		let formulaire = document.getElementById('monformulaire')
		let feedBack = document.getElementById('feed-back')
		let exceptions=["menu-chp-rechercher","service"]

		feedBack.innerHTML=""
		
		let valide=true
		for(var info of infos){
			if(exceptions.find(elt=> elt==info.getAttribute("name")))
				continue

			info.value=valeurClaire(info.value)

			if(!formulaire.reportValidity()){
				valide=false
				break;
			}

			

			formData.append(info.getAttribute('name') , valeurClaireForApi(info.value))
			
		}
		if(!valide)
			return

		formData.append('description',valeurClaireForApi(document.getElementById("description").value))
		formData.append('service',document.getElementById('service').value.split(' ')[0])
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
	menuResponsiveActivation(route)
	chargerService("0")
})
includeHTML()
