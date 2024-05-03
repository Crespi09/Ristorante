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

  if (!empty($data->localeID)) {
    try {
      $conn = mysqli_connect($db->host, $db->user, $db->password, $db->db_name);

      if (!$conn) {
        throw new Exception("Connessione al database fallita: " . mysqli_connect_error());
      }
      //TODO nome ristorante
      $query = "SELECT num_civico, via, postiMax, tipologia, id_comune, azienda_pIVA FROM locale WHERE localeID = ?";
      $stmt = mysqli_prepare($conn, $query);
      mysqli_stmt_bind_param($stmt, 's', $data->localeID);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);

      if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $num_civico, $via, $postiMax, $tipologia, $id_comune, $azienda_pIVA);
        mysqli_stmt_fetch($stmt);

        $user = array(
          "num_civico" => $num_civico,
          "via" => $via,
          "postiMax" => $postiMax,
          "tipologia" => $tipologia,
          "id_comune" => $id_comune,
          "azienda_pIVA" => $azienda_pIVA
        );

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        echo json_encode($user);
      } else {
        throw new Exception("id locale non valido");
      }
    } catch (Exception $e) {
      http_response_code(401);
      echo json_encode(array("message" => $e->getMessage()));
    }
  } else {
    http_response_code(400);
    echo json_encode(array("message" => "nessun id mandato."));
  }
} else {
  http_response_code(405);
  echo json_encode(array("message" => "Metodo non consentito."));
}
?>