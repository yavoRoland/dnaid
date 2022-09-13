route="juste"

urlParams=["page","juste"] //tableau declaré dans utilitaires.js

function listerAssemblee(){
	let page=getParameters(urlParams).page
	let juste=getParameters(urlParams).juste
	var formData= new FormData()
	formData.append('code','J1-10')
	formData.append('juste',juste)
	formData.append('page',page-1)
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code==liste_vide){
			document.getElementById('message-vide').classList.remove('invisible')
		}else if(resultat.code==requete_reussi){
			let lines=''
			resultat.donnee.forEach((elt,index)=>{
				let pariteLigne = index % 2 == 0? "paire":"impaire"
				lines += `
					<div id="${elt.idassemble}" class="ligne ${pariteLigne} ligne-temporaire" data-index="${index}">
						<div id="rang-bloc" class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Rang: </div>
							<div>${index}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Nom: </div>
							<div class="bloc-value">${infoClaire(elt.nomassemble)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Pays: </div>
							<div class="bloc-value">${infoClaire(elt.paysassemble)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Ville: </div>
							<div class="bloc-value">${infoClaire(elt.villeassemble)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Quartier: </div>
							<div class="bloc-value">${infoClaire(elt.quartierassemble)}</div>
						</div>
					</div>
				`
			})
			document.getElementById('tableau-corps').innerHTML=lines
			document.getElementById('tableau').classList.remove('invisible')
			afficherPagination(parseInt(resultat.total.A_TOTAL),qte_standard)
			let lignes = document.getElementsByClassName('ligne')
			for(var line of lignes){
				line.addEventListener('click',function(event){
					saveToken(passeur,JSON.stringify(resultat.donnee[this.getAttribute('data-index')]))
					setTimeout(()=>{
						window.location.href=`${server}assemblee/detail/${this.getAttribute('id')}`
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
	listerAssemblee()
	menuResponsiveActivation(route)
})
includeHTML()


