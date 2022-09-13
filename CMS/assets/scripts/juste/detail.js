route="juste"
urlParams=["juste"]//tableau declaré dans utilitaires.js

let assemblees=[]
let groupes=[]

function chargerGroupe(page){
	var formData=new FormData()
	formData.append('code','G1-5')
	formData.append('page',page)
	let dataListes=document.getElementsByClassName('groupe-data-list')
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code == requete_reussi){
			groupes=[...groupes, ...resultat.donnee]
			resultat.donnee.forEach((elt,index)=>{
				for(i=0;i<dataListes.length;i++){
					dataListes[i].innerHTML+=`<option value="${elt.matgroupe} ${elt.nomgroupe}">`
				}
			})
			if(parseInt(resultat.total.G_TOTAL)> ((page+1)*qte_standard)){
				chargerGroupe(page+1)
			}
		}
	})
}

function chargerAssemblee(page){
	var formData=new FormData()
	formData.append('code','A2-6')
	formData.append('page',page)
	let dataListes=document.getElementsByClassName('assemblee-data-list')
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code== requete_reussi){
			assemblees=[...assemblees,...resultat.donnee]
			resultat.donnee.forEach((elt,index)=>{
				for(i=0;i<dataListes.length;i++){
					dataListes[i].innerHTML+=`<option value="${elt.matassemble} ${elt.nomassemble}">`
				}
			})
			if(parseInt(resultat.total.A_TOTAL)>((page+1)*qte_standard)){
				chargerAssemblee(page+1)
			}

		}
	})
}
function chargerFonction(){
	fetch('../../CMS/assets/data/fonction.json')
	.then(response=> response.json())
	.then(response=>{
		let fonctionListe = document.getElementById('fonction-list')
		response.data.sort().forEach(elt=> fonctionListe.innerHTML += `<option value="${elt}">`)

	})
	.catch(error=>console.log(error))
}
function userModeUpdateCallBack(data){
	if(data.code==requete_reussi){
		let msg= data.message
		if(data.donnee.niveau>lambda){
			msg+= `<br> Login : ${data.donnee.login} <br> Mot de passe : ${data.donnee.mdp}`
		}
		document.getElementById('chargement-msg').innerHTML= msg
		document.getElementById('chargement-msg-container').classList.remove("efface")
		document.getElementById('loader').classList.add("efface")
	}
}


function activerLienModifier(){
	document.getElementById('lien-modifier-info').addEventListener('click',function(){
		window.location.href = `${server}juste/modifier/${getParameters(urlParams).juste}`
	})

	document.getElementById("lien-reinit-mdp").addEventListener("click",function(event){
		let formData= new FormData()
		formData.append('code',"J1-15")
		formData.append('niveau',getInfoByPasseur().niveaujuste)
		formData.append("juste",getInfoByPasseur().idjuste)
		document.getElementById('chargement-overlay').classList.remove("efface")
		document.getElementById('info-perso-actions').classList.add("invisible")
		
		executeRequete(formData)
		.then(resultat=>{
			userModeUpdateCallBack(resultat)
		})
	})
}
function loadOvelayFieldsValue(data){

	if(data.target){
		cible= document.getElementById(data.target)
		if(cible && cible.hasAttribute("load-data")){
			let base=cible.getAttribute('id').replace('-overlay','')
			let infos=document.getElementsByClassName(`${base}-control`)
			for(info of infos){
				if(info.hasAttribute("data-source"))
					info.value=selection[info.getAttribute("data-source")]
			}
		}
	}
		
}
function infosPasseur(){
	const user= JSON.parse(getToken(userInfoToken))
	let juste= getInfoByPasseur()
	
	if(!juste)
		return

	if(juste.niveaujuste==1)
		document.getElementById('lambda-mode-btn').classList.remove('efface')
	if(juste.niveaujuste==0)
		document.getElementById('admin-mode-btn').classList.remove('efface')

	if(juste.niveaujuste>lambda){
		if(juste.idjuste==user.idjuste || user.niveaujuste==superUtilisateur){
			document.getElementById('lien-reinit-mdp').classList.remove('efface')
		}
	}

	let container= document.getElementById('info-personnelle-container')

	container.innerHTML += `
	<div class="ligne-temporaire-inverse zone-tete">
		${juste.photojuste
			?
			`
			<div id="photo-identite-container">
				<img class="photo-identite" src="${server}juste/illustration/${juste.photojuste}">
			</div>
			`
			:
			`
			<div id="photo-identite-container">
				<img class="photo-identite" src="../../dao/illustration/juste/photo/null.png">
			</div>
			`

		}
		<div >${juste.nomjuste} ${juste.prenomjuste}</div>
		<div >${juste.surnomjuste}</div>
		<div class="ligne-temporaire">
			<div class="info-libelle">Année de nouvelle naissance </div>
			<div class="info-value">${infoClaire(juste.anneenvelnaissjuste)}</div>
		</div>
		
	</div>
	<div class="ligne ligne-temporaire">
		<div class="zone-bloc">
			<div class="info-libelle">Date de naissance</div>
			<div class="info-value">${infoClaire(juste.datenaissjuste)}</div>
		</div>

		<div class="zone-bloc">
			<div class="info-libelle">Genre</div>
			<div class="info-value">${infoClaire(juste.genrejuste.split(' ')[0])}</div>
		</div>
	</div>

	<div class="ligne ligne-temporaire">
		<div class="zone-bloc">
			<div class="info-libelle">Adresse</div>
			<div class="info-value">${infoClaire(juste.adressejuste)}</div>
		</div>

		<div class="zone-bloc">
			<div class="info-libelle">Téléphone</div>
			<div class="info-value">${infoClaire(juste.phonejuste)}</div>
		</div>
	</div>

	<div class="ligne ligne-temporaire">
		<div class="zone-bloc">
			<div class="info-libelle">Etat</div>
			<div class="info-value">${infoClaire(juste.etatjuste)}</div>
		</div>

		<div class="zone-bloc">
			<div class="info-libelle">Grade</div>
			<div class="info-value">${infoClaire(juste.gradejuste)}</div>
		</div>

	</div>

	<div class="ligne ligne-temporaire">
		<div class="zone-bloc">
			<div class="info-libelle">Situation matrimoniale</div>
			<div class="info-value">${infoClaire(juste.statutmatrijuste.split(' ')[0])}</div>
		</div>

		<div class="zone-bloc">
			<div class="info-libelle">Ethnie</div>
			<div class="info-value">${infoClaire(juste.ethniejuste)}</div>
		</div>

	</div>
	<div class="ligne">
		<div class="info-libelle">Origine </div>
		<div class="info-value">${infoClaire(juste.originejuste)}</div>
	</div>
	`
	activerLienModifier()
	activeTogglersClosers(document.getElementsByClassName('info-perso-toggler'),document.getElementsByClassName('info-perso-closer'))
}

function infosAssemblee(infos){
	let juste= getParameters(urlParams).juste
	var formData= new FormData()
	formData.append('code','J1-7')
	formData.append('juste',juste)
	executeRequete(formData)
	.then(resultat=>{
		temoin="-1"
		let assemblee={}
		if(resultat.code==requete_reussi){
			let container= document.getElementById('info-assemblee-container')
			container.classList.remove('invisible')
			assemblee=resultat.donnee
			temoin=assemblee.idassemble
			container.innerHTML=`
				<div class="zone-tete">
					<div>${assemblee.nomassemble}</div>

					<div class="ligne-temporaire">
						<div class="info-libelle">Fonction du juste: </div>
						<div class="info-value">${infoClaire(assemblee.fonctionrattacher)}</div>
					</div>
					<div class="ligne-temporaire">
						<div class="info-libelle">Date de rattachement: </div>
						<div class="info-value">${infoClaire(assemblee.datedebutrattacher)}</div>
					</div>
				</div>

				<div class="ligne ligne-temporaire">
					<div class="zone-bloc">
						<div class="info-libelle">Pays</div>
						<div class="info-value">${infoClaire(assemblee.paysassemble)}</div>
					</div>
					<div class="zone-bloc">
						<div class="info-libelle">Region</div>
						<div class="info-value">${infoClaire(assemblee.regionassemble)}</div>
					</div>
				</div>

				<div class="ligne ligne-temporaire">
					<div class="zone-bloc">
						<div class="info-libelle">Ville</div>
						<div class="info-value">${infoClaire(assemblee.villeassemble)}</div>
					</div>
					<div class="zone-bloc">
						<div class="info-libelle">Quartier</div>
						<div class="info-value">${infoClaire(assemblee.quartierassemble)}</div>
					</div>
				</div>
			`
			activeTogglersClosers(document.getElementsByClassName('assemblee-toggler'),document.getElementsByClassName('assemblee-closer'))
			activeAccessManager(["conditionee"])

			//afficher ou cacher le fleche qui permet de montrer les actions
			let assembleeActions=document.getElementsByClassName("assemblee-toggler conditionee")
			let visible=false
			for(a of assembleeActions){
				a.addEventListener('click',function(event){
					selection = assemblee
					loadOvelayFieldsValue({"target":this.getAttribute("data-target")})
				})
				if(!a.classList.contains("efface")){
					visible=true
				}
			}
			if(!visible)
				document.getElementById('assemblee-actions-toggler').classList.add("efface")
		}else{
			document.getElementById('info-assemblee-msg-vide').classList.remove('invisible')
			document.getElementById('assemblee-actions-toggler').classList.add('invisible')
			document.getElementById('assemblee-actions-toggler').classList.add("efface")
		}
		
		
		
	})
}

function infosGroupe(infos){
	let juste= getParameters(urlParams).juste
	var formData= new FormData()
	formData.append('code','J1-11')
	formData.append('juste',juste)
	formData.append('page','0')
	executeRequete(formData)
	.then(resultat=>{
		if(resultat.code==requete_reussi){
			document.getElementById('info-groupe-container').classList.remove('invisible')
			let container= document.getElementById("tableau-corps")

			resultat.donnee.forEach((elt,index)=>{
				if(index>5)
					return;
				let pariteLigne= index%2==0?"paire":"impaire"
				container.innerHTML +=`
					<div id="${elt.idgroupe}" class="ligne ${pariteLigne} ligne-temporaire ligne-groupe" data-index="${index}">

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Nom: </div>
							<div class="bloc-value">${infoClaire(elt.nomgroupe)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Rôle: </div>
							<div class="bloc-value">${infoClaire(elt.roleappartenir)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Date d'adhésion: </div>
							<div class="bloc-value">${infoClaire(elt.datedebutappartenir)}</div>
						</div>

						<div class="info-bloc ligne-permanente">
							<div class="libelle-responsive">Date de retractation: </div>
							<div class="bloc-value">${infoClaire(elt.datefinappartenir)}</div>
						</div>
						<div class="info-bloc ligne-permanente groupe-actions-bloc">
							<div class="libelle-responsive">Actions: </div>
							<div class="centreur toggler groupe-actions-toggler" data-target="${elt.idgroupe}actions"><i class="fas fa-ellipsis-h"></i></div>
							<div id="${elt.idgroupe}actions" class="groupe-actions invisible">
								<div class="actions-elt closer groupe-actions-closer" data-target="${elt.idgroupe}actions"><i class="fas fa-times"></i></div>
								<div class="actions-elt toggler groupe-actions-toggler parent-killer conditionee2" data-index="${index}" data-parent="${elt.idgroupe}actions" data-target="detail-groupe-overlay" data-nom="detail-juste-groupe">Details</div>
								<div class="actions-elt toggler groupe-actions-toggler parent-killer conditionee2" data-index="${index}" data-parent="${elt.idgroupe}actions" data-target="modifier-groupe-overlay" data-nom="modifier-juste-groupe">Modifier</div>
								<div class="actions-elt toggler groupe-actions-toggler parent-killer conditionee2" data-index="${index}" data-parent="${elt.idgroupe}actions" data-target="retrait-groupe-overlay" data-nom="retirer-juste-groupe">Retirer</div>
							</div>
						</div>
					</div>

				`
			})
			activeTogglersClosers(document.getElementsByClassName('groupe-actions-toggler'),document.getElementsByClassName('groupe-actions-closer'))
			let lignes=document.getElementsByClassName('ligne-groupe')
			for(var ligne of lignes){
				ligne.addEventListener('click',function(){
					saveToken(passeur,JSON.stringify(resultat.donnee[this.getAttribute('data-index')]))
					setTimeout(()=>{
						window.location.href=`${server}groupe/detail/${this.getAttribute('id')}`
					},300)
				})
			}
			let groupeActionTogglers= document.getElementsByClassName('groupe-actions-toggler')
			for (var i=0; i<groupeActionTogglers.length; i++){
				if(groupeActionTogglers[i].hasAttribute('data-index')){
					groupeActionTogglers[i].addEventListener('click',function(event){
						selection=resultat.donnee[this.getAttribute('data-index')]

						if(this.getAttribute('data-target')=="detail-groupe-overlay"){
							let affichage=document.getElementById('information-groupe-detail')
							affichage.innerHTML=infoClaire(selection.descriptionappartenir,"Aucune information supplementaire n'est disponible")
						}
						
						loadOvelayFieldsValue({"target":this.getAttribute("data-target")})
					})
				}
			}
			let groupeActions=document.getElementsByClassName("groupe-actions")
			for(var groupeAction of groupeActions){
				groupeAction.addEventListener('click',function(event){
					event.stopPropagation()
				})
			}
			activeAccessManager(["conditionee2"])
		}

		if(resultat.code == liste_vide){
			document.getElementById('info-groupe-msg-vide').classList.remove('invisible')
		}

		activeTogglersClosers(document.getElementsByClassName('groupe-toggler'),document.getElementsByClassName('groupe-closer'))
		let nouvelleAdhesion= document.getElementById('nouvelle-adhesion-action')
		nouvelleAdhesion.addEventListener('click',function(event){
			selection=getInfoByPasseur();
		})
	})
}

function activerOverlayFormValider(){
	let validers= document.getElementsByClassName('overlay-btn')
	for(let i= 0; i<validers.length; i++){
		validers[i].addEventListener('click',function(event){
			event.preventDefault()

			let base= this.getAttribute('data-id')
			let formulaire=document.getElementById(`${base}-form`)
			if(!formulaire.reportValidity())
				return;
			let feedBack= document.getElementById(`${base}-message`)
			let infos= document.getElementsByClassName(`${base}-control`)
			let formData=new FormData()
			for(var info of infos){
				if(info.type == 'hidden'){
					formData.append(info.getAttribute('name'),selection[info.getAttribute("data-source")])
					continue
				}
				if(info.hasAttribute('trois-en-un')){
					formData.append(info.getAttribute('name'),info.value.split(' ')[0])
					continue
				}
				formData.append(info.getAttribute('name'),valeurClaireForApi(info.value))
			}
			formData.append('code',this.getAttribute('data-code'))
			if(!formulaire.reportValidity())
				return;
			executeRequete(formData)
			.then(resultat=>{
				feedBack.innerHTML=resultat.message
				if(resultat.code == requete_reussi){
					formulaire.reset()
					window.location.reload()
				}
			})
		})
	}
}

function activerChangerMode(){
	let modes=document.getElementsByClassName('user-mode-btn')
	for(mode of modes){
		mode.addEventListener('click', function(event){
			let formData= new FormData()
			formData.append('code',"J1-15")
			formData.append('niveau',this.getAttribute('data-niveau'))
			formData.append("juste",getInfoByPasseur().idjuste)
			document.getElementById('chargement-overlay').classList.remove("efface")
			executeRequete(formData)
			.then(resultat=>{
				if(resultat.code=requete_reussi){
					this.classList.add('efface')
					document.getElementById(this.getAttribute('data-alter')).classList.remove('efface')
					if(parseInt(this.getAttribute('data-niveau'))>lambda){
						const user= JSON.parse(getToken(userInfoToken))
						let juste= getInfoByPasseur()
						if(juste.idjuste==user.idjuste || user.niveaujuste==superUtilisateur){
							document.getElementById('lien-reinit-mdp').classList.remove('efface')
						}
					}else{
						document.getElementById('lien-reinit-mdp').classList.add('efface')
					}
						
				}

				userModeUpdateCallBack(resultat)
			})
		})
	}
}





document.addEventListener('included',()=>{
	infosPasseur()
	infosAssemblee()
	infosGroupe()
	menuResponsiveActivation(route)
	chargerGroupe("0")
	chargerAssemblee("0")
	chargerFonction()
	activerOverlayFormValider()
	activeTogglersClosers(null,document.getElementsByClassName('closer'));
	activeLienList(getParameters(urlParams).juste)
	activerChangerMode()
})
includeHTML()




