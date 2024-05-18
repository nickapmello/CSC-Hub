function showForm(formName) {
    document.getElementById('createForm').style.display = 'none';
    document.getElementById('updateForm').style.display = 'none';
    document.querySelector('.menu').style.display = 'none';
    document.querySelector('.back-button').style.display = 'inline-block';
    document.getElementById(formName + 'Form').style.display = 'block';
}

function resetForm() {
    document.querySelector('.menu').style.display = 'block';
    document.querySelector('.back-button').style.display = 'none';
    document.getElementById('createForm').style.display = 'none';
    document.getElementById('updateForm').style.display = 'none';
}

document.getElementById('submitButton').addEventListener('click', function() {
    var formData = new FormData(document.getElementById('createForm'));
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'create_user.php', true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            if (xhr.responseText.includes("sucesso")) {
                alert("Registro criado com sucesso!");
                window.location.href = 'http://localhost/estagio/index.html'; // Redireciona para a home se sucesso
            } else {
                alert(xhr.responseText); // Mostra apenas o alerta se houver erro
            }
        } else {
            alert('Falha ao enviar: ' + xhr.statusText);
        }
    };
    xhr.onerror = function () {
        alert('Erro na solicitação.');
    };
    xhr.send(formData);
});
