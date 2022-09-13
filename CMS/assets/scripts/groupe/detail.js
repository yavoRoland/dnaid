route="groupe"
urlParams=["groupe"]//tableau declaré dans utilitaire.js


function activerLienModifier(){
	document.getElementById('lien-modifier-info').addEventListener('click',function(){
		window.location.href = `${server}groupe/modifier/${getParameters(urlParams).groupe}`
	})
}
function infosPasseur(){
	let groupe=getInfoByPasseur()

	if(!groupe)
		return
	let container= document.getElementById('info-generale-container')
	container.innerHTML+=`
		<div class="zone-tete">
			<div>${groupe.nomgroupe}</div>
			<div>${groupe.matgroupe}</div>
		</div>
		<div class="ligne ligne-temporaire">
			<div class="zone-bloc">
				<div class="info-libelle">Date de création</div>
				<div class="info-value">${infoClaire(groupe.datecreatgroupe)}</div>
			</div>
		</div>

		${
			groupe.descriptiongroupe?`
			<div class="zone-bloc">
				<div class="info-libelle">Description</div>
				<div class="info-value">${infoClaire(groupe.descriptiongroupe)}</div>
			</div>`
			:''
		}
	`
}

function infosService(){
	let groupe=getInfoByPasseur()

	if(!groupe)
		return
	var formData= new FormData()
	formData.append('code','S1-3')
	formData.append('service',groupe.idservicegroupe)
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code==requete_reussi){
			let container = document.getElementById('info-service-container')
			let service = resultat.donnee

			container.innerHTML+=`
				<div class="zone-tete">
					<div>${service.nomservice}</div>
				</div>

				<div class="ligne ligne-temporaire">
					<div class="zone-bloc">
						<div class="info-libelle">Matricule</div>
						<div class="info-value">${infoClaire(service.matservice)}</div>
					</div>
					<div class="zone-bloc">
						<div class="info-libelle">Date de création</div>
						<div class="info-value">${infoClaire(service.datecreatservice)}</div>
					</div>
				</div>

			`
		}
	})
}


function infosJuste(){
	let groupe= getParameters(urlParams).groupe 
	var formData = new FormData()
	formData.append('code','G1-6')
	formData.append('groupe',groupe)
	formData.append('page',0)
	executeRequete(formData)
	.then(resultat=>{
		console.log(resultat.code)
		if(resultat.code==requete_reussi){
			document.getElementById('info-juste-part').classList.remove('invisible')
			let container= document.getElementById('tableau-corps')
			resultat.donnee.forEach((elt,index)=>{
				let pariteLigne=index % 2==0?"paire":"impaire"
				container.innerHTML+=`
					<div id="${elt.idjuste}" class="ligne ${pariteLigne} ligne-temporaire ligne-juste" data-index="${index}">
						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Nom & Prenoms:</div>
							<div class="bloc-value">${infoClaire(elt.nomjuste)} ${infoClaire(elt.prenomjuste)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Rôle:</div>
							<div class="bloc-value">${infoClaire(elt.roleappartenir)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Téléphone:</div>
							<div class="bloc-value">${infoClaire(elt.phonejuste)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Date de adhésion:</div>
							<div class="bloc-value">${infoClaire(elt.datedebutappartenir)}</div>
						</div>
					</div>
				`
			})
			let lignes=document.getElementsByClassName('ligne-juste')
			for(var ligne of lignes){
				ligne.addEventListener('click',function(){
					saveToken(passeur,JSON.stringify(resultat.donnee[this.getAttribute('data-index')]))
					setTimeout(()=>{
						window.location.href=`${server}juste/detail/${this.getAttribute('id')}`
					},300)
				})
			}
		}
	})
}


document.addEventListener('included',()=>{
	infosPasseur()
	infosService()
	infosJuste()
	menuResponsiveActivation(route)
	activerLienModifier()
	activeLienList(getParameters(urlParams).groupe)
})
includeHTML()












