function showForm(formName) {
    document.getElementById('createForm').style.display = 'none';
    document.getElementById('createFormProfessor').style.display = 'none';
    document.getElementById('createFormSecretaria').style.display = 'none';
    document.getElementById('updateForm').style.display = 'none';
    document.getElementById('userTypeButtons').style.display = 'none';
    document.querySelector('.menu').style.display = 'none';
    
    if (formName === 'create') {
        document.getElementById('userTypeButtons').style.display = 'block';
    } else if (formName === 'update') {
        document.getElementById('updateForm').style.display = 'block';
    }

    document.querySelector('.back-button').style.display = 'inline-block';
}


function setUserType(type) {
    // Esconder os botões de escolha de tipo de usuário
    document.getElementById('userTypeButtons').style.display = 'none';

    // Esconder todos os formulários primeiro
    document.getElementById('createForm').style.display = 'none';
    document.getElementById('createFormProfessor').style.display = 'none';
    document.getElementById('createFormSecretaria').style.display = 'none';

    var form;
    switch (type) {
        case 'aluno':
            form = document.getElementById('createForm');
            break;
        case 'professor':
            form = document.getElementById('createFormProfessor');
            break;
        case 'secretaria':
            form = document.getElementById('createFormSecretaria');
            break;
    }

    if (form) {
        form.style.display = 'block';  // Mostra o formulário de Aluno, Professor ou Secretaria
        document.querySelector('.back-button').style.display = 'inline-block'; // Garante que o botão 'Voltar' está visível
    }
}


function resetForm() {
    document.querySelector('.menu').style.display = 'block';
    document.getElementById('createForm').style.display = 'none';
    document.getElementById('createFormProfessor').style.display = 'none';
    document.getElementById('createFormSecretaria').style.display = 'none';
    document.getElementById('updateForm').style.display = 'none';
    document.querySelector('.back-button').style.display = 'none';
    window.location.href = 'http://localhost/estagio/xxx/index.html'; // Redireciona para a página principal
}


