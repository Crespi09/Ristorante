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

  try {
    $conn = mysqli_connect($db->host, $db->user, $db->password, $db->db_name);

    if (!$conn) {
      throw new Exception("Connessione al database fallita: " . mysqli_connect_error());
    }


    $query = "SELECT localeID, data_prenotazione, numero_posti, turnoID FROM prenotazione WHERE mail_prenotazione = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $data->mail_prenotazione);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $localeID, $data_prenotazione, $numero_posti, $turnoID);

    $userArray = array();

    while (mysqli_stmt_fetch($stmt)) {
      $userArray[] = array(
        "localeID" => $localeID,
        "data_prenotazione" => $data_prenotazione,
        "numero_posti" => $numero_posti,
        "turnoID" => $turnoID
      );
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    echo json_encode($userArray);

  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(array("message" => "errore server - " . $e->getMessage()));
  }


  //postiMax tipologia id_comune

} else {
  http_response_code(405);
  echo json_encode(array("message" => "Metodo non consentito."));
}