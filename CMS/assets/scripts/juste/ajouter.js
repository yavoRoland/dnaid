route="juste"

function chargerAnneeNouvelleNaissance(){
	let select = document.getElementById('nvelNais')
	for(i=new Date().getFullYear(); i>=1990; i--){
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

	let assemblee=getInfoByPasseur()
	if(assemblee && assemblee.idassemble)
		document.getElementById('assemble').value=`${assemblee.idassemble}-${assemblee.matassemble} ${assemblee.nomassemble}`
	

	let formData=new FormData()
	formData.append('code','A2-6')
	formData.append('page',page)
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code==requete_reussi){
			let assembleeListe=document.getElementById('assemble-list')
			resultat.donnee.forEach((elt,index)=>{
				assembleeListe.innerHTML+=`<option value="${elt.matassemble} ${elt.nomassemble}">`
			})
			if(parseInt(resultat.total.A_TOTAL) > ((page+1) *qte_standard)){
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

function visibiliteDateDeces(){
	document.getElementById('etat').addEventListener('change',function(){
		let dateDeces= document.getElementById('datedeces')
		let blocDateDeces=document.getElementById('datedeces-bloc')
		if(this.value=="décédé mort"){
			blocDateDeces.classList.remove('invisible')
			dateDeces.setAttribute('required','required')
		}else{
			dateDeces.value=null
			dateDeces.removeAttribute('required')
			blocDateDeces.classList.add('invisible')
		}
	})
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
			formData.append(infos[i].getAttribute('name') , valeurClaireForApi(infos[i].value))
			
		}
		if(!valide)
			return
		infos=document.getElementsByTagName('select')
		for(i=0;i<infos.length;i++){
			formData.append(infos[i].getAttribute('name') , valeurClaireForApi(infos[i].value))
		}
		
		const user=JSON.parse(getToken(userInfoToken))
		if(user){
			if(parseInt(user.niveaujuste)==superUtilisateur && document.getElementById('assemble').value){
				formData.append('assemblee',document.getElementById('assemble').value.split(' ')[0])
			}else{
				formData.append('assemblee',user.idassemble)
			}
		}else{//le else est mis pour les test. il doit etre supprimer lorsqu'on rentre en production
			formData.append('assemblee',1)
		}

		let source=document.getElementById('source')
		if(source.files.length>0){
			imagePart=source.value.split('.')
			formData.append('photo', source.files[0])
			formData.append('ext', imagePart[imagePart.length - 1])
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
	visibiliteDateDeces()
	activerVisualiseur(source=document.getElementById('source'),document.getElementById('photo'))
	activerClearPhoto()
	activerEnregistrerJuste()
	menuResponsiveActivation(route)
})
includeHTML()

