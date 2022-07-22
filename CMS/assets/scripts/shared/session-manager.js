const userInfoToken='ADJDNAIDUSER'
const jwtToken='ADJDNAIDTOKEN'
const passeur='ADJPASSEUR'


const connexionPage= 'http://localhost:8888/dnaid/auth'
const homePage='http://localhost:8888/dnaid/home'
const apiLink='http://localhost:8888/dnaid/system'


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


