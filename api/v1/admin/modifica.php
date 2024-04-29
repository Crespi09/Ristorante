<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../../config.php";

$db = new Database();
if ($_SERVER["REQUEST_METHOD"] == "PUT") {
  $data = json_decode(file_get_contents("php://input"));
  if (!empty($data->mail_prenotazione) && !empty($data->turnoID) && !empty($data->numero_posti)) {
    try {
      $conn = mysqli_connect($db->host, $db->user, $db->password, $db->db_name);

      if (!$conn) {
        throw new Exception("Connessione al database fallita: " . mysqli_connect_error());
      }

      $check_query = "SELECT * FROM prenotazione WHERE mail_prenotazione = ? AND turnoID = ?";
      $check_stmt = mysqli_prepare($conn, $check_query);
      mysqli_stmt_bind_param($check_stmt, 'ss', $data->mail_prenotazione, $data->turnoID);
      mysqli_stmt_execute($check_stmt);
      $result = mysqli_stmt_get_result($check_stmt);
      
      if (mysqli_num_rows($result) == 0) {
        throw new Exception("La prenotazione specificata non esiste.");
      }

      // Modifica del turno id e del numero dei posti
      $update_query = "UPDATE prenotazione SET numero_posti = ?, turnoID=? WHERE mail_prenotazione = ? AND data_prenotazione = ? AND localeID = ?"; //manca il giorno in cui si mangia
      $update_stmt = mysqli_prepare($conn, $update_query);
      mysqli_stmt_bind_param($update_stmt, 'sss', $data->numero_posti, $data->turnoID, $data->mail_prenotazione, $data->data_prenotazione, $data->localeID );
      mysqli_stmt_execute($update_stmt);

      mysqli_stmt_close($update_stmt);
      mysqli_close($conn);

      echo json_encode(array("message" => "Prenotazione modificata con successo."));
    } catch (Exception $e) {
      http_response_code(401);
      echo json_encode(array("message" => $e->getMessage()));
    }
  } else {
    http_response_code(400);
    echo json_encode(array("message" => "Dati incompleti per la modifica della prenotazione."));
  }

} else {
  http_response_code(405);
  echo json_encode(array("message" => "Metodo non consentito."));
}
?>
