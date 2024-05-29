function showForm(formName) {
    var createForm = document.getElementById('createForm');
    var createFormProfessor = document.getElementById('createFormProfessor');
    var createFormSecretaria = document.getElementById('createFormSecretaria');
    var searchForm = document.getElementById('searchForm');
    var userTypeButtons = document.getElementById('userTypeButtons');

    // Esconder todos os formulários
    if (createForm) createForm.style.display = 'none';
    if (createFormProfessor) createFormProfessor.style.display = 'none';
    if (createFormSecretaria) createFormSecretaria.style.display = 'none';
    if (searchForm) searchForm.style.display = 'none';
    if (userTypeButtons) userTypeButtons.style.display = 'none';
    document.querySelector('.menu').style.display = 'none';

    // Mostrar o formulário relevante
    if (formName === 'create') {
        if (userTypeButtons) userTypeButtons.style.display = 'block';
        updateFormButtonText('create');
    } else if (formName === 'update') {
        if (searchForm) searchForm.style.display = 'block';
        updateFormButtonText('update');
    }

    document.querySelector('.back-button').style.display = 'inline-block';
}

function setUserType(type) {
    var createForm = document.getElementById('createForm');
    var createFormProfessor = document.getElementById('createFormProfessor');
    var createFormSecretaria = document.getElementById('createFormSecretaria');
    var userTypeButtons = document.getElementById('userTypeButtons');

    // Esconder os botões de escolha de tipo de usuário
    if (userTypeButtons) userTypeButtons.style.display = 'none';

    // Esconder todos os formulários primeiro
    if (createForm) createForm.style.display = 'none';
    if (createFormProfessor) createFormProfessor.style.display = 'none';
    if (createFormSecretaria) createFormSecretaria.style.display = 'none';

    // Mostrar o formulário correto
    var form;
    switch (type) {
        case 'aluno':
            form = createForm;
            break;
        case 'professor':
            form = createFormProfessor;
            break;
        case 'secretaria':
            form = createFormSecretaria;
            break;
    }

    if (form) {
        form.style.display = 'block';
        document.querySelector('.back-button').style.display = 'inline-block';
    }
}

function updateFormButtonText(action) {
    var createForm = document.getElementById('createForm');
    var createFormProfessor = document.getElementById('createFormProfessor');
    var createFormSecretaria = document.getElementById('createFormSecretaria');

    var buttons = [
        createForm.querySelector('button[type="submit"]'),
        createFormProfessor.querySelector('button[type="submit"]'),
        createFormSecretaria.querySelector('button[type="submit"]')
    ];

    buttons.forEach(button => {
        if (button) {
            if (action === 'create') {
                button.textContent = button.textContent.replace('Atualizar', 'Registrar');
            } else if (action === 'update') {
                button.textContent = button.textContent.replace('Registrar', 'Atualizar');
            }
        }
    });
}

function resetForm() {
    // Adicionar confirmação antes de resetar o formulário
    var confirmation = confirm("Você realmente deseja cancelar as edições?");
    if (confirmation) {
        document.querySelector('.menu').style.display = 'block';
        var createForm = document.getElementById('createForm');
        var createFormProfessor = document.getElementById('createFormProfessor');
        var createFormSecretaria = document.getElementById('createFormSecretaria');
        var searchForm = document.getElementById('searchForm');
        var userTypeButtons = document.getElementById('userTypeButtons');

        if (createForm) {
            createForm.style.display = 'none';
            createForm.reset(); // Limpa o formulário de aluno
        }
        if (createFormProfessor) {
            createFormProfessor.style.display = 'none';
            createFormProfessor.reset(); // Limpa o formulário de professor
        }
        if (createFormSecretaria) {
            createFormSecretaria.style.display = 'none';
            createFormSecretaria.reset(); // Limpa o formulário de secretaria
        }
        if (searchForm) {
            searchForm.style.display = 'none'; // Oculta o formulário de busca
            searchForm.reset(); // Limpa o formulário de busca
        }
        if (userTypeButtons) {
            userTypeButtons.style.display = 'none'; // Oculta os botões de tipo de usuário
        }
        document.querySelector('.back-button').style.display = 'none';
        updateFormButtonText('create'); // Resetar o texto do botão para 'Registrar'
    }
}

document.getElementById('searchForm').addEventListener('submit', function (e) {
    e.preventDefault();
    var matricula = this.querySelector('[name="matricula"]').value;

    fetch('search_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'matricula': matricula
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            var form;
            var tipo = data.tipo;

            if (tipo === 'aluno') {
                form = document.getElementById('createForm');
            } else if (tipo === 'professor') {
                form = document.getElementById('createFormProfessor');
            } else if (tipo === 'secretaria') {
                form = document.getElementById('createFormSecretaria');
            }

            if (form) {
                // Esconder todos os formulários e o formulário de busca
                document.getElementById('createForm').style.display = 'none';
                document.getElementById('createFormProfessor').style.display = 'none';
                document.getElementById('createFormSecretaria').style.display = 'none';
                document.getElementById('searchForm').style.display = 'none';

                form.querySelector('[name="user_id"]').value = data.user_id;
                form.querySelector('[name="tipo"]').value = tipo;

                for (var key in data) {
                    if (form.querySelector('[name="' + key + '"]')) {
                        form.querySelector('[name="' + key + '"]').value = data[key];
                    }
                }

                form.style.display = 'block';
                document.querySelector('.menu').style.display = 'none';
                document.querySelector('.back-button').style.display = 'inline-block';
                updateFormButtonText('update'); // Alterar o texto do botão para 'Atualizar'
            }
        }
    })
    .catch(error => console.error('Error:', error));
});
