function valeurClaire(valeur){
	if(!(valeur==null))
		return valeur.trim()
	else
		return valeur
}

function chargerEthnie(){
	fetch('CMS/assets/data/ethnie.json')
	.then(response=> response.json())
	.then(response=>{
		let etnieListe = document.getElementById('ethnie-list')
		response.data.sort().forEach(elt=> etnieListe.innerHTML += `<option value="${elt}">`)

	})
	.catch(error=>console.log(error))
}

function chargerFonction(){
	fetch('CMS/assets/data/fonction.json')
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



document.addEventListener('included',function(){
	chargerEthnie()
	chargerFonction()
	activerVisualiseur()
	activerClearPhoto()
})