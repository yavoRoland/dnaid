route="juste"
function afficherAssemblee(){
	let userInfo=getToken(userInfoToken)
	if(userInfo){
		let user= JSON.parse(userInfo)
		if(user.niveaujuste>2){
			document.getElementById('assemble-bloc').classList.remove('invisible')

			if(user.assemblerattacher)
				document.getElementById('assemble').value=user.matassemble
		}
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

function activerVisualiseur(){
	let source=document.getElementById('source')
	let visualiseur=document.getElementById('photo')
	source.addEventListener('change',()=>{
		
		var reader=new FileReader()
		reader.addEventListener('load',()=>{
			visualiseur.src=reader.result
		})
		try{
			reader.readAsDataURL(source.files[0])
		}catch(e){
			console.log(e)
		}
	})
}

function activerClearPhoto(){
	document.getElementById('clear-photo').addEventListener('click',function(){
		document.getElementById('source').value=null
		document.getElementById('photo').src=''
		document.getElementById('source').focus()
	})
}

function activerEnregistrerJuste(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		var formData=new FormData()
		let exceptions= ["menu-chp-rechercher","source"]

		let infos=document.getElementsByTagName('input')
		let formulaire=document.getElementById('monformulaire')

		let feedBack=document.getElementById('feed-back')
		let visualiseur=document.getElementById('photo')
		feedBack.innerHTML=''

		for(i=0; i<infos.length; i++){
			
			if(exceptions.find(elt=> elt==infos[i].getAttribute('name')))
				continue

			if(!formulaire.reportValidity())
				break;

			if(infos[i].required && infos[i].value && infos[i].value.trim().length==0){
				infos[i].value=null
				formulaire.reportValidity()
				break;
			}

			if(i<infos.length-1){
				if(infos[i].getAttribute('name') == infos[i+1].getAttribute('name')){
					if(infos[i].checked)
						formData.append(infos[i].getAttribute('name') , valeurClaire(infos[i].value))
					else
						formData.append(infos[i+1].getAttribute('name') , valeurClaire(infos[i+1].value))
					i++
					continue
				}
			}
			formData.append(infos[i].getAttribute('name') , valeurClaire(infos[i].value))
			
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
	activerVisualiseur()
	activerClearPhoto()
	activerEnregistrerJuste()
})

