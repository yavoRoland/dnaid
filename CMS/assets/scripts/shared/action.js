const includedEvent=new Event('included')

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
	document.getElementById('header-titre').innerHTML=user.niveauJuste==1? "Administrateur":"Super Administrateur"
	document.getElementById('menu-responsive-user').innerHTML=`${user.nomjuste.charAt(0)}. ${user.prenomjuste}`
}




document.addEventListener('included',()=>{
	//identification();
	actionMenuResponsive();
	document.getElementById('menu-burger-container').addEventListener('click',afficherMenu);
	document.getElementById('fermer-menu').addEventListener('click',cacherMenu)
	actionDeconnexion()
})

