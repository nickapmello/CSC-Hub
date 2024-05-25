<?php
function gerarMatricula($conn) {
    $prefixo = "24";

    // busca a ultima matricula
    $sql = "SELECT MAX(matricula) AS ultima_matricula FROM (
                SELECT matricula FROM aluno
                UNION ALL
                SELECT matricula FROM professor
            ) AS todas_matriculas";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ultimaMatricula = $row['ultima_matricula'];
        $ultimoNumero = (int) substr($ultimaMatricula, 2); 
        $novoNumero = $ultimoNumero + 1;
    } else {
        $novoNumero = 1;
    }

    // verifica 4 digitos
    $novoNumeroStr = str_pad($novoNumero, 4, "0", STR_PAD_LEFT);

    return $prefixo . $novoNumeroStr;
}


