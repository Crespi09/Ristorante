const username = document.getElementById('username');
const fullName = document.getElementById('fullName');
const email = document.getElementById('email');
const cell = document.getElementById('cell');
const data_nascita = document.getElementById('dataNascita');

window.onload = function() {
    getUserData();
    getPrenotazioniData();
};


// funzione che prende i dati dell'utente
async function getUserData() {
    
    //coglione
    const mail = {
        mail : "carlino@example.it" //TODO - prendere da sessione
    }

    axios.post(`http://localhost:80/Ristorante/api/v1/cliente/richiedi_dati.php`,  mail)
    .then(response => {
        console.log(response.data); // debug
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

// funzione che cambia l'animazione dell'icona utente
function changeUserIconAttribute(){
    let userIcon = document.getElementById("userIcon")
    userIcon.setAttribute('trigger', 'hover');
    userIcon.removeAttribute('state');
    userIcon.removeAttribute('delay');

}

// -----------------FUNZIONI EDIT BTN-------------------------------------------

// funzione che aggiorna dati utente
function updateUserData(){
    fullName.removeAttribute('disabled');
    cell.removeAttribute('disabled');

    fullName.style.borderColor = "black";
    cell.style.borderColor = "black";

}
function handleEditHoverIn(){
    console.log("edit mouse hover");
    fullName.style.borderColor = "black";
    cell.style.borderColor = "black";
}

function handleEditHoverOut(){
    fullName.style.borderColor = "transparent";
    cell.style.borderColor = "transparent";
}
//----------------------------------------------------------------------------

// funzione che prende le prenotazioni dell'utente
function getPrenotazioniData(){

    const mail = {
        mail_prenotazione : "carlino@example.it" //TODO - prendere da sessione
    }

    axios.post(`http://localhost:80/Ristorante/api/v1/prenotazione/ricerca.php`,  mail)
    .then(response => {
        
        response.data.forEach(prenot => {
            let tr = document.createElement("tr");
            Object.values(prenot).forEach(value => {
                let td = document.createElement("td");
                td.innerText = value;
                tr.appendChild(td);
            });
            document.getElementById("tablePrenotBody").appendChild(tr);
        });

    })
    .catch(error => {
        console.error("Errore richiesta dati user", error);
    }); 

}

// funzione per ricerca ristorante utente
function ricercaRistorante(){
    
}



