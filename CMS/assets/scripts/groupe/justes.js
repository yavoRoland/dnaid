route="groupe"

urlParams=["page","groupe"]

function listerJuste(){
	let page=getParameters(urlParams).page
	let groupe=getParameters(urlParams).groupe
	var formData= new FormData()
	formData.append('code','G1-6')
	formData.append('groupe',groupe)
	formData.append('page',page-1)
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code==liste_vide){
			document.getElementById('message-vide').classList.remove('invisible')
		}else if(resultat.code==requete_reussi){
			let lines='';
			resultat.donnee.forEach((elt,index)=>{
				let pariteLigne= index%2==0?"paire":"impaire"
				lines+=`
					<div id="${elt.idjuste}" class="ligne ${pariteLigne} ligne-temporaire" data-index="${index}">
						<div id="rang-bloc" class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Rang: </div>
							<div>${index}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Nom: </div>
							<div class="bloc-value">${infoClaire(elt.nomjuste)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Prenom: </div>
							<div class="bloc-value">${infoClaire(elt.prenomjuste)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Téléphone: </div>
							<div class="bloc-value">${infoClaire(elt.phonejuste,"numero non enregistré")}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Adresse: </div>
							<div class="bloc-value">${infoClaire(elt.adressejuste)}</div>
						</div>
					</div>

				`
			})
			document.getElementById('tableau-corps').innerHTML=lines
			document.getElementById('tableau').classList.remove('invisible')
			afficherPagination(parseInt(resultat.total.J_TOTAL),qte_standard)

			let lignes = document.getElementsByClassName('ligne')
			for(var line of lignes){
				line.addEventListener('click',function(event){
					saveToken(passeur,JSON.stringify(resultat.donnee[this.getAttribute('data-index')]))
					setTimeout(()=>{
						window.location.href=`${server}juste/detail/${this.getAttribute('id')}`
					},300)
					
				})
			}
		}else{
			document.getElementById('message-vide').innerHTML= resultat.message
			document.getElementById('message-vide').classList.remove('invisible')
		}
		
	})
	.catch(error=>{
		document.getElementById('message-vide').innerHTML
		document.getElementById('message-vide').classList.remove('invisible')
	})
}
document.addEventListener('included',()=>{
	listerJuste()
	menuResponsiveActivation(route)
})
includeHTML()

