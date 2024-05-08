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

  if (!empty($data)) {
    try {
      $conn = mysqli_connect($db->host, $db->user, $db->password, $db->db_name);

      if (!$conn) {
        throw new Exception("Connessione al database fallita: " . mysqli_connect_error());
      }
      $check_query = "SELECT localeID FROM locale WHERE localeID = ?";
      $check_stmt = mysqli_prepare($conn, $check_query);
      mysqli_stmt_bind_param($check_stmt, 's', $data->localeID);
      mysqli_stmt_execute($check_stmt);
      mysqli_stmt_store_result($check_stmt);

      if (mysqli_stmt_num_rows($check_stmt) > 0) {
        throw new Exception("Il locale id è già presente nel database.");
      }

      $insert_query = "INSERT INTO locale (localeID, nome, num_civico, via, postiMax, tipologia, id_comune, azienda_pIVA) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $insert_stmt = mysqli_prepare($conn, $insert_query);
      mysqli_stmt_bind_param($insert_stmt, 'sssssss', $data->localeID, $data->nome, $data->num_civico, $data->via, $data->postiMax, $data->tipologia, $data->id_comune, $data->azienda_pIVA);

      if (!mysqli_stmt_execute($insert_stmt)) {
        throw new Exception("Errore durante l'inserimento dei dati: " . mysqli_error($conn));
      }

      mysqli_stmt_close($insert_stmt);
      mysqli_close($conn);

      echo json_encode(array("message" => "Dati inseriti con successo."));
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(array("message" => $e->getMessage()));
    }
  } else {
    http_response_code(400);
    echo json_encode(array("message" => "Nessun dato inviato."));
  }
} else {
  // Metodo non consentito
  http_response_code(405);
  echo json_encode(array("message" => "Metodo non consentito."));
}
?>