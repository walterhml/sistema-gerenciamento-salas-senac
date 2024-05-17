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

    public function update($reservaId, $status) {
        $stmt = $this->db->prepare("UPDATE reserva SET Status = :status WHERE Id = :reserva_id");
        $stmt->bindParam(':reserva_id', $reservaId, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status);
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
    $novoStatus = $_POST['novo_status'];

    $atualizado = $reservaDAO->update($reservaId, $novoStatus);
    if ($atualizado) {
        echo "Reserva atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar a reserva.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>Atualizar Reserva</h1>
        <form method="POST" action="atualizar_reserva.php">
            <div class="mb-3">
                <label for="reserva_id" class="form-label">ID da Reserva</label>
                <input type="text" class="form-control" id="reserva_id" name="reserva_id" required>
            </div>
            <div class="mb-3">
                <label for="novo_status" class="form-label">Novo Status</label>
                <select class="form-select" id="novo_status" name="novo_status" required>
                    <option value="Livre">Livre</option>
                    <option value="Reservado">Reservado</option>
                    <option value="Manutencao">Manutenção</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Reserva</button>
        </form>
    </div>
</body>
</html>
