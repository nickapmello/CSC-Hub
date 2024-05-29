<?php
function gerarMatricula() {
    $prefixo = "2410";
    $numero = mt_rand(100000, 999999);
    return $prefixo . $numero;
}
?>
