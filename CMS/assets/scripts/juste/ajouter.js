route="juste"

function chargerAnneeNouvelleNaissance(){
	let select = document.getElementById('nvelNais')
	for(i=1990; i<new Date().getFullYear(); i++){
		select.innerHTML+=`<option value="${i}">${i}</option>`
	}
}
function chargerEthnie(){
	fetch('../CMS/assets/data/ethnie.json')
	.then(response=> response.json())
	.then(response=>{
		let etnieListe = document.getElementById('ethnie-list')
		response.data.sort().forEach(elt=> etnieListe.innerHTML += `<option value="${elt}">`)

	})
	.catch(error=>console.log(error))
}

function chargerFonction(){
	fetch('../CMS/assets/data/fonction.json')
	.then(response=> response.json())
	.then(response=>{
		let fonctionListe = document.getElementById('fonction-list')
		response.data.sort().forEach(elt=> fonctionListe.innerHTML += `<option value="${elt}">`)

	})
	.catch(error=>console.log(error))
}


function activerClearPhoto(){
	document.getElementById('clear-photo').addEventListener('click',function(){
		document.getElementById('source').value=null
		document.getElementById('photo').src=''
		document.getElementById('source').focus()
	})
}

function chargerAssemblee(page){
	let formData=new FormData()
	formData.append('code','A2-6')
	formData.append('page',page)
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code==requete_reussi){
			let assembleeListe=document.getElementById('assemble-list')
			resultat.donnee.forEach((elt,index)=>{
				assembleeListe.innerHTML+=`<option value="${elt.idassemble}-${elt.matassemble} ${elt.nomassemble}">`
			})
			if(parseInt(resultat.total.A_TOTAL) < (page *10)){
				chargerAssemblee(page+1)
			}
		}
	})
}

function visibiliteAssembleeBloc(){
	try{
		const user=JSON.parse(getToken(userInfoToken))
		if(user.niveaujuste){
			if(parseInt(user.niveaujuste)==superUtilisateur){
				document.getElementById('assemblee-bloc').classList.remove("invisible")
				chargerAssemblee("0")
			}
		}


	}catch(e){

	}
}

function activerEnregistrerJuste(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		var formData=new FormData()
		let exceptions= ["menu-chp-rechercher","source","assemble"]

		let infos=document.getElementsByTagName('input')
		let formulaire=document.getElementById('monformulaire')

		let feedBack=document.getElementById('feed-back')
		let visualiseur=document.getElementById('photo')
		feedBack.innerHTML=''
		let valide=true
		for(i=0; i<infos.length; i++){
			
			if(exceptions.find(elt=> elt==infos[i].getAttribute('name')))
				continue

			infos[i].value=valeurClaire(infos[i].value)

			if(!formulaire.reportValidity()){
				valide= false
				break;
			}


			if(i<infos.length-1){
				if(infos[i].getAttribute('name') == infos[i+1].getAttribute('name')){
					if(infos[i].checked)
						formData.append(infos[i].getAttribute('name') ,infos[i].value)
					else
						formData.append(infos[i+1].getAttribute('name') , infos[i+1].value)
					i++
					continue
				}
			}
			formData.append(infos[i].getAttribute('name') , infos[i].value)
			
		}
		
		if(!valide)
			return
		const user=JSON.parse(getToken(userInfoToken))
		if(user.niveaujuste){
			if(parseInt(user.niveaujuste)==2 && document.getElementById('assemble').value){
				formData.append('assemblee',document.getElementById('assemble').value.split('-')[0])
			}else{
				formData.append('assemblee',user.idassemble)
			}
		}

		let source=document.getElementById('source')
		if(source.files.length<0){
			formData.append('photo', source.files[0])
			formData.append('ext', source.value.split('.')[imagePart.length - 1])
		}
		formData.append('code','J1-1')
		executeRequete(formData)
		.then((resultat)=>{
			if(resultat.code==requete_reussi){
				formulaire.reset()
				visualiseur.src= ''
				infos[infos.length-1].focus()
			}
			feedBack.innerHTML=resultat.message
		})
		.catch(error=>feedBack.innerHTML=msgErreurTechnique)
		
	})
}


document.addEventListener('included',function(){
	chargerEthnie()
	chargerFonction()
	chargerAnneeNouvelleNaissance()
	visibiliteAssembleeBloc()
	activerVisualiseur(source=document.getElementById('source'),document.getElementById('photo'))
	activerClearPhoto()
	activerEnregistrerJuste()
	menuResponsiveActivation(route)
})

