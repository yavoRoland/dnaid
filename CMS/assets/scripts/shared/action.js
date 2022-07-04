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

afficherMenu=function(){
	document.getElementById("menu-responsive-part").classList.remove('invisible')
	document.getElementById("menu-burger-container").classList.add('invisible')
}

cacherMenu=function(){
	document.getElementById("menu-responsive-part").classList.add('invisible')
	document.getElementById("menu-burger-container").classList.remove('invisible')
}


document.addEventListener('included',()=>{
	actionMenuResponsive();

	document.getElementById('menu-burger-container').addEventListener('click',afficherMenu);
	document.getElementById('fermer-menu').addEventListener('click',cacherMenu)
})

