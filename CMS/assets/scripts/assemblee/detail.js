route="assemblee"
urlParams=["assemblee"]//tableau declaré dans utilitaires.js

function infosPasseur(){
	let assemblee=false
	try{
		assemblee = JSON.parse(getToken(passeur))
	}catch(error){

	}
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


document.addEventListener('included',()=>{
	console.log("executionnnnn")
	infosPasseur()
})