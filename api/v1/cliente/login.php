<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../../config.php";

$db = new Database();


//l'ho fatta post perchè mi è stato consigliato per la sicurezza dei dati dell'utente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->mail) && !empty($data->password)) {
        try {
            $conn = mysqli_connect($db->host, $db->user, $db->password, $db->db_name);

            if (!$conn) {
                throw new Exception("Connessione al database fallita: " . mysqli_connect_error());
            }

            $query = "SELECT mail, nome, cognome FROM cliente WHERE mail = ? AND password = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'ss', $data->mail, $data->password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                mysqli_stmt_bind_result($stmt, $mail, $nome, $cognome);
                mysqli_stmt_fetch($stmt);

                $user = array(
                    "mail" => $mail,
                    "nome" => $nome,
                    "cognome" => $cognome
                );

                mysqli_stmt_close($stmt);
                mysqli_close($conn);

                echo json_encode($user);
            } else {
                throw new Exception("Credenziali non valide.");
            }
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(array("message" => $e->getMessage()));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Dati di accesso non validi."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Metodo non consentito."));
}
?>