<?php

class Database {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            $host = 'localhost';
            $dbname = 'reserva_sala';
            $username = 'root';
            $password = '';

            self::$instance = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}

class ReservaDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function delete($reservaId) {
        $stmt = $this->db->prepare("DELETE FROM reserva WHERE ID = :reserva_id");
        $stmt->bindParam(':reserva_id', $reservaId, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

$reservaDAO = new ReservaDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservaId = $_POST['reserva_id'];

    $deletado = $reservaDAO->delete($reservaId);
    if ($deletado) {
        echo "Reserva deletada com sucesso!";
    } else {
        echo "Erro ao deletar a reserva.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>Deletar Reserva</h1>
        <form method="POST" action="deletar_reserva.php">
            <div class="mb-3">
                <label for="reserva_id" class="form-label">ID da Reserva a ser Deletada</label>
                <input type="number" class="form-control" id="reserva_id" name="reserva_id" required>
            </div>
            <button type="submit" class="btn btn-danger">Deletar Reserva</button>
        </form>
    </div>
</body>
</html>
