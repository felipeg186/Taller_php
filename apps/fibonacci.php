<?php


class Sucesion {
    private int $n;

    public function __construct(int $n) {
        $this->n = $n;
    }

    
    public function fibonacci(): array {
        if ($this->n <= 0) return [];
        $serie = [0];
        if ($this->n === 1) return $serie;
        $serie[] = 1;
        for ($i = 2; $i < $this->n; $i++) {
            $serie[] = $serie[$i - 1] + $serie[$i - 2];
        }
        return $serie;
    }

   
    public function factorial(): array {
        if ($this->n <= 0) return [1]; // 0! = 1
        $pasos = [];
        $acum  = 1;
        for ($i = 1; $i <= $this->n; $i++) {
            $acum *= $i;
            $pasos[] = $acum;
        }
        return $pasos;
    }

    public function factorialTotal(): string {
        $pasos = $this->factorial();
        return (string) end($pasos);
    }

    public function getN(): int {
        return $this->n;
    }
}

$serie     = [];
$operacion = '';
$error     = '';
$n         = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n         = trim($_POST['numero'] ?? '');
    $operacion = $_POST['operacion'] ?? '';

    if (!is_numeric($n) || (int)$n < 0) {
        $error = 'Por favor ingresa un número entero mayor o igual a 0.';
    } elseif ($operacion === '') {
        $error = 'Selecciona una operación.';
    } else {
        $suc = new Sucesion((int)$n);
        $serie = ($operacion === 'fibonacci')
            ? $suc->fibonacci()
            : $suc->factorial();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>02 — Fibonacci / Factorial</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<header>
    <h1>Fibonacci / Factorial</h1>
    <a href="../index.php">← Menú</a>
</header>

<nav>
    <a href="../index.php">Inicio</a>
    <a href="acronimo.php">01 Acrónimo</a>
    <a href="fibonacci.php" class="active">02 Fibonacci</a>
    <a href="estadistica.php">03 Estadística</a>
    <a href="conjuntos.php">04 Conjuntos</a>
    <a href="binario.php">05 Binario</a>
    <a href="arbol.php">06 Árbol</a>
    <a href="calculadora.php">07 Calculadora</a>
</nav>

<main>
    <h2 class="page-title">02 — Fibonacci / Factorial</h2>
    <p class="subtitle">Ingresa un número y elige la operación para ver la serie completa.</p>

    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="form-box">
        <form method="POST" action="">
            <div class="form-group">
                <label for="numero">Número (n)</label>
                <input
                    type="number"
                    id="numero"
                    name="numero"
                    min="0"
                    placeholder="Ej: 10"
                    value="<?php echo htmlspecialchars($n); ?>"
                >
            </div>
            <div class="form-group">
                <label for="operacion">Operación</label>
                <select id="operacion" name="operacion">
                    <option value="">— Selecciona —</option>
                    <option value="fibonacci"  <?php echo ($operacion === 'fibonacci'  ? 'selected' : ''); ?>>Sucesión de Fibonacci (primeros n términos)</option>
                    <option value="factorial"  <?php echo ($operacion === 'factorial'  ? 'selected' : ''); ?>>Factorial de n (pasos acumulados)</option>
                </select>
            </div>
            <input type="submit" value="Calcular →">
        </form>
    </div>

    <?php if (!empty($serie)): ?>
    <div class="result-box">
        <h3>
            <?php echo $operacion === 'fibonacci'
                ? "Fibonacci — primeros {$n} términos"
                : "Factorial — pasos de 1! a {$n}!"; ?>
        </h3>
        <div class="result-list">
            <?php
            foreach ($serie as $i => $val) {
                if ($operacion === 'fibonacci') {
                    echo "F({$i}) = {$val}";
                } else {
                    $k = $i + 1;
                    echo "{$k}! = {$val}";
                }
                echo ($i < count($serie) - 1) ? ' → ' : '';
            }
            ?>
        </div>
        <?php if ($operacion === 'factorial'): ?>
        <p style="margin-top:14px;font-size:0.85rem;color:#aaa;">
            Resultado final: <strong><?php echo end($serie); ?></strong>
        </p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</main>

<footer>&copy; <?php echo date('Y'); ?> — PHP POO</footer>
</body>
</html>
