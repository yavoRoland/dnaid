const acces_refuse=-1;
const requete_echoue=0;
const requete_reussi=1;
const liste_vide=2;
const msgErreurTechnique="Une erreur s'est produite. Vuillez reéssayez! si elle persiste, contacter l'administrateur."
var urlParams=new Array();//chaque page liste dans l'ordre reverse les parametres qui lui sont passés via l'url dans ce tableau en le redifinissant
const superUtilisateur=2
const admnistrateur=1


function getParameters(noms){//on passe a cette fonction le tableau urlParams redefinit par la page
	let parameters={}
	let url = window.location.href.split("/")
	noms.forEach((elt,index)=>{
		parameters[elt]=url[url.length-(index+1)]
	})
	return parameters
}


function afficherPagination(total,quantite){
	let currPage=getParameters(["page"])? parseInt(getParameters(urlParams).page):1

	let nbrePage=Math.ceil(total/quantite)
	if(nbrePage==1)
		return
	let recule=`<div class="pagination-btn avance" id="${currPage-5 <1 ? 1 : currPage-5}"> << </div>
				<div class="pagination-btn avance" id="${currPage-1 <1 ? 1 : currPage-1}"> < </div>
	`
	let avance=`<div class="pagination-btn recule" id="${currPage+1 > nbrePage? nbrePage : currPage+1}"> > </div>
				<div class="pagination-btn recule" id="${currPage+5 > nbrePage? nbrePage : currPage+5}"> >> </div>
	`
	let intermediaire='<div id="intermdiaire"> ... </div>'
	let final=''
	if(nbrePage>5){
		if(currPage && currPage>nbrePage-2){
			let precedent=currPage-5>=1? `<div id="${currPage-5}" class="pagination-btn">${currPage-5}</div>` : '<div class="pagination-btn">1</div>'

			intermediaire += `<div id="${currPage-1}" class="pagination-btn">${currPage-1}</div>`

			let suivant =currPage==nbrePage?'' : `<div id="${currPage+1}" class="pagination-btn">${currPage+1}</div>`
			final=`${recule}  ${precedent} ${intermediaire} <div class="page-actuelle pagination-btn">${currPage}</div> ${suivant} ${avance}`
		}else{
			let precedent = currPage==1? '': `<div id="${currPage-1}"  class="pagination-btn">${currPage-1}</div>`
			
			intermediaire= `<div id="${currPage+1}" class="pagination-btn"> ${currPage+1} </div> ${intermediaire}` 

			let suivant=currPage+5<=nbrePage? `<div id="${currPage+5}" class="pagination-btn">${currPage+5}</div>`:`<div class="pagination-btn">${nbrePage}</div>`
			final=`${recule}  ${precedent} <div class="page-actuelle pagination-btn">${currPage}</div> ${intermediaire} ${suivant} ${avance}`
		}
	}else{

		for(i=0; i<nbrePage; i++){
			let classPageCourante=''
			if( currPage && i+1==currPage)
				classPageCourante='page-actuelle'
			final+= `<div id="${i+1}" class="pagination-btn ${classPageCourante}"> ${i+1}</div>`
		}
	}

	document.getElementById('pagination-container').classList.remove('invisible')
	document.getElementById('pagination-container').innerHTML=final
	
	pages=document.getElementsByClassName('pagination-btn')
	for(i=0;i<pages.length; i++){
		pages[i].addEventListener('click',function(){
			if(!this.classList.contains('page-actuelle') && parseInt(this.getAttribute("id"))!= currPage){
				let index= urlParams.findIndex(elt=>elt=="page")
				let url = window.location.href.split("/")
				url[url.length-(index+1)]= this.getAttribute("id")
				window.location=url.toString().replaceAll(',','/')
			}
		})
	}
}

function valeurClaire(valeur){
	if(!(valeur==null))
		return valeur.trim().length>0? valeur.trim : null
	else
		return valeur
}

function infoClaire(info, msg="non défini"){
	return info?info:msg
}

function executeRequete(formData, token=true){
	let execution= new Promise((resolve, reject)=>{
		var xhr= new XMLHttpRequest(); 
		xhr.addEventListener("progress",(detail)=>{
			console.log("en progression")
		},false)

		xhr.addEventListener("load",(detail)=>{
			let resultat=JSON.parse(detail.target.response)
			resolve(resultat)

		},false)

		xhr.addEventListener("error",(detail)=>{
			console.log("grosse erreur")
			reject(detail)
		},false)

		xhr.addEventListener("abort",(detail)=>{
			console.log("annulée")
			reject(detail)
		},false)
		
		xhr.open('POST',apiLink, true)
		//xhr.setRequestHeader('Content-Type','application/json')
		if(token)
			xhr.setRequestHeader('Authorization',`Bearer ${getToken(jwtToken)}`)
		xhr.send(formData)
	})
	return execution		
}

function getInfoByPasseur(){
	let info=false
	try{
		info=JSON.parse(getToken(passeur))
	}catch(e){
	}
	return info
}

function activerVisualiseur(source, visualiseur){
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

