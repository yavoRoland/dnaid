route="assemblee"
urlParams=["assemblee"]//tableau declaré dans utilitaires.js
var conservateur=null

function chargerInfos(){
	let assemblee = getInfoByPasseur()
	if(!assemblee)
		return

	for(var property in assemblee){
		let elt=document.getElementById(`${property.replace('assemble','')}`)
		if(elt)
			elt.value= assemblee[property]
	}
}
function activerModifierAssemblee(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		let assemblee=getInfoByPasseur()
		conservateur = assemblee
		let formulaire= document.getElementById('monformulaire')

		let infos=document.getElementsByTagName('input')
		let exceptions=["menu-chp-rechercher"]
		let feedBack = document.getElementById('feed-back')
		feedBack.innerHTML=""
			
		let valide = true
		let change = false
		var formData = new FormData()
		for(var info of infos){
			if(exceptions.find(elt=> elt==info.getAttribute("name")))
				continue

			info.value=valeurClaire(info.value)

			if(!formulaire.reportValidity()){
				valide=false
				break;
			}

			if(info.value != assemblee[`${info.getAttribute('name')}assemble`])
				change=true

			formData.append(info.getAttribute('name') , info.value)
			conservateur[`${info.getAttribute('name')}assemble`]
		}

		if(!change)
			return

		if(!valide)
			return
		formData.append('code','A2-2')
		formData.append('assemblee',assemblee.idassemble)
		executeRequete(formData)
		.then(resultat=>{
			if(resultat.code==requete_reussi){
				saveToken(passeur,JSON.stringify(conservateur))
			}
			feedBack.innerHTML=resultat.message
		})
	})
}

document.addEventListener('included',function(){
	chargerInfos()
	menuResponsiveActivation(route)
})