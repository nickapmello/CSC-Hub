<?php
function gerarMatricula($conn) {
    $prefixo = "24";
    $sql = "SELECT MAX(matricula) AS ultima_matricula FROM usuario";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ultimaMatricula = $row['ultima_matricula'];
        $ultimoNumero = (int) substr($ultimaMatricula, 2);
        $novoNumero = $ultimoNumero + 1;
    } else {
        $novoNumero = 1;
    }

    $novoNumeroStr = str_pad($novoNumero, 4, "0", STR_PAD_LEFT);
    return $prefixo . $novoNumeroStr;
}
?>
