function showForm(formName) {
    document.getElementById('createForm').style.display = 'none';
    document.getElementById('updateForm').style.display = 'none';
    document.querySelector('.menu').style.display = 'none';
    document.querySelector('.back-button').style.display = 'inline-block';
    document.getElementById(formName + 'Form').style.display = 'block';
}

function resetForm() {
    // Restaurar a interface inicial
    document.querySelector('.menu').style.display = 'block';
    document.querySelector('.back-button').style.display = 'none';
    document.getElementById('createForm').style.display = 'none';
    document.getElementById('updateForm').style.display = 'none';

    // Recarregar a página imediatamente
    location.reload();
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

document.getElementById('updateForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevenir o comportamento padrão de submissão do formulário

    // Limpar a mensagem de erro antes de iniciar a busca
    document.getElementById('message').innerText = '';

    var matricula = document.querySelector('input[name="matricula"]').value;
    var isProfessor = document.getElementById('professorCheckboxUpdate').checked;

    var formData = new FormData();
    formData.append('matricula', matricula);
    formData.append('isProfessor', isProfessor);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'search_user.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            var response = JSON.parse(xhr.responseText);
            if (response.error) {
                document.getElementById('message').innerText = response.error;
            } else {
                populateEditForm(response, isProfessor);
            }
        } else {
            document.getElementById('message').innerText = 'Erro na busca: ' + xhr.statusText;
        }
    };
    xhr.onerror = function() {
        document.getElementById('message').innerText = 'Erro na solicitação.';
    };
    xhr.send(formData);
});

function populateEditForm(userInfo, isProfessor) {
    var editForm = document.getElementById('editForm');
    editForm.style.display = 'block';
    editForm.elements['matricula'].value = userInfo.matricula;
    editForm.elements['nome'].value = userInfo.nome;
    editForm.elements['telefone'].value = userInfo.telefone;
    editForm.elements['endereco'].value = userInfo.endereco;
    editForm.elements['status'].value = userInfo.status;
    editForm.elements['senha'].value = ''; // Clear the password field
    editForm.elements['isProfessor'].value = isProfessor; // Store the isProfessor value
}

document.getElementById('editForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevenir o comportamento padrão de submissão do formulário

    var formData = new FormData(document.getElementById('editForm'));

    var senha = formData.get('senha');
    if (!senha) {
        formData.delete('senha'); // Remove the field if it's empty
    }

    formData.append('isProfessor', document.getElementById('professorCheckboxUpdate').checked);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_user.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            alert('Dados atualizados com sucesso!');
            resetForm();
        } else {
            document.getElementById('message').innerText = 'Erro ao atualizar: ' + xhr.statusText;
        }
    };
    xhr.onerror = function() {
        document.getElementById('message').innerText = 'Erro na solicitação.';
    };
    xhr.send(formData);
});








