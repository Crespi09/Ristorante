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





    $query = "SELECT locale.nome, prenotazione.localeID, prenotazione.data_prenotazione, prenotazione.numero_posti, turno.ora_inizio, turno.ora_fine 
      FROM prenotazione 
      INNER JOIN turno ON prenotazione.turnoID = turno.turnoID 
      INNER JOIN locale ON prenotazione.localeID = locale.localeID 
      WHERE prenotazione.mail_prenotazione = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $data->mail_prenotazione);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$nome_locale, $localeID, $data_prenotazione, $numero_posti, $ora_inizio,$ora_fine);

    $userArray = array();

    while (mysqli_stmt_fetch($stmt)) {
      $userArray[] = array(
        "nome" => $nome_locale,
        "data_prenotazione" => $data_prenotazione,
        "numero_posti" => $numero_posti,
        "turno" => $ora_inizio . "/" . $ora_fine,
        "localeID" => $localeID,
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