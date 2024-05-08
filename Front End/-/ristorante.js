// Ottieni l'URL corrente
const currentUrl = window.location.href;
const url = new URL(currentUrl);
const id = url.searchParams.get('id');

const indirizzo = document.getElementById('indirizzo');
const posti = document.getElementById('posti');
const tipologia = document.getElementById('tipologia');

let postiMax=0;
let postiLiberi;


axios.post(`http://localhost:80/Ristorante/api/v1/locale/richiedi_dati.php`, { localeID: id })
  .then(response => {
    userData = {
      civico: response.data.num_civico,
      via: response.data.via,
      posti: response.data.postiMax,
      tipologia: response.data.tipologia,
      idComune: response.data.id_comune,
      partitaIva: response.data.azienda_pIVA
    }

    postiMax=userData.posti;

    indirizzo.setAttribute('value', userData.via + " " + userData.civico);
    tipologia.setAttribute('value', userData.tipologia);

  })
  .catch(error => {
    console.error("Errore richiesta dati locale", error);
  });



axios.post(`http://localhost:80/Ristorante/api/v1/locale/conto_prenotazioni.php`, { localeID: id })
  .then(response => {
    userData = {
      nPrenotazioni: response.data.numero_prenotazioni
    }
    postiLiberi=postiMax-userData.nPrenotazioni;

    posti.setAttribute('value', postiLiberi);

   

  })
  .catch(error => {
    console.error("Errore richiesta numero prenotazioni ", error);
  }); 