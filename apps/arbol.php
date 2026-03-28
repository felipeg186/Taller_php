<?php


class NodoArbol {
    public string $valor;
    public ?NodoArbol $izquierdo;
    public ?NodoArbol $derecho;

    public function __construct(string $valor) {
        $this->valor     = $valor;
        $this->izquierdo = null;
        $this->derecho   = null;
    }
}

class ArbolBinario {
    private ?NodoArbol $raiz = null;

    public function construirDesdePreInorden(array $preorden, array $inorden): void {
        $this->raiz = $this->reconstruirPreIn($preorden, $inorden);
    }

    private function reconstruirPreIn(array $pre, array $ino): ?NodoArbol {
        if (empty($pre) || empty($ino)) return null;
        $raizVal = $pre[0];
        $nodo    = new NodoArbol($raizVal);
        $idx     = array_search($raizVal, $ino);
        if ($idx === false) return $nodo;

        $inoIzq = array_slice($ino, 0, $idx);
        $inoDer = array_slice($ino, $idx + 1);
        $preIzq = array_slice($pre, 1, count($inoIzq));
        $preDer = array_slice($pre, 1 + count($inoIzq));

        $nodo->izquierdo = $this->reconstruirPreIn($preIzq, $inoIzq);
        $nodo->derecho   = $this->reconstruirPreIn($preDer, $inoDer);
        return $nodo;
    }

    public function construirDesdePostInorden(array $postorden, array $inorden): void {
        $this->raiz = $this->reconstruirPostIn($postorden, $inorden);
    }

    private function reconstruirPostIn(array $post, array $ino): ?NodoArbol {
        if (empty($post) || empty($ino)) return null;
        $raizVal = $post[count($post) - 1];
        $nodo    = new NodoArbol($raizVal);
        $idx     = array_search($raizVal, $ino);
        if ($idx === false) return $nodo;

        $inoIzq  = array_slice($ino, 0, $idx);
        $inoDer  = array_slice($ino, $idx + 1);
        $postIzq = array_slice($post, 0, count($inoIzq));
        $postDer = array_slice($post, count($inoIzq), count($inoDer));

        $nodo->izquierdo = $this->reconstruirPostIn($postIzq, $inoIzq);
        $nodo->derecho   = $this->reconstruirPostIn($postDer, $inoDer);
        return $nodo;
    }

    public function getPreorden(): array {
        $res = [];
        $this->preorden($this->raiz, $res);
        return $res;
    }

    public function getInorden(): array {
        $res = [];
        $this->inorden($this->raiz, $res);
        return $res;
    }

    public function getPostorden(): array {
        $res = [];
        $this->postorden($this->raiz, $res);
        return $res;
    }

    private function preorden(?NodoArbol $nodo, array &$res): void {
        if ($nodo === null) return;
        $res[] = $nodo->valor;
        $this->preorden($nodo->izquierdo, $res);
        $this->preorden($nodo->derecho, $res);
    }

    private function inorden(?NodoArbol $nodo, array &$res): void {
        if ($nodo === null) return;
        $this->inorden($nodo->izquierdo, $res);
        $res[] = $nodo->valor;
        $this->inorden($nodo->derecho, $res);
    }

    private function postorden(?NodoArbol $nodo, array &$res): void {
        if ($nodo === null) return;
        $this->postorden($nodo->izquierdo, $res);
        $this->postorden($nodo->derecho, $res);
        $res[] = $nodo->valor;
    }

    public function visualizar(): string {
        if ($this->raiz === null) return '';
        $lineas = [];
        $this->vizRecursivo($this->raiz, '', true, $lineas);
        return implode("\n", $lineas);
    }

    private function vizRecursivo(?NodoArbol $nodo, string $prefijo, bool $esRaiz, array &$lineas): void {
        if ($nodo === null) return;
        if ($esRaiz) {
            $lineas[] = '[ ' . $nodo->valor . ' ]';
        } else {
            $lineas[] = $prefijo . '[ ' . $nodo->valor . ' ]';
        }
        if ($nodo->izquierdo !== null || $nodo->derecho !== null) {
            if ($nodo->izquierdo !== null) {
                $conector = ($nodo->derecho !== null) ? '├─ IZQ: ' : '└─ IZQ: ';
                $lineas[] = $prefijo . ($esRaiz ? '' : '    ') . $conector . '[ ' . $nodo->izquierdo->valor . ' ]';
                $nuevoPref = $prefijo . ($esRaiz ? '' : '    ') . (($nodo->derecho !== null) ? '│       ' : '        ');
                $this->vizHijos($nodo->izquierdo, $nuevoPref, $lineas);
            }
            if ($nodo->derecho !== null) {
                $lineas[] = $prefijo . ($esRaiz ? '' : '    ') . '└─ DER: [ ' . $nodo->derecho->valor . ' ]';
                $nuevoPref = $prefijo . ($esRaiz ? '' : '    ') . '        ';
                $this->vizHijos($nodo->derecho, $nuevoPref, $lineas);
            }
        }
    }

    private function vizHijos(?NodoArbol $nodo, string $prefijo, array &$lineas): void {
        if ($nodo === null) return;
        if ($nodo->izquierdo !== null) {
            $conector = ($nodo->derecho !== null) ? '├─ IZQ: ' : '└─ IZQ: ';
            $lineas[] = $prefijo . $conector . '[ ' . $nodo->izquierdo->valor . ' ]';
            $nuevoP   = $prefijo . (($nodo->derecho !== null) ? '│       ' : '        ');
            $this->vizHijos($nodo->izquierdo, $nuevoP, $lineas);
        }
        if ($nodo->derecho !== null) {
            $lineas[] = $prefijo . '└─ DER: [ ' . $nodo->derecho->valor . ' ]';
            $this->vizHijos($nodo->derecho, $prefijo . '        ', $lineas);
        }
    }
}

function parsearRecorrido(string $input): array {
    // Acepta → o -> o comas o espacios como separadores
    $limpio = str_replace(['→', '->', '→'], ',', $input);
    $partes = preg_split('/[\s,;]+/', trim($limpio));
    $nodos  = [];
    foreach ($partes as $p) {
        $p = trim($p);
        if ($p !== '') $nodos[] = strtoupper($p);
    }
    return $nodos;
}

$arbol    = null;
$visual   = '';
$error    = '';
$pre = $ino = $post = '';
$preArr = $inoArr = $postArr = [];
$preRes = $inoRes = $postRes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pre  = trim($_POST['preorden']  ?? '');
    $ino  = trim($_POST['inorden']   ?? '');
    $post = trim($_POST['postorden'] ?? '');

    $preArr  = !empty($pre)  ? parsearRecorrido($pre)  : [];
    $inoArr  = !empty($ino)  ? parsearRecorrido($ino)  : [];
    $postArr = !empty($post) ? parsearRecorrido($post) : [];

    $provistos = ((!empty($preArr)) ? 1 : 0) + ((!empty($inoArr)) ? 1 : 0) + ((!empty($postArr)) ? 1 : 0);

    if ($provistos < 2) {
        $error = 'Debes ingresar al menos dos recorridos.';
    } else {
        $arbol = new ArbolBinario();
        if (!empty($preArr) && !empty($inoArr)) {
            $arbol->construirDesdePreInorden($preArr, $inoArr);
        } elseif (!empty($postArr) && !empty($inoArr)) {
            $arbol->construirDesdePostInorden($postArr, $inoArr);
        } else {
            $error = 'Para reconstruir el árbol, uno de los recorridos debe ser el INORDEN.';
            $arbol = null;
        }

        if ($arbol) {
            $preRes  = $arbol->getPreorden();
            $inoRes  = $arbol->getInorden();
            $postRes = $arbol->getPostorden();
            $visual  = $arbol->visualizar();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>06 — Árbol Binario</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<header>
    <h1>Árbol Binario</h1>
    <a href="../index.php">← Menú</a>
</header>

<nav>
    <a href="../index.php">Inicio</a>
    <a href="acronimo.php">01 Acrónimo</a>
    <a href="fibonacci.php">02 Fibonacci</a>
    <a href="estadistica.php">03 Estadística</a>
    <a href="conjuntos.php">04 Conjuntos</a>
    <a href="binario.php">05 Binario</a>
    <a href="arbol.php" class="active">06 Árbol</a>
    <a href="calculadora.php">07 Calculadora</a>
</nav>

<main>
    <h2 class="page-title">06 — Árbol Binario</h2>
    <p class="subtitle">Ingresa mínimo dos recorridos (el inorden es obligatorio). Usa → o comas como separadores.</p>

    <?php if ($error): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="form-box">
        <form method="POST" action="">
            <div class="form-group">
                <label for="preorden">Preorden (opcional)</label>
                <input
                    type="text"
                    id="preorden"
                    name="preorden"
                    placeholder="Ej: A → B → D → E → C"
                    value="<?php echo htmlspecialchars($pre); ?>"
                >
            </div>
            <div class="form-group">
                <label for="inorden">Inorden (requerido)</label>
                <input
                    type="text"
                    id="inorden"
                    name="inorden"
                    placeholder="Ej: D → B → E → A → C"
                    value="<?php echo htmlspecialchars($ino); ?>"
                >
            </div>
            <div class="form-group">
                <label for="postorden">Postorden (opcional)</label>
                <input
                    type="text"
                    id="postorden"
                    name="postorden"
                    placeholder="Ej: D → E → B → C → A"
                    value="<?php echo htmlspecialchars($post); ?>"
                >
            </div>
            <input type="submit" value="Construir árbol →">
        </form>
    </div>

    <?php if ($arbol && empty($error)): ?>

    <div class="tree-container">
        <p style="font-size:0.75rem;letter-spacing:1px;text-transform:uppercase;color:#666;margin-bottom:12px;">Estructura del árbol</p>
        <pre><?php echo htmlspecialchars($visual); ?></pre>
    </div>

    <div class="result-box" style="margin-top:20px;">
        <h3>Recorridos generados</h3>
        <div class="result-item">
            <span class="result-label">Preorden</span>
            <span><?php echo implode(' → ', $preRes); ?></span>
        </div>
        <div class="result-item">
            <span class="result-label">Inorden</span>
            <span><?php echo implode(' → ', $inoRes); ?></span>
        </div>
        <div class="result-item">
            <span class="result-label">Postorden</span>
            <span><?php echo implode(' → ', $postRes); ?></span>
        </div>
    </div>

    <?php endif; ?>

    <!-- Ejemplo -->
    <div style="margin-top:32px;">
        <div class="alert info">
            <strong>Ejemplo:</strong><br>
            Preorden: A → B → D → E → C<br>
            Inorden: D → B → E → A → C<br>
            Postorden: D → E → B → C → A
        </div>
    </div>
</main>

<footer>&copy; <?php echo date('Y'); ?> — PHP POO</footer>
</body>
</html>
