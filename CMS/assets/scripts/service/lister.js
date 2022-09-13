route="service"

urlParams=["page"] //tableau declaré dans utilitaires.js


function listerService(){
	let page=getParameters(urlParams).page
	var formData= new FormData()
	formData.append('code','S1-5')
	formData.append('page',page-1)
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code == liste_vide){
			document.getElementById('message-vide').classList.remove('invisible')
		}else if(resultat.code == requete_reussi){
			let lines=''
			resultat.donnee.forEach((elt, index)=>{
				let pariteLigne = index % 2 == 0? "paire":"impaire"
				lines+= `
					<div id="${elt.idservice}" class="ligne ${pariteLigne} ligne-temporaire" data-index="${index}">
						<div id="rang-bloc" class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Rang: </div>
							<div>${index}</div>
						</div>

						<div class="info-bloc">
							<div class="libelle-responsive"> Nom: </div>
							<div class="bloc-value">${infoClaire(elt.nomservice)}</div>
						</div>

						<div class="info-bloc">
							<div class="libelle-responsive"> Date de création: </div>
							<div class="bloc-value">${infoClaire(elt.datecreatservice)}</div>
						</div>
					</div>
				`
			})
			document.getElementById('tableau-corps').innerHTML=lines
			document.getElementById('tableau').classList.remove('invisible')
			afficherPagination(parseInt(resultat.total.S_TOTAL),qte_huge)
			let lignes = document.getElementsByClassName('ligne')
			for(var line of lignes){
				line.addEventListener('click',function(event){
					saveToken(passeur,JSON.stringify(resultat.donnee[this.getAttribute('data-index')]))
					setTimeout(()=>{
						window.location.href=`${server}service/detail/${this.getAttribute('id')}`
					},300)
				})
			}
		}else{
			document.getElementById('message-vide').innerHTML= resultat.message
			document.getElementById('message-vide').classList.remove('invisible')
		}
	})
}

document.addEventListener('included',()=>{
	listerService()
	menuResponsiveActivation(route)
})
includeHTML()
