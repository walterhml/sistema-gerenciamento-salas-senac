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

class SalaDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM sala");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class DocenteDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM docente");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class CursoDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM curso");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class SubareaDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM subarea");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM turma WHERE Id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $turma = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($turma) {
                return $turma;
            } else {
                return null; // Handle case where no turma found for the ID
            }
        } catch (PDOException $e) {
            // Handle database errors here
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
}

class ReservaDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM reserva");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Lógica de agrupamento das reservas
$reservaDAO = new ReservaDAO();
$reservas = $reservaDAO->getAll();

$manha = [];
$tarde = [];
$noite = [];

$turmaDAO = new TurmaDAO();

foreach ($reservas as $reserva) {
    $turma = $turmaDAO->getById($reserva['Turma_ID']);
    if ($turma) {
        switch ($turma['Periodo']) {
            case 'Manhã':
                $manha[] = $reserva;
                break;
            case 'Tarde':
                $tarde[] = $reserva;
                break;
            case 'Noite':
                $noite[] = $reserva;
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas de Sala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>Reservas de Sala</h1>
        <div class="row">
            <div class="col-md-4">
                <h2>Manhã</h2>
                <ul class="list-group">
                    <?php if (count($manha) > 0): ?>
                        <?php foreach ($manha as $reserva): ?>
                            <li class="list-group-item">
                                Reserva ID: <?php echo $reserva['Id']; ?><br>
                                Sala ID: <?php echo $reserva['Sala_ID']; ?><br>
                                Status: <?php echo $reserva['Status']; ?><br>
                                Data Início: <?php echo $reserva['Data_Inicio']; ?><br>
                                Data Fim: <?php echo $reserva['Data_FIM']; ?><br>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item">Nenhuma reserva para este período.</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md-4">
                <h2>Tarde</h2>
                <ul class="list-group">
                    <?php if (count($tarde) > 0): ?>
                        <?php foreach ($tarde as $reserva): ?>
                            <li class="list-group-item">
                                Reserva ID: <?php echo $reserva['Id']; ?><br>
                                Sala ID: <?php echo $reserva['Sala_ID']; ?><br>
                                Status: <?php echo $reserva['Status']; ?><br>
                                Data Início: <?php echo $reserva['Data_Inicio']; ?><br>
                                Data Fim: <?php echo $reserva['Data_FIM']; ?><br>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item">Nenhuma reserva para este período.</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md-4">
                <h2>Noite</h2>
                <ul class="list-group">
                    <?php if (count($noite) > 0): ?>
                        <?php foreach ($noite as $reserva): ?>
                            <li class="list-group-item">
                                Reserva ID: <?php echo $reserva['Id']; ?><br>
                                Sala ID: <?php echo $reserva['Sala_ID']; ?><br>
                                Status: <?php echo $reserva['Status']; ?><br>
                                Data Início: <?php echo $reserva['Data_Inicio']; ?><br>
                                Data Fim: <?php echo $reserva['Data_FIM']; ?><br>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item">Nenhuma reserva para este período.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>