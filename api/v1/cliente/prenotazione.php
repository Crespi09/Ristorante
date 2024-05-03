<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../../config.php";

$db = new Database();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $data = json_decode(file_get_contents("php://input"));
  if (!empty($data->mail_prenotazione) && !empty($data->turnoID) && empty($data->localeID) && !empty($data->numero_posti)) {
    try {
      $conn = mysqli_connect($db->host, $db->user, $db->password, $db->db_name);

      if (!$conn) {
        throw new Exception("Connessione al database fallita: " . mysqli_connect_error());
      }
        
      $check_query = "SELECT postiMax FROM locale WHERE localeID=?";
      $check_stmt = mysqli_prepare($conn, $check_query);
      mysqli_stmt_bind_param($check_stmt, 's', $data->localeID);
      mysqli_stmt_execute($check_stmt);
      mysqli_stmt_bind_result($check_stmt, $postiMax);
      mysqli_stmt_fetch($check_stmt);
      mysqli_stmt_close($check_stmt);

      if ($data->numero_posti > $postiMax) {
        echo json_encode(array("message"=>"Non ci sono abbastanza posti disponibili"));
        exit();
      }

      //check se la prenotazione esista già nello stesso turno, chiavi primarie??

      $insert_query = "INSERT INTO prenotazione (mail_prenotazione, data_prenotazione, localeID, numero_posti, turnoID) VALUES (?, ?, ?, ?, ?)"; //data prenotazione e data modifica?
      $insert_stmt = mysqli_prepare($conn, $insert_query);
      mysqli_stmt_bind_param($insert_stmt, 'sssss', $data->mail_prenotazione, $data->localeID, $data->cognome, $data->numero_posti, $data->turnoID);
      mysqli_stmt_execute($insert_stmt);
      if (!mysqli_stmt_execute($insert_stmt)) {
        throw new Exception("Errore durante l'inserimento dei dati: " . mysqli_error($conn));
      }
      mysqli_stmt_close($insert_stmt);
      mysqli_close($conn);

      echo json_encode(array("message" => "Prenotazione inserita con successo."));
    } catch (Exception $e) {
      http_response_code(401);
      echo json_encode(array("message" => $e->getMessage()));
    }
  } else {
    http_response_code(400);
    echo json_encode(array("message" => "Nessun dato inviato."));
  }

} else {
  http_response_code(405);
  echo json_encode(array("message" => "Metodo non consentito."));
}



