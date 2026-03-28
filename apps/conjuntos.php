<?php

class Conjuntos {
    private array $a;
    private array $b;

    public function __construct(array $a, array $b) {
        $this->a = array_values(array_unique($a));
        $this->b = array_values(array_unique($b));
    }

    public function union(): array {
        return array_values(array_unique(array_merge($this->a, $this->b)));
    }

    public function interseccion(): array {
        return array_values(array_intersect($this->a, $this->b));
    }

    public function diferencia_AB(): array {
        return array_values(array_diff($this->a, $this->b));
    }

    public function diferencia_BA(): array {
        return array_values(array_diff($this->b, $this->a));
    }

    public function getA(): array { return $this->a; }
    public function getB(): array { return $this->b; }
}

function parsearConjunto(string $input): array {
    $partes  = preg_split('/[\s,;]+/', trim($input));
    $validos = [];
    foreach ($partes as $p) {
        $p = trim($p);
        if ($p === '') continue;
        if (is_numeric($p)) {
            $validos[] = (int)$p;
        }
    }
    return $validos;
}

$resultados = null;
$error      = '';
$inputA = $inputB = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputA = trim($_POST['conjunto_a'] ?? '');
    $inputB = trim($_POST['conjunto_b'] ?? '');

    $arrA = parsearConjunto($inputA);
    $arrB = parsearConjunto($inputB);

    if (empty($arrA) || empty($arrB)) {
        $error = 'Ingresa al menos un número entero en cada conjunto.';
    } else {
        $conj       = new Conjuntos($arrA, $arrB);
        $resultados = [
            'A'    => $conj->getA(),
            'B'    => $conj->getB(),
            'AuB'  => $conj->union(),
            'AnB'  => $conj->interseccion(),
            'A-B'  => $conj->diferencia_AB(),
            'B-A'  => $conj->diferencia_BA(),
        ];
    }
}

function fmt(array $arr): string {
    if (empty($arr)) return '∅ (vacío)';
    sort($arr);
    return '{ ' . implode(', ', $arr) . ' }';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>04 — Conjuntos</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<header>
    <h1>Operaciones de Conjuntos</h1>
    <a href="../index.php">← Menú</a>
</header>

<nav>
    <a href="../index.php">Inicio</a>
    <a href="acronimo.php">01 Acrónimo</a>
    <a href="fibonacci.php">02 Fibonacci</a>
    <a href="estadistica.php">03 Estadística</a>
    <a href="conjuntos.php" class="active">04 Conjuntos</a>
    <a href="binario.php">05 Binario</a>
    <a href="arbol.php">06 Árbol</a>
    <a href="calculadora.php">07 Calculadora</a>
</nav>

<main>
    <h2 class="page-title">04 — Conjuntos</h2>
    <p class="subtitle">Ingresa números enteros en cada conjunto separados por comas o espacios.</p>

    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="form-box">
        <form method="POST" action="">
            <div class="set-columns">
                <div class="form-group">
                    <label for="conjunto_a">Conjunto A</label>
                    <input
                        type="text"
                        id="conjunto_a"
                        name="conjunto_a"
                        placeholder="Ej: 1, 2, 3, 4, 5"
                        value="<?php echo htmlspecialchars($inputA); ?>"
                    >
                </div>
                <div class="form-group">
                    <label for="conjunto_b">Conjunto B</label>
                    <input
                        type="text"
                        id="conjunto_b"
                        name="conjunto_b"
                        placeholder="Ej: 3, 4, 5, 6, 7"
                        value="<?php echo htmlspecialchars($inputB); ?>"
                    >
                </div>
            </div>
            <input type="submit" value="Calcular →">
        </form>
    </div>

    <?php if ($resultados): ?>
    <div class="result-box">
        <h3>Resultados</h3>
        <div class="result-item">
            <span class="result-label">A</span>
            <span><?php echo fmt($resultados['A']); ?></span>
        </div>
        <div class="result-item">
            <span class="result-label">B</span>
            <span><?php echo fmt($resultados['B']); ?></span>
        </div>
        <div class="result-item">
            <span class="result-label">A ∪ B (Unión)</span>
            <span><?php echo fmt($resultados['AuB']); ?></span>
        </div>
        <div class="result-item">
            <span class="result-label">A ∩ B (Intersección)</span>
            <span><?php echo fmt($resultados['AnB']); ?></span>
        </div>
        <div class="result-item">
            <span class="result-label">A − B (Diferencia)</span>
            <span><?php echo fmt($resultados['A-B']); ?></span>
        </div>
        <div class="result-item">
            <span class="result-label">B − A (Diferencia)</span>
            <span><?php echo fmt($resultados['B-A']); ?></span>
        </div>
    </div>
    <?php endif; ?>
</main>

<footer>&copy; <?php echo date('Y'); ?> — PHP POO</footer>
</body>
</html>
