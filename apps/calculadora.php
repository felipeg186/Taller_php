<?php

session_start();

class Calculadora {
    private float $a;
    private float $b;
    private string $operacion;

    public function __construct(float $a, float $b, string $operacion) {
        $this->a         = $a;
        $this->b         = $b;
        $this->operacion = $operacion;
    }

    public function calcular(): float|string {
        switch ($this->operacion) {
            case 'suma':
                return $this->a + $this->b;
            case 'resta':
                return $this->a - $this->b;
            case 'multiplicacion':
                return $this->a * $this->b;
            case 'division':
                if ($this->b == 0) return 'Error: división por cero';
                return $this->a / $this->b;
            case 'porcentaje':
                return ($this->a * $this->b) / 100;
            default:
                return 'Operación desconocida';
        }
    }

    public function getExpresion(): string {
        $simbolos = [
            'suma'          => '+',
            'resta'         => '−',
            'multiplicacion'=> '×',
            'division'      => '÷',
            'porcentaje'    => '% de',
        ];
        $s = $simbolos[$this->operacion] ?? '?';
        return "{$this->a} {$s} {$this->b}";
    }
}


if (!isset($_SESSION['historial'])) {
    $_SESSION['historial'] = [];
}

$resultado  = null;
$expresion  = '';
$error      = '';
$numA = $numB = $op = '';


if (isset($_POST['borrar_historial'])) {
    $_SESSION['historial'] = [];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calcular'])) {
    $numA = trim($_POST['num_a'] ?? '');
    $numB = trim($_POST['num_b'] ?? '');
    $op   = trim($_POST['operacion'] ?? '');

    if (!is_numeric($numA) || !is_numeric($numB)) {
        $error = 'Ingresa valores numéricos válidos en ambos campos.';
    } elseif ($op === '') {
        $error = 'Selecciona una operación.';
    } else {
        $calc      = new Calculadora((float)$numA, (float)$numB, $op);
        $resultado = $calc->calcular();
        $expresion = $calc->getExpresion();

        if (!is_string($resultado)) {
            
            array_unshift($_SESSION['historial'], [
                'expr'   => $expresion,
                'result' => $resultado,
                'time'   => date('H:i:s'),
            ]);
            if (count($_SESSION['historial']) > 20) {
                array_pop($_SESSION['historial']);
            }
        } else {
            $error = $resultado;
            $resultado = null;
        }
    }
}

$opNombres = [
    'suma'           => 'Suma (+)',
    'resta'          => 'Resta (−)',
    'multiplicacion' => 'Multiplicación (×)',
    'division'       => 'División (÷)',
    'porcentaje'     => 'Porcentaje (A% de B)',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>07 — Calculadora</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<header>
    <h1>Calculadora</h1>
    <a href="../index.php">← Menú</a>
</header>

<nav>
    <a href="../index.php">Inicio</a>
    <a href="acronimo.php">01 Acrónimo</a>
    <a href="fibonacci.php">02 Fibonacci</a>
    <a href="estadistica.php">03 Estadística</a>
    <a href="conjuntos.php">04 Conjuntos</a>
    <a href="binario.php">05 Binario</a>
    <a href="arbol.php" >06 Árbol</a>
    <a href="calculadora.php" class="active">07 Calculadora</a>
</nav>

<main>
    <h2 class="page-title">07 — Calculadora</h2>
    <p class="subtitle">Operaciones básicas con historial de sesión.</p>

    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="form-box">
        <form method="POST" action="">
            <div class="set-columns">
                <div class="form-group">
                    <label for="num_a">Número A</label>
                    <input
                        type="number"
                        id="num_a"
                        name="num_a"
                        step="any"
                        placeholder="Ej: 25"
                        value="<?php echo htmlspecialchars($numA); ?>"
                    >
                </div>
                <div class="form-group">
                    <label for="num_b">Número B</label>
                    <input
                        type="number"
                        id="num_b"
                        name="num_b"
                        step="any"
                        placeholder="Ej: 4"
                        value="<?php echo htmlspecialchars($numB); ?>"
                    >
                </div>
            </div>
            <div class="form-group">
                <label for="operacion">Operación</label>
                <select id="operacion" name="operacion">
                    <option value="">— Selecciona —</option>
                    <?php foreach ($opNombres as $val => $nombre): ?>
                    <option value="<?php echo $val; ?>" <?php echo ($op === $val ? 'selected' : ''); ?>>
                        <?php echo $nombre; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="submit" name="calcular" value="Calcular →">
        </form>
    </div>

    <?php if ($resultado !== null): ?>
    <div class="result-box">
        <h3>Resultado</h3>
        <p class="result-expr"><?php echo htmlspecialchars($expresion); ?></p>
        <div class="result-value"><?php echo htmlspecialchars((string)round($resultado, 10)); ?></div>
    </div>
    <?php endif; ?>

    
    <div class="history-box">
        <div class="history-header">
            <h3>Historial de operaciones</h3>
            <?php if (!empty($_SESSION['historial'])): ?>
            <form method="POST" action="" class="history-clear-form">
                <button type="submit" name="borrar_historial" class="secondary btn-clear">
                    Borrar historial
                </button>
            </form>
            <?php endif; ?>
        </div>

        <?php if (empty($_SESSION['historial'])): ?>
            <p class="history-empty">Aún no hay operaciones registradas.</p>
        <?php else: ?>
            <?php foreach ($_SESSION['historial'] as $h): ?>
            <div class="history-item">
                <span><?php echo htmlspecialchars($h['expr']); ?> = <strong><?php echo htmlspecialchars((string)round($h['result'], 10)); ?></strong></span>
                <span class="history-time"><?php echo $h['time']; ?></span>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<footer>&copy; <?php echo date('Y'); ?> — PHP POO</footer>
</body>
</html>
