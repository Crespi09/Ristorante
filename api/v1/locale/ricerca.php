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


    $query = "SELECT localeID, num_civico, via, postiMax, tipologia, id_comune, azienda_pIVA, nome FROM locale
            JOIN comuni ON locale.id_comune = comuni.id 
            WHERE 1 = 1 
            AND (comuni.id = ? OR ? IS NULL) 
            AND (locale.tipologia = ? OR ? IS NULL)
            AND (locale.nome LIKE ? OR ? IS NULL)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssss', $data->id_comune, $data->id_comune, $data->postiMax, $data->postiMax, $data->tipologia, $data->tipologia);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $localeID, $num_civico, $via, $postiMax, $tipologia, $id_comune, $azienda_pIVA, $nome);

    $userArray = array();

    while (mysqli_stmt_fetch($stmt)) {
      $userArray[] = array(
        "nome" => $nome,
        "localeID" => $localeID,
        "num_civico" => $num_civico,
        "via" => $via,
        "postiMax" => $postiMax,
        "tipologia" => $tipologia,
        "id_comune" => $id_comune,
        "azienda_pIVA" => $azienda_pIVA
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