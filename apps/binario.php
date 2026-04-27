<?php

class ConversorBinario {
    private int $numero;

    public function __construct(int $numero) {
        $this->numero = $numero;
    }

    public function convertir(): string {
        if ($this->numero === 0) return '0';

        $n      = abs($this->numero);
        $bits   = [];
        while ($n > 0) {
            $bits[] = $n % 2;
            $n      = (int)floor($n / 2);
        }
        $binario = implode('', array_reverse($bits));
        return ($this->numero < 0 ? '-' : '') . $binario;
    }

    public function getPasos(): array {
        if ($this->numero === 0) return [[0, 0, 0]];
        $n     = abs($this->numero);
        $pasos = [];
        while ($n > 0) {
            $pasos[] = [$n, (int)floor($n / 2), $n % 2];
            $n        = (int)floor($n / 2);
        }
        return $pasos;
    }

    public function getNumero(): int {
        return $this->numero;
    }
}

$binario  = null;
$pasos    = [];
$error    = '';
$inputNum = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputNum = trim($_POST['numero'] ?? '');
    if (!preg_match('/^-?\d+$/', $inputNum)) {
        $error = 'Ingresa un número entero válido (puede ser negativo).';
    } else {
        $conv    = new ConversorBinario((int)$inputNum);
        $binario = $conv->convertir();
        $pasos   = $conv->getPasos();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>05 — Conversor Binario</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<header>
    <h1>Conversor a Binario</h1>
    <a href="../index.php">← Menú</a>
</header>

<nav>
    <a href="../index.php">Inicio</a>
    <a href="acronimo.php">01 Acrónimo</a>
    <a href="fibonacci.php">02 Fibonacci</a>
    <a href="estadistica.php">03 Estadística</a>
    <a href="conjuntos.php">04 Conjuntos</a>
    <a href="binario.php" class="active">05 Binario</a>
    <a href="arbol.php">06 Árbol</a>
    <a href="calculadora.php">07 Calculadora</a>
</nav>

<main>
    <h2 class="page-title">05 — Entero a Binario</h2>
    <p class="subtitle">Convierte un número entero a su representación en sistema binario (base 2).</p>

    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="form-box">
        <form method="POST" action="">
            <div class="form-group">
                <label for="numero">Número entero</label>
                <input
                    type="number"
                    id="numero"
                    name="numero"
                    placeholder="Ej: 42"
                    value="<?php echo htmlspecialchars($inputNum); ?>"
                >
            </div>
            <input type="submit" value="Convertir →">
        </form>
    </div>

    <?php if ($binario !== null): ?>
    <div class="result-box">
        <h3><?php echo htmlspecialchars($inputNum); ?> en binario</h3>
        <div class="result-value"><?php echo htmlspecialchars($binario); ?></div>
        <p class="result-verify">
            Verificación PHP: <?php echo decbin((int)$inputNum); ?>
        </p>
    </div>

    <?php if (!empty($pasos) && (int)$inputNum !== 0): ?>
    <div class="steps-section">
        <h3 class="steps-title">Proceso de divisiones sucesivas</h3>
        <div class="form-box form-box--no-padding">
            <div class="result-item result-item--header">
                <span class="result-label">Número</span>
                <span class="result-label">Cociente</span>
                <span class="result-label">Residuo (bit)</span>
            </div>
            <?php foreach ($pasos as $paso): ?>
            <div class="result-item result-item--row">
                <span><?php echo $paso[0]; ?></span>
                <span><?php echo $paso[1]; ?></span>
                <strong><?php echo $paso[2]; ?></strong>
            </div>
            <?php endforeach; ?>
        </div>
        <p class="steps-note">
            Los bits se leen de abajo hacia arriba → <strong><?php echo htmlspecialchars($binario); ?></strong>
        </p>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</main>

<footer>&copy; <?php echo date('Y'); ?> — PHP POO</footer>
</body>
</html>
