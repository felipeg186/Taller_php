<?php


class ConversorAcronimo {
    private string $frase;

    public function __construct(string $frase) {
        $this->frase = $frase;
    }

    private function limpiarFrase(): string {
        $texto = str_replace('-', ' ', $this->frase);
        
        $texto = preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]/', '', $texto);
        return $texto;
    }

    public function convertir(): string {
        $limpia = $this->limpiarFrase();
        $palabras = preg_split('/\s+/', trim($limpia));
        $acronimo = '';
        foreach ($palabras as $palabra) {
            if (!empty($palabra)) {
                $acronimo .= mb_strtoupper(mb_substr($palabra, 0, 1));
            }
        }
        return $acronimo;
    }

    public function getFraseLimpia(): string {
        return $this->limpiarFrase();
    }
}

$resultado = null;
$acronimo  = '';
$error     = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $frase = trim($_POST['frase'] ?? '');
    if ($frase === '') {
        $error = 'Por favor ingresa una frase.';
    } else {
        $conversor = new ConversorAcronimo($frase);
        $acronimo  = $conversor->convertir();
        $resultado = $conversor->getFraseLimpia();
    }
}

$ejemplos = [
    'Programacion Avanzada'       => 'PA',
    'Hyper Text Markup Language'    => 'HTML',
    "Hypertext Preprocessor" => 'HP',
    
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>01 — Conversor de Acrónimo</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<header>
    <h1>Conversor de Acrónimo</h1>
    <a href="../index.php">← Menú</a>
</header>

<nav>
    <a href="../index.php">Inicio</a>
    <a href="acronimo.php" class="active">01 Acrónimo</a>
    <a href="fibonacci.php">02 Fibonacci</a>
    <a href="estadistica.php">03 Estadística</a>
    <a href="conjuntos.php">04 Conjuntos</a>
    <a href="binario.php">05 Binario</a>
    <a href="arbol.php">06 Árbol</a>
    <a href="calculadora.php">07 Calculadora</a>
</nav>

<main>
    <h2 class="page-title">01 — Acrónimo</h2>
    <p class="subtitle">Convierte un nombre largo en su acrónimo. Los guiones cuentan como separadores.</p>

    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="form-box">
        <form method="POST" action="">
            <div class="form-group">
                <label for="frase">Frase o nombre</label>
                <input
                    type="text"
                    id="frase"
                    name="frase"
                    placeholder="Ej: Liquid-crystal display"
                    value="<?php echo htmlspecialchars($_POST['frase'] ?? ''); ?>"
                >
            </div>
            <input type="submit" value="Convertir →">
        </form>
    </div>

    <?php if ($acronimo !== ''): ?>
    <div class="result-box">
        <h3>Resultado</h3>
        <div class="result-value"><?php echo htmlspecialchars($acronimo); ?></div>
        <p class="result-note">
            Frase procesada: <em><?php echo htmlspecialchars($resultado); ?></em>
        </p>
    </div>
    <?php endif; ?>

   
    <div class="section-extra">
        <h3 class="section-extra-title">Ejemplos</h3>
        <div class="form-box">
            <?php foreach ($ejemplos as $frase => $acr): ?>
                <div class="result-item">
                    <span><?php echo htmlspecialchars($frase); ?></span>
                    <strong><?php echo $acr; ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<footer>&copy; <?php echo date('Y'); ?> — PHP POO</footer>
</body>
</html>
