route="juste"
urlParams=["juste"]//tableau declaré dans utilitaires.js
var conservateur=null

function chargerAnneeNouvelleNaissance(){
	let select = document.getElementById('anneenvelnaiss')
	for(i=1990; i<new Date().getFullYear(); i++){
		select.innerHTML+=`<option value="${i}">${i}</option>`
	}
}
function visibiliteDateDeces(valeur){
	let dateDeces= document.getElementById('datedeces')
		let blocDateDeces=document.getElementById('datedeces-bloc')
		if(valeur=="décédé mort"){
			blocDateDeces.classList.remove('invisible')
			dateDeces.setAttribute('required','required')
		}else{
			dateDeces.value=null
			dateDeces.removeAttribute('required')
			blocDateDeces.classList.add('invisible')
		}
}
function activerModifierPhoto(){
	document.getElementById('btn-modifier').addEventListener('click',function(event){
		event.preventDefault()
		document.getElementById('overlay-message').innerHTML=""
		let nouvellePhoto= document.getElementById('image')
		if(nouvellePhoto.files.length==0){
			document.getElementById('overlay-message').innerHTML="Veuillez selectionner la photo"
			return
		}
		let juste= getInfoByPasseur()
		let imagePart=image.value.split('.')
		var formData= new FormData()
		formData.append('photo',image.files[0])
		formData.append('extension', imagePart[imagePart.length - 1])
		formData.append('juste',juste.idjuste)
		formData.append('code','J1-3')
		executeRequete(formData)
		.then(resultat=>{
			document.getElementById('overlay-message').innerHTML=resultat.message
			if(resultat.code==requete_reussi){
				var reader = new FileReader()
				reader.addEventListener('load',()=>{
					document.getElementById('photo').src=reader.result
				})
				try{
					reader.readAsDataURL(nouvellePhoto.files[0])
				}catch(e){

				}
				
			}
		})
	})
	
}
function activerModifierJuste(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		let juste= getInfoByPasseur()
		conservateur=juste
		var formData= new FormData()

		let formulaire=document.getElementById('monformulaire')
		if(!formulaire.reportValidity())
			return

		let feedBack= document.getElementById('feed-back')
		feedBack.innerHTML=""

		let change=false

		for(var property in juste){
			let elts= document.getElementsByName(property.replace('juste',''))
			for(var elt of elts){
				if(elt.nodeName== "INPUT"){
					if(elt.getAttribute('type') == "text" || elt.getAttribute('type') == "date"){

						elt.value=valeurClaire(elt.value)
						formData.append(elt.getAttribute('name'),valeurClaireForApi(elt.value))

						if(elt.value != juste[property]){
							change=true
						}
						conservateur[property]=elt.value
					}

					if(elt.getAttribute('type') == "radio"){
						if(elt.checked){
							formData.append(elt.getAttribute('name'),elt.value)
							
							if(elt.value != juste[property])
								change=true

							conservateur[property]=elt.value
						}
					}
				}
				if(elt.nodeName== "SELECT"){
					formData.append(elt.getAttribute('name'), elt.value)
					
					if(elt.value !=juste[property])
						change=true

					conservateur[property]=elt.value
				}
			}
		}
		if(!formulaire.reportValidity())
			return
		if(!change)
			return

		formData.append('code','J1-2')
		formData.append('juste',juste.idjuste)
		executeRequete(formData)
		.then((resultat)=>{
			if(resultat.code==requete_reussi){
				saveToken(passeur,JSON.stringify(conservateur))
			}
			feedBack.innerHTML=resultat.message
		})
	})
}

function chargerInfos(){
	let juste= getInfoByPasseur()

	if(!juste)
		return
	if(juste.datedecesjuste!=null){
		document.getElementById('datedeces').classList.add('invisible')
	}
	visibiliteDateDeces(juste.datedecesjuste)
	for(var property in juste){
		let elts= document.getElementsByName(property.replace('juste',''))
		if(elts.length>0){
			for(var elt of elts){
				if(elt.nodeName == "INPUT" && juste[property]){
					if(elt.getAttribute('type') == "text")
						elt.value=valeurClaire(juste[property])
					if(elt.getAttribute('type') == "date"){
						elt.value=juste[property]
					}
					

					if(elt.getAttribute('type')== "radio" && elt.value.trim().toLowerCase() == juste[property].trim().toLowerCase()){
						elt.setAttribute("checked",true)
					}
				}

				if(elt.nodeName == "SELECT"){
					elt.value=juste[property]
				}
			}
		}
	}

	if(juste.photojuste){
		document.getElementById('photo').src=`${server}juste/illustration/${juste.photojuste}`
		document.getElementById('overlay-img').src=`${server}juste/illustration/${juste.photojuste}`
	}else{
		document.getElementById('photo').src=`../../dao/illustration/juste/photo/null.png`
	}			
}

function chargerEthnie(){
	fetch('../../CMS/assets/data/ethnie.json')
	.then(response=> response.json())
	.then(response=>{
		let etnieListe = document.getElementById('ethnie-list')
		response.data.sort().forEach(elt=> etnieListe.innerHTML += `<option value="${elt}">`)

	})
	.catch(error=>console.log(error))
}

function activeVisibiliteOverlay(){
	document.getElementById('change-photo').addEventListener('click',function(){
		document.getElementById('overlay').classList.remove('invisible')
	})

	document.getElementById('close-overlay').addEventListener('click',function(){
		document.getElementById('overlay').classList.add('invisible')
	})
}
function activerVisibiliteDateDeces(){
	document.getElementById('etat').addEventListener('change',function(){
		visibiliteDateDeces(this.value)
	})
}





document.addEventListener('included',function(){
	chargerAnneeNouvelleNaissance()
	chargerEthnie()
	activeVisibiliteOverlay()
	visibiliteDateDeces()
	chargerInfos()
	menuResponsiveActivation(route)
	activerModifierJuste()
	activerModifierPhoto()
	activerVisualiseur(document.getElementById('image'),document.getElementById('overlay-img'))
})
includeHTML()
