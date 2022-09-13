route="assemblee"
urlParams=["assemblee"]//tableau declaré dans utilitaires.js

function init(){
	let assemblee=getInfoByPasseur()
	console.log(assemblee)
	if(assemblee)
		temoin=assemblee.idassemble
}

function activerLienModifier(){
	document.getElementById('lien-modifier-info').addEventListener('click',function(){
		window.location.href = `${server}assemblee/modifier/${getParameters(urlParams).assemblee}`
	})
}
function infosPasseur(){
	let assemblee=getInfoByPasseur()
	if(!assemblee)
		return
	let container= document.getElementById('info-generale-container')

	container.innerHTML+= `

		<div class="zone-tete">
			<div>${assemblee.nomassemble}</div>
			<div>${assemblee.matassemble}</div>
		</div>
		<div class="ligne ligne-temporaire">
			<div class="zone-bloc">
				<div class="info-libelle">Pays</div>
				<div class="info-value">${infoClaire(assemblee.paysassemble)}</div>
			</div>
			<div class="zone-bloc">
				<div class="info-libelle">Region</div>
				<div class="info-value">${infoClaire(assemblee.regionassemble)}</div>
			</div>
		</div>
		<div class="ligne ligne-temporaire">
			<div class="zone-bloc">
				<div class="info-libelle">Ville</div>
				<div class="info-value">${infoClaire(assemblee.villeassemble)}</div>
			</div>
			<div class="zone-bloc">
				<div class="info-libelle">Departement</div>
				<div class="info-value">${infoClaire(assemblee.departassemble)}</div>
			</div>
		</div>

		<div class="ligne ligne-temporaire">
			<div class="zone-bloc">
				<div class="info-libelle">Quartier</div>
				<div class="info-value">${infoClaire(assemblee.quartierassemble)}</div>
			</div>
			<div class="zone-bloc">
				<div class="info-libelle">Commune</div>
				<div class="info-value">${infoClaire(assemblee.communeassemble)}</div>
			</div>
		</div>
		<div>
			${assemblee.descrptionassemble??""}
		</div>
		
	`
}

function infosJuste(){
	let assemblee=getParameters(urlParams).assemblee
	var formData= new FormData()
	formData.append('code','A2-7')
	formData.append('assemblee',assemblee)
	formData.append('page',0)
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code==requete_reussi){
			document.getElementById('info-juste-part').classList.remove('invisible')
			let container=document.getElementById('tableau-corps')
			resultat.donnee.forEach((elt,index)=>{
				let pariteLigne= index % 2==0? "paire": "impaire"
				container.innerHTML+=`
					<div id="${elt.idjuste}" class="ligne ${pariteLigne} ligne-temporaire ligne-juste" data-index="${index}">
						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Nom & Prenoms:</div>
							<div class="bloc-value">${infoClaire(elt.nomjuste)} ${infoClaire(elt.prenomjuste)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Fonction:</div>
							<div class="bloc-value">${infoClaire(elt.fonctionrattacher)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Téléphone:</div>
							<div class="bloc-value">${infoClaire(elt.phonejuste)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Date de rattachement:</div>
							<div class="bloc-value">${infoClaire(elt.datedebutrattacher)}</div>
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
	infosJuste()
	activerLienModifier()
	menuResponsiveActivation(route)
	activeLienList(getParameters(urlParams).assemblee)
})
init()
includeHTML()
