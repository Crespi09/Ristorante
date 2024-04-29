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
  if (!empty($data->mail) && !empty($data->nome) && !empty($data->cognome) && !empty($data->data_nascita) && !empty($data->cell) && !empty($data->password)) {
    try {
      $conn = mysqli_connect($db->host, $db->user, $db->password, $db->db_name);

      if (!$conn) {
        throw new Exception("Connessione al database fallita: " . mysqli_connect_error());
      }


      $update_query = "UPDATE cliente SET nome = ?, cognome=?,  cell = ?,  password = ? WHERE mail = ?";
      $update_stmt = mysqli_prepare($conn, $update_query);
      mysqli_stmt_bind_param($update_stmt, 'sss', $data->nome, $data->cognome, $data->cell, $data->password, $data->mail);
      mysqli_stmt_execute($update_stmt);

      mysqli_stmt_close($update_stmt);
      mysqli_close($conn);

      echo json_encode(array("message" => "Dati cliente modificati con successo."));
    } catch (Exception $e) {
      http_response_code(401);
      echo json_encode(array("message" => $e->getMessage()));
    }
  } else {
    http_response_code(400);
    echo json_encode(array("message" => "Dati incompleti per la modifica del profilo cliente."));
  }

} else {
  http_response_code(405);
  echo json_encode(array("message" => "Metodo non consentito."));
}
?>