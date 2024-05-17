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

class TurmaDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM turma");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class ReservaDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function insert($turmaId, $dataInicio, $dataFim) {
        $stmt = $this->db->prepare("INSERT INTO reserva (Turma_ID, Data_Inicio, Data_FIM) VALUES (:turma_id, :data_inicio, :data_fim)");
        $stmt->bindParam(':turma_id', $turmaId, PDO::PARAM_INT);
        $stmt->bindParam(':data_inicio', $dataInicio);
        $stmt->bindParam(':data_fim', $dataFim);
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

$turmaDAO = new TurmaDAO();
$turmas = $turmaDAO->getAll();
$reservaDAO = new ReservaDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $turmaId = $_POST['turma_id'];
    $dataInicio = $_POST['data_inicio'];
    $dataFim = $_POST['data_fim'];

    $inserido = $reservaDAO->insert($turmaId, $dataInicio, $dataFim);
    if ($inserido) {
        echo "Reserva inserida com sucesso!";
    } else {
        echo "Erro ao inserir a reserva.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>Inserir Reserva</h1>
        <form method="POST" action="inserir_reserva.php">
            <div class="mb-3">
                <label for="turma_id" class="form-label">Turma</label>
                <select class="form-select" id="turma_id" name="turma_id" required>
                    <?php foreach ($turmas as $turma): ?>
                        <option value="<?php echo $turma['Id']; ?>"><?php echo $turma['Cod_Turma']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="data_inicio" class="form-label">Data Início</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
            </div>
            <div class="mb-3">
                <label for="data_fim" class="form-label">Data Fim</label>
                <input type="date" class="form-control" id="data_fim" name="data_fim" required>
            </div>
            <button type="submit" class="btn btn-primary">Inserir Reserva</button>
        </form>
    </div>
</body>
</html>