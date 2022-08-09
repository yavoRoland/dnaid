route="groupe"
urlParams=["groupe"]//tableau declaré dans utilitaires.js

function chargerService(page){
	let groupe=getInfoByPasseur()
	var formData = new FormData()
	formData.append('code','S1-5')
	formData.append('page',page)

	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code == requete_reussi){
			let serviceListe=document.getElementById('service-list')
			resultat.donnee.forEach((elt,index)=>{
				if(elt.idservice==groupe.idservicegroupe)
					document.getElementById('service').value=`${elt.idservice}-${elt.matservice} ${elt.nomservice}`
				serviceListe.innerHTML+=`<option value="${elt.idservice}-${elt.matservice} ${elt.nomservice}">`
			})

			if(parseInt(resultat.total.S_TOTAL) > (page * 10)){
				chargerService(page + 1)
			}
		}
	})
}

function chargerInfos(){
	let groupe=getInfoByPasseur()
	if(!groupe)
		return
	document.getElementById('nom').value = groupe.nomgroupe
	if(groupe.datecreatgroupe)
		document.getElementById('datecreat').value= groupe.datecreatgroupe
	document.getElementById('description').value= groupe.descriptiongroupe
}

function activerModifierGroupe(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		let groupe=getInfoByPasseur()
		let formulaire= document.getElementById('monformulaire')
		if(!formulaire.reportValidity())
			return
		let infos= document.getElementsByTagName('input')
		let exceptions=["menu-chp-rechercher","service"]
		let feedBack= document.getElementById('feed-back')
		let change = false
		let valide=true

		for(var info of infos){
			if(exceptions.find(elt=> elt==info.getAttribute("name")))
				continue

			info.value= info.value.trim()

			if(!formulaire.reportValidity()){
				valide = false
				break
			}

			formData.append(info.getAttribute('name') , info.value)

			if(info.value != groupe[`${info.getAttribute('name')}groupe`])
				change=true
		}

		if(!valide)
			return

		change=change || groupe.descriptiongroupe == valeurClaire(document.getElementById(description))
		change = change || parseInt(groupe.idservicegroupe) == parseInt(document.getElementById('service').value.split('-')[0])

		if(!change)
			return
		formData.append('description',valeurClaire(document.getElementById(description)))
		formData.append('service',document.getElementById('service').value.split('-')[0])
		formData.append('groupe',groupe.idgroupe)
		formData.append('code',"G1-2")
			
		executeRequete(formData)
		.then(resultat=>{
			if(resultat.code==requete_reussi){
				groupe.nomgroupe=document.getElementById('nom').value
				groupe.datecreatgroupe=document.getElementById('datecreat').value
				groupe.descriptiongroupe=valeurClaire(document.getElementById('description').value)
				groupe.idservicegroupe=document.getElementById('service').value.split('-')[0]
				saveToken(passeur,JSON.stringify(groupe))
			}
			feedBack.innerHTML=resultat.message
		})


	})
}


document.addEventListener('included',()=>{
	menuResponsiveActivation(route)
	chargerService(0)
	chargerInfos()
})