function showForm() {
    document.querySelector('.menu').style.display = 'none'; // Hide main menu
    document.getElementById('userTypeButtons').style.display = 'flex'; // Show user type buttons
}

function showUserTypeSelection() {
    document.querySelector('.menu').style.display = 'none';
    document.getElementById('userTypeButtons').style.display = 'flex';
}

function showUserForm(userType) {
    document.getElementById('userTypeButtons').style.display = 'none';
    document.getElementById('createForm').style.display = 'block';
    document.querySelector('.back-button').style.display = 'block'; // Ensure the back button is visible
}

function resetForm() {
    document.querySelector('.menu').style.display = 'block';
    document.getElementById('createForm').style.display = 'none';
    document.getElementById('userTypeButtons').style.display = 'none';
    document.querySelector('.back-button').style.display = 'none';
    location.reload(); // Optionally reload the page to reset state
}

document.getElementById('createForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    var formData = new FormData(this); // Capture form data

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'create_user.php', true); // Make sure the path is correct

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            alert('Registro criado com sucesso!');
            window.location.href = 'index.html'; // Redirect if needed
        } else {
            alert('Erro ao criar registro: ' + xhr.statusText);
        }
    };

    xhr.onerror = function() {
        alert('Erro na solicitação.');
    };

    xhr.send(formData); // Send the form data
});



document.getElementById('submitButton').addEventListener('click', function() {
    var telefone = document.querySelector('input[name="telefone"]').value.trim();
    if (telefone.length !== 11 || isNaN(telefone)) {
        alert('O número de telefone deve ter exatamente 11 dígitos e deve ser numérico.');
        return;
    }
    var formData = new FormData(document.getElementById('createForm'));
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'create_user.php', true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            if (xhr.responseText.includes("sucesso")) {
                alert("Registro criado com sucesso!");
                window.location.href = 'http://localhost/estagio/index.html'; // home
            } else {
                alert(xhr.responseText); 
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

document.getElementById('editForm').addEventListener('submit', function(event) {
    event.preventDefault(); 
    var formData = new FormData(document.getElementById('editForm'));
    var senha = formData.get('senha');
    if (!senha) {
        formData.delete('senha'); 
    }
    formData.append('isProfessor', document.getElementById('professorCheckboxUpdate').checked);
    console.log('FormData:', formData); // log que verifica os dados
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

document.getElementById('updateForm').addEventListener('submit', function(event) {
    event.preventDefault(); 
    document.getElementById('message').innerText = ''; //limpa mensagem de busca
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