const quantite= 15

urlParams=["page"] //tableau declaré dans utilitaires.js

function listerGroupe(){
	let page=getParameters(urlParams).page
	var formData= new FormData()
	formData.append('code','G1-5')
	formData.append('page',page-1)
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code == liste_vide){
			document.getElementById('message-vide').classList.remove('invisible')
		}else if(resultat.code == requete_reussi){
			let lines=''
			resultat.donnee.forEach((elt, index)=>{
				let pariteLigne = index % 2 == 0? "paire":"impaire"
				lines+=`
					<div id="${elt.idgroupe}" class="ligne ${pariteLigne} ligne-temporaire">
						<div id="rang-bloc" class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Rang: </div>
							<div>${index}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Nom: </div>
							<div class="bloc-value">${infoClaire(elt.nomgroupe)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive"> Date de création: </div>
							<div class="bloc-value">${infoClaire(elt.datecreatgroupe)}</div>
						</div>
					</div>
				`
			})
			document.getElementById('tableau-corps').innerHTML=lines
			document.getElementById('tableau').classList.remove('invisible')
			afficherPagination(parseInt(resultat.total.G_TOTAL),quantite)
			let lignes = document.getElementsByClassName('ligne')
			for(var line of lignes){
				line.addEventListener('click',function(event){
					console.log("on a clicker sur "+this.getAttribute('id'))
				})
			}
		}else{
			document.getElementById('message-vide').innerHTML= resultat.message
			document.getElementById('message-vide').classList.remove('invisible')
		}
	})
}
document.addEventListener('included',()=>{
	listerGroupe()
})