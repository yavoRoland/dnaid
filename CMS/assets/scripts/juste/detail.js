urlParams=["juste"]//tableau declaré dans utilitaires.js

function infosPasseur(){
	let juste= JSON.parse(getToken(passeur))
	if(!juste)
		return

	let container= document.getElementById('info-personnelle-container')

	container.innerHTML += `
	<div class="ligne-temporaire-inverse">
		<div>${juste.nomjuste} ${juste.prenomjuste}</div>
		<div>${juste.surnomjuste}</div>
		<div class="ligne-temporaire">
			</div>Année de nouvelle naissance</div>
			<div>${infoClaire(juste.anneenvelnaissjuste)}
		</div>
		${juste.photojuste
			?
			`
			<div>
				<img>
			</div>
			`
			:
			""
		}
	<div>
	<div class="ligne ligne-temporaire">
		<div class="info-bloc">
			<div class="info-libelle">Date de naissance</div>
			<div class="info-value">${infoClaire(juste.datenaissjuste)}</div>
		</div>

		<div class="info-bloc">
			<div class="info-libelle">Genre</div>
			<div class="info-value">${infoClaire(juste.genrejuste)}</div>
		</div>
	</div>

	<div class="ligne ligne-temporaire">
		<div class="info-bloc">
			<div class="info-libelle">Adresse</div>
			<div class="info-value">${infoClaire(juste.adressejuste)}</div>
		</div>

		<div class="info-bloc">
			<div class="info-libelle">Téléphone</div>
			<div class="info-value">${infoClaire(juste.phonejuste)}</div>
		</div>
	</div>

	<div class="ligne ligne-temporaire">
		<div class="info-bloc">
			<div class="info-libelle">Etat</div>
			<div class="info-value">${infoClaire(juste.etatjuste)}</div>
		</div>

		<div class="info-bloc">
			<div class="info-libelle">Grade</div>
			<div class="info-value">${infoClaire(juste.gradejuste)}</div>
		</div>

	</div>

	<div class="ligne ligne-temporaire">
		<div class="info-bloc">
			<div class="info-libelle">Situation matrimoniale</div>
			<div class="info-value">${infoClaire(juste.statutmatrijuste)}</div>
		</div>

		<div class="info-bloc">
			<div class="info-libelle">Ethnie</div>
			<div class="info-value">${infoClaire(juste.ethniejuste)}</div>
		</div>

	</div>


	`

}

function infosAssemblee(infos){
	let juste= getParameters(urlParams).juste
	var formData= new FormData()
	formData.append('code','J1-7')
	formData.append('juste',juste)
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code==requete_reussi){
			if(resultat.donnee.idassemble){
				document.getElementById('info-assemblee-part').classList.remove('invisible')
				let container= document.getElementById('info-assemblee-container')
				let assemblee=resultat.donnee
				container.innerHTML=`
					<div>${assemblee.nomassemble}</div>

					<div class="ligne-temporaire">
						<div info-libelle>Fonction du juste</div>
						<div>${infoClaire(assemblee.fonctionrattacher)}</div>
					</div>
					<div class="ligne-temporaire">
						<div info-libelle>Date de rattachement</div>
						<div>${infoClaire(assemblee.datedebutrattacher)}</div>
					</div>

					<div class="ligne ligne-temporaire">
						<div class="info-bloc">
							<div info-libelle>Pays</div>
							<div>${infoClaire(assemblee.paysassemble)}</div>
						</div>
						<div class="info-bloc">
							<div info-libelle>Region</div>
							<div>${infoClaire(assemblee.regionassemble)}</div>
						</div>
					</div>

					<div class="ligne ligne-temporaire">
						<div class="info-bloc">
							<div info-libelle>Ville</div>
							<div>${infoClaire(assemblee.villeassemble)}</div>
						</div>
						<div class="info-bloc">
							<div info-libelle>Quartier</div>
							<div>${infoClaire(assemblee.quartierassemble)}</div>
						</div>
					</div>
				`
			}
		}
	})
}

function infosGroupe(infos){
	let juste= getParameters(urlParams).juste
	var formData= new FormData()
	formData.append('code','J1-11')
	formData.append('juste',juste)
	formData.append('page','0')
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code==requete_reussi){
			document.getElementById('info-groupe-part').classList.remove('invisible')
			let container= document.getElementById("tableau-corps")

			resultat.donnee.forEach((elt,index)=>{
				if(index>5)
					return;
				let pariteLigne= index%2==0?"paire":"impaire"
				container.innerHTML +=`
					<div id="${elt.idgroupe}" class="ligne ${pariteLigne} ligne-temporaire" data-index="${index}">
						<div id="rang-bloc" class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Rang: </div>
							<div>${index}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Nom: </div>
							<div class="bloc-value">${infoClaire(elt.nomgroupe)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Prenom: </div>
							<div class="bloc-value">${infoClaire(elt.roleappartenir)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Téléphone: </div>
							<div class="bloc-value">${infoClaire(elt.datedebutappartenir)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Adresse: </div>
							<div class="bloc-value">${infoClaire(elt.datefinappartenir)}</div>
						</div>
						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Adresse: </div>
							<div class="bloc-value">${infoClaire(elt.datefinappartenir,"Toujours membre")}</div>
						</div>
					</div>

				`
			})
		}
	})

}



document.addEventListener('included',()=>{
	console.log("executionnnnn")
	infosPasseur()
	infosAssemblee()
	infosGroupe()
})