function showForm(formName) {
    if (formName === 'create') {
        document.querySelector('.menu').style.display = 'none';
        document.getElementById('userTypeButtons').style.display = 'block';
    } else {
        document.getElementById('createForm').style.display = 'none';
        document.getElementById('updateForm').style.display = 'none';
        document.querySelector('.menu').style.display = 'none';
        document.querySelector('.back-button').style.display = 'inline-block';
        document.getElementById(formName + 'Form').style.display = 'block';
    }
}

function setUserType(type) {
    document.getElementById('userTypeButtons').style.display = 'none';
    var form = document.getElementById('createForm');
    form.style.display = 'block';
    form.querySelector('input[name="tipo"]').value = type; // Assuming you have an input to hold this value
    // Additional logic to modify the form based on the type can go here
}

function resetForm() {
    document.querySelector('.menu').style.display = 'block';
    document.querySelector('.back-button').style.display = 'none';
    document.getElementById('createForm').style.display = 'none';
    document.getElementById('updateForm').style.display = 'none';
    location.reload(); //reload da page
}

function configureFormFields(type) { // botao aluno é selecionado, puxar o formulario certo
    var form = document.getElementById('createForm');
    form.innerHTML = '';  // Limpa qualquer conteúdo anterior
    if (type === 'aluno') {
        form.innerHTML = `
            <input type="text" name="nome_completo" placeholder="Nome Completo" style="width: 100%;">
            <input type="date" name="data_nascimento" placeholder="Data de Nascimento" style="width: 100%;">
            <input type="text" name="cpf" placeholder="CPF" style="width: 100%;">
            <input type="text" name="endereco_residencial" placeholder="Endereço Residencial" style="width: 100%;">
            <input type="text" name="telefone_contato" placeholder="Telefone de Contato" style="width: 100%;">
            <input type="email" name="email" placeholder="E-mail" style="width: 100%;">
            <textarea name="info_saude" placeholder="Informações de Saúde" style="width: 100%;"></textarea>
            <textarea name="documento_identidade" placeholder="Documento de Identidade" style="width: 100%;"></textarea>
            <input type="text" name="nome_pais" placeholder="Nome dos Pais" style="width: 100%;">
            <input type="text" name="telefone_pais" placeholder="Telefone dos Pais" style="width: 100%;">
            <input type="text" name="cpf_pais" placeholder="CPF dos Pais" style="width: 100%;">
            <select name="status" style="width: 100%;">
                <option value="Ativo">Ativo</option>
                <option value="Inativo">Inativo</option>
            </select>
            <input type="password" name="senha" placeholder="Senha" style="width: 100%;">
            <button type="submit">Registrar Aluno</button>
        `;
        form.style.display = 'block';
    }
    // Adicionar casos para outros tipos aqui
}


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
