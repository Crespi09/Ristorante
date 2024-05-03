//import axios from 'axios';
window.onload = getUserData();

let userData;
let username = document.getElementById('username');
let fullName = document.getElementById('fullName');
let email = document.getElementById('email');
let cell = document.getElementById('cell');
let data_nascita = document.getElementById('dataNascita');

async function getUserData() {
    
    //coglione
    const mail = {
        mail : "carlino@example.it"
    }
    axios.post(`http://localhost:80/Ristorante/api/v1/cliente/richiedi_dati.php`,  mail)
    .then(response => {
        // console.log(response.data); // debug
        userData = {
            nome : response.data.nome,
            cognome : response.data.cognome,
            mail : response.data.mail,
            data_nascita : response.data.data_nascita,
            cell : response.data.cell
        }

        username.setAttribute('value', userData.nome + " " + userData.cognome);
        fullName.setAttribute('value', userData.nome + " " + userData.cognome);
        email.setAttribute('value', userData.mail);
        cell.setAttribute('value', userData.cell);
        data_nascita.setAttribute('value', userData.data_nascita);

    })
    .catch(error => {
        console.error("Errore richiesta dati user", error);
    }); 

    setTimeout(() => {
        changeUserIconAttribute();
    }, 2000);
}


function changeUserIconAttribute(){
    let userIcon = document.getElementById("userIcon")
    userIcon.setAttribute('trigger', 'hover');
    userIcon.removeAttribute('state');
    userIcon.removeAttribute('delay');

}

function updateUserData(){
    fullName.removeAttribute('disabled');
    cell.removeAttribute('disabled');

    
}



