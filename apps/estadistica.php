<?php


class Estadistica {
    private array $numeros;

    public function __construct(array $numeros) {
        $this->numeros = $numeros;
    }

    public function promedio(): float {
        return array_sum($this->numeros) / count($this->numeros);
    }

    public function mediana(): float {
        $sorted = $this->numeros;
        sort($sorted);
        $n = count($sorted);
        $mid = (int)floor($n / 2);
        if ($n % 2 === 0) {
            return ($sorted[$mid - 1] + $sorted[$mid]) / 2;
        }
        return $sorted[$mid];
    }

    public function moda(): array {
        $frecuencia = array_count_values(array_map('strval', $this->numeros));
        $maxFrec    = max($frecuencia);
        
        if ($maxFrec === 1) return [];
        $modas = [];
        foreach ($frecuencia as $val => $frec) {
            if ($frec === $maxFrec) {
                $modas[] = (float)$val;
            }
        }
        return $modas;
    }

    public function getNumeros(): array {
        return $this->numeros;
    }
}

$promedio  = null;
$mediana   = null;
$moda      = null;
$numeros   = [];
$error     = '';
$inputRaw  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputRaw = trim($_POST['numeros'] ?? '');
    
    $partes = preg_split('/[\s,;]+/', $inputRaw);
    $validos = [];
    $hayError = false;
    foreach ($partes as $p) {
        $p = trim($p);
        if ($p === '') continue;
        if (!is_numeric($p)) {
            $hayError = true;
            break;
        }
        $validos[] = (float)$p;
    }
    if ($hayError) {
        $error = 'Todos los valores deben ser números reales. Sepáralos con comas o espacios.';
    } elseif (count($validos) < 2) {
        $error = 'Ingresa al menos 2 números.';
    } else {
        $est      = new Estadistica($validos);
        $promedio = $est->promedio();
        $mediana  = $est->mediana();
        $moda     = $est->moda();
        $numeros  = $validos;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>03 — Estadística</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<header>
    <h1>Estadística Básica</h1>
    <a href="../index.php">← Menú</a>
</header>

<nav>
   <a href="../index.php">Inicio</a>
    <a href="acronimo.php">01 Acrónimo</a>
    <a href="fibonacci.php">02 Fibonacci</a>
    <a href="estadistica.php" class="active">03 Estadística</a>
    <a href="conjuntos.php">04 Conjuntos</a>
    <a href="binario.php">05 Binario</a>
    <a href="arbol.php">06 Árbol</a>
    <a href="calculadora.php">07 Calculadora</a>
</nav>

<main>
    <h2 class="page-title">03 — Promedio, Mediana y Moda</h2>
    <p class="subtitle">Ingresa los números separados por comas o espacios. La cantidad la defines tú.</p>

    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="form-box">
        <form method="POST" action="">
            <div class="form-group">
                <label for="numeros">Serie de números reales</label>
                <textarea
                    id="numeros"
                    name="numeros"
                    placeholder="Ej: 4, 7.5, 2, 9, 4, 3.2, 7.5"
                ><?php echo htmlspecialchars($inputRaw); ?></textarea>
            </div>
            <input type="submit" value="Calcular →">
        </form>
    </div>

    <?php if ($promedio !== null): ?>
    <div class="result-box">
        <h3>Resultados — <?php echo count($numeros); ?> números</h3>
        <div class="result-item">
            <span class="result-label">Promedio (Media aritmética)</span>
            <strong><?php echo round($promedio, 4); ?></strong>
        </div>
        <div class="result-item">
            <span class="result-label">Mediana</span>
            <strong><?php echo round($mediana, 4); ?></strong>
        </div>
        <div class="result-item">
            <span class="result-label">Moda</span>
            <strong>
                <?php
                if (empty($moda)) {
                    echo 'Sin moda (todos los valores son únicos)';
                } else {
                    echo implode(', ', $moda);
                }
                ?>
            </strong>
        </div>
        <p class="stats-sorted">
            Datos ordenados: <?php
                $ord = $numeros;
                sort($ord);
                echo implode(', ', $ord);
            ?>
        </p>
    </div>
    <?php endif; ?>
</main>

<footer>&copy; <?php echo date('Y'); ?> — PHP POO</footer>
</body>
</html>
