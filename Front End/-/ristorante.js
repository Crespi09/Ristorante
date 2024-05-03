// Ottieni l'URL corrente
const currentUrl = window.location.href;

// Crea un oggetto URL per analizzare l'URL
const url = new URL(currentUrl);

// Ottieni il valore del parametro "id"
const id = url.searchParams.get('id');

// Ora puoi utilizzare il valore di "id"
console.log('ID:', id);


axios.post(`http://localhost:80/Ristorante/api/v1/cliente/richiedi_dati.php`,  {id:id})
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