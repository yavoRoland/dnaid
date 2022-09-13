const includedEvent=new Event('included')
var route=null
var selection=null//variable de stockage d'un ensemble d'information  qu'on est sur le point de modifier sur une page donnée
var carte=null //variable qui stock les données au format JSON contenu dans CMS/assets/data/routage.json afin de determiner si une action est disponible ou non pour l'utilisateur courant
var temoin=null //variable qui permet de savoir si un utilisateur de niveau 1 peut effectuer une tache de niveau 1 lorsque l'option temoin est activé
// l'option temoin indique si oui ou non l'utilisateur doit appartenir à une assemblée donnée avant de pouvoir effectuer des actions
function actionMenuResponsive(){
	let menuElts= document.getElementsByClassName("menu-responsive-elt")
	for(i=0; i<menuElts.length; i++){
		menuElts[i].addEventListener('click',function() {
			actifs= document.getElementsByClassName('actif')
			for(j=0; j<actifs.length; j++){
				actifs[j].classList.remove('actif')
			}

			ssMenuContainers= document.getElementsByClassName('ss-menu-elt-container')
			for(j=0; j<ssMenuContainers.length; j++){
				if(!ssMenuContainers[j].classList.contains('invisible'))
					ssMenuContainers[j].classList.add('invisible')
			}
			
			document.getElementById(`${this.getAttribute('id')}-elt`).classList.remove('invisible')
			this.parentNode.classList.add('actif')
		})
	}
}

function actionDeconnexion(){
	let btnDeconnexions= document.getElementsByClassName('btn-deconnexion')
	for(var btn of btnDeconnexions){
		btn.addEventListener('click',function(){
			clearSession()
			window.location.replace(connexionPage)
		})
	}
}


function afficherMenu(){
	document.getElementById("menu-responsive-part").classList.remove('invisible')
	document.getElementById("menu-burger-container").classList.add('invisible')
}

function cacherMenu(){
	document.getElementById("menu-responsive-part").classList.add('invisible')
	document.getElementById("menu-burger-container").classList.remove('invisible')
}

function identification(){
	const user= JSON.parse(getToken(userInfoToken))
	document.getElementById('header-action-user').innerHTML=`${user.nomjuste.charAt(0)}. ${user.prenomjuste}`
	document.getElementById('header-sous-titre').innerHTML=user.hasOwnProperty('nomassemble')?user.nomassemble:"Assemblée des justes de C.I."
	document.getElementById('header-titre').innerHTML=user.niveaujuste==1? "Administrateur":"Super Administrateur"
	document.getElementById('menu-responsive-user').innerHTML=`${user.nomjuste.charAt(0)}. ${user.prenomjuste}`
}

function navigationLatterale(){
	let latteraux=document.getElementsByClassName('latteral-elt')
	for(var latteral of latteraux){
		latteral.addEventListener('click',function(){
			if(route){
				window.location.href=`${server}${route}/${this.getAttribute('data-action')}`
			}
		})
	}
}

function menuResponsiveActivation(route){

	let menuElts= document.getElementsByClassName("menu-responsive-elt")
	actifs= document.getElementsByClassName('actif')
	for(var actif of actifs){
		actif.classList.remove('actif')
	}
	ssMenuContainers= document.getElementsByClassName('ss-menu-elt-container')
	for(smc of ssMenuContainers){
		smc.classList.add('invisible')
	}
	if(route){
		document.getElementById(`menu-${route}-elt`).classList.remove('invisible')
		document.getElementById(`menu-${route}`).parentNode.classList.add('actif')
	}
}


function activeTogglersClosers(togglers,closers){
	if(togglers){
		for(i=0; i<togglers.length; i++){
			togglers[i].addEventListener('click',function(event){
				event.stopPropagation()
				let cible=document.getElementById(this.getAttribute('data-target'))
				cible.classList.remove('invisible')
				if(this.classList.contains('parent-killer'))
					document.getElementById(this.getAttribute('data-parent')).classList.add('invisible')

				
			})
		}
	}
		
	if(closers){
		for(i=0; i<closers.length; i++){
			closers[i].addEventListener('click',function(event){
				event.stopPropagation()
				document.getElementById(this.getAttribute('data-target')).classList.add('invisible')
				selection=null
			})	
		}
	}	
}

function activeLienList(selected){
	let liens=document.getElementsByClassName('lien-list')
	for(lien of liens){
		lien.addEventListener('click',function(){
			window.location.href=`${server}${this.getAttribute('data-link')}/${selected}/1`
		})
	}
}


function activeAccessManager(selectors){
	if(!carte)
		return

	if(!route)
		return
	for(selector of selectors){
		
		let elts=document.getElementsByClassName(selector)
		const user= JSON.parse(getToken(userInfoToken))
		for(elt of elts){
			if(carte[route][elt.getAttribute("data-nom")]){
				const user= JSON.parse(getToken(userInfoToken))
				let path=carte[route][elt.getAttribute("data-nom")]
				
				if(user.niveaujuste<path.niveau){
					elt.classList.add('efface')
				}

				if(user.niveaujuste==path.niveau && path.temoin){
					if(!temoin || !user.idassemble || user.idassemble!=temoin){
						elt.classList.add('efface')
					}
				}
			}
		}
	}
}



function chargerCarte(){
	fetch(`/dnaid/CMS/assets/data/routage.json`)
	.then(response=> response.json())
	.then(response=>{
		carte=response.carte;
	})
}

function activerMoteur(){
	let search= document.getElementsByClassName('chp-search')
	for(s of search){
		s.setAttribute('placeholder',`Rechercher ${route??"juste"}`)
		s.addEventListener('keypress',function(event){
			if(event.keyCode === 13){
				saveToken(passeur,JSON.stringify({"text":this.value.trim()}))
				setTimeout(()=>{
					window.location.href=`${server}${route??"juste"}/rechercher/1`
				},300)
			}
		})
	}
}

function gestionMenuPrincipal(){
	if(route){
		document.getElementById(`menu-${route}`).classList.add('active')
	}
}

function activationSimple(){
	document.getElementById('chargement-btn-closer').addEventListener('click',function(event){
		event.preventDefault()
		document.getElementById('chargement-overlay').classList.add("efface")
	})
}


document.addEventListener('included',()=>{
	identification();
	actionMenuResponsive();
	document.getElementById('menu-burger-container').addEventListener('click',afficherMenu);
	document.getElementById('fermer-menu').addEventListener('click',cacherMenu)
	actionDeconnexion()
	navigationLatterale()
	activeAccessManager(["menu-elt","ss-menu-elt","conditionee"])
	activerMoteur()
	gestionMenuPrincipal()
	activationSimple()
})
chargerCarte()
