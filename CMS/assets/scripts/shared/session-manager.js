const userInfoToken='ADJDNAIDUSER'
const jwtToken='ADJDNAIDTOKEN'
const passeur='ADJPASSEUR'
const server="http://localhost:8888/dnaid/"

const connexionPage= `${server}auth`
const homePage=`${server}home`
const apiLink=`${server}system`


function getToken(cle){
	return sessionStorage.getItem(cle)
}

function saveToken(cle,token){
	sessionStorage.setItem(cle,token)
}

function checkToken(){
	console.log(getToken(jwtToken))
	if(!getToken(jwtToken)){
		window.location.replace(connexionPage)
	}
}

function clearSession(){
	sessionStorage.removeItem(userInfoToken)
	sessionStorage.removeItem(jwtToken)
	sessionStorage.removeItem(passeur)
}


