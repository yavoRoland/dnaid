route=null;
function activerModifierMDP(){
	document.getElementById('btn-valider').addEventListener('click',function(event){
		event.preventDefault()
		let chps=document.getElementsByClassName('form-chp')
		let formulaire= document.getElementById('monformulaire')
		let feedBack= document.getElementById('feed-back')
		const user=JSON.parse(getToken(userInfoToken))

		let formData =new FormData()
		if(!formulaire.reportValidity()){
			return
		}
		for(chp of chps){
			formData.append(chp.getAttribute('name'),chp.value)
		}
		formData.append('juste',user.idjuste)
		formData.append('code',"J1-14")
		executeRequete(formData)
		.then(resultat=>{
			feedBack.innerHTML=resultat.message
			if(resultat.code==requete_reussi){
				formulaire.reset()
			}
		})
	})
}



document.addEventListener('included',function(){
	activerModifierMDP()
})
includeHTML()