<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplicaciones PHP</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<header>
    <h1>Apps PHP — POO</h1>
    <span style="font-size:0.75rem;letter-spacing:2px;color:#aaa;">PORTAFOLIO DE EJERCICIOS</span>
</header>

<nav>
    <a href="index.php" class="active">Inicio</a>
    <a href="apps/acronimo.php">01 Acrónimo</a>
    <a href="apps/fibonacci.php">02 Fibonacci / Factorial</a>
    <a href="apps/estadistica.php">03 Estadística</a>
    <a href="apps/conjuntos.php">04 Conjuntos</a>
    <a href="apps/binario.php">05 Binario</a>
    <a href="apps/arbol.php">06 Árbol Binario</a>
    <a href="apps/calculadora.php">07 Calculadora</a>
</nav>

<main>
    <h2 class="page-title">Menú Principal</h2>
    <p class="subtitle">Selecciona una aplicación para comenzar</p>

    <div class="app-grid">
        <a href="apps/acronimo.php" class="app-card">
            <span class="num">01</span>
            <h3>Conversor de Acrónimo</h3>
            <p>Convierte un nombre largo en su acrónimo. Soporta espacios y guiones como separadores.</p>
        </a>
        <a href="apps/fibonacci.php" class="app-card">
            <span class="num">02</span>
            <h3>Fibonacci / Factorial</h3>
            <p>Calcula la sucesión de Fibonacci o el factorial de un número e imprime la serie completa.</p>
        </a>
        <a href="apps/estadistica.php" class="app-card">
            <span class="num">03</span>
            <h3>Estadística</h3>
            <p>Calcula el promedio, la mediana y la moda de una serie de números reales ingresados.</p>
        </a>
        <a href="apps/conjuntos.php" class="app-card">
            <span class="num">04</span>
            <h3>Operaciones de Conjuntos</h3>
            <p>Dados dos conjuntos A y B, calcula la unión, intersección, A−B y B−A.</p>
        </a>
        <a href="apps/binario.php" class="app-card">
            <span class="num">05</span>
            <h3>Conversor a Binario</h3>
            <p>Convierte un número entero a su representación en sistema binario.</p>
        </a>
        <a href="apps/arbol.php" class="app-card">
            <span class="num">06</span>
            <h3>Árbol Binario</h3>
            <p>Construye y visualiza un árbol binario a partir de dos recorridos: preorden, inorden o postorden.</p>
        </a>
        <a href="apps/calculadora.php" class="app-card">
            <span class="num">07</span>
            <h3>Calculadora</h3>
            <p>Calculadora con operaciones básicas: suma, resta, multiplicación, división y porcentaje. Guarda historial.</p>
        </a>
    </div>
</main>

<footer>
    &copy; <?php echo date('Y'); ?> — PHP POO — HTML + CSS - FELIPE GUALTEROS
</footer>

</body>
</html>
