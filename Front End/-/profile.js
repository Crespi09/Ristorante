import axios from 'axios'

window.onload = getUserData();

function getUserData(){
    console.log("albertooooooooo");
    const userData = {
        mail : "carlino@example.it"
    }


    axios.post('http://localhost/api/v1/cliente/richiedi_dati.php', userData)
    .then(response => {
        console.log(response.data);
    })
    .catch(error => {
        console.error("Errore richiesta dati user", error);
    })
}



function updateUserData(){

}