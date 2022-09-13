route="service"
urlParams=["service"]//tableau declaré dans utilitaires.js


function activerLienModifier(){
	document.getElementById('lien-modifier-info').addEventListener('click',function(){
		window.location.href = `${server}service/modifier/${getParameters(urlParams).service}`
	})
}
function infosPasseur(){
	let service = getInfoByPasseur()
	
	if(!service)
		return
	let container= document.getElementById('info-generale-container')

	container.innerHTML+=`
		<div class="zone-tete">
			<div>${service.nomservice}</div>
			<div>${service.matservice}</div>
		</div>
		<div class="ligne ligne-temporaire">
			<div class="zone-bloc">
				<div class="info-libelle">Date de création</div>
				<div class="info-value">${infoClaire(service.datecreatservice)}</div>
			</div>
		</div>
	`
}


function infosGroupe(){
	let service = getParameters(urlParams).service
	var formData = new FormData()
	formData.append('code','S1-6')
	formData.append('service',service)
	formData.append('page',0)
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code== requete_reussi){
			document.getElementById('info-groupe-part').classList.remove('invisible')
			let container=document.getElementById('tableau-corps')
			resultat.donnee.forEach((elt,index)=>{
				let pariteLigne= index % 2==0? "paire": "impaire"
				container.innerHTML+=`
					<div id="${elt.idgroupe}" class="ligne ${pariteLigne} ligne-temporaire ligne-groupe" data-index="${index}">
						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Matricule: </div>
							<div class="bloc-value">${infoClaire(elt.matgroupe)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Nom: </div>
							<div class="bloc-value">${infoClaire(elt.nomgroupe)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Date de création: </div>
							<div class="bloc-value">${infoClaire(elt.datecreatgroupe)}</div>
						</div>
					</div>
				`
			})

			let lignes=document.getElementsByClassName('ligne-groupe')
			for(var ligne of lignes){
				ligne.addEventListener('click',function(){
					saveToken(passeur,JSON.stringify(resultat.donnee[this.getAttribute('data-index')]))
					setTimeout(()=>{
						window.location.href=`${server}groupe/detail/${this.getAttribute('id')}`
					},300)
				})
			}
		}
	})
}

document.addEventListener('included',()=>{
	infosPasseur()
	infosGroupe()
	menuResponsiveActivation(route)
	activerLienModifier()
	activeLienList(getParameters(urlParams).service)
})
includeHTML()
