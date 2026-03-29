const ctx = document.getElementById('grafico');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [{
            label: 'Doações (R$)',
            data: [500, 750, 620, 910, 820, 1010, 970, 980, 1020, 860, 940, 1110],
            backgroundColor: 'rgba(46, 204, 113, 0.78)',
            borderColor: 'rgba(46, 204, 113, 1)',
            borderWidth: 2,
            borderRadius: 8,
            barPercentage: 0.55,
            categoryPercentage: 0.5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                grid: { display: false },
                ticks: { color: '#1a1a1a', font: { size: 16, weight: '700' } }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.09)' },
                ticks: { color: '#1a1a1a', font: { size: 14, weight: '600' } }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(255,255,255,0.95)',
                titleColor: '#111',
                bodyColor: '#111',
                borderColor: '#d3d3d3',
                borderWidth: 1,
                boxPadding: 8
            }
        }
    }
});

const ctxDoacoes = document.getElementById('grafico-doacoes');

new Chart(ctxDoacoes, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [
            {
                label: 'Ano anterior',
                data: [420, 670, 580, 840, 760, 880, 890, 920, 940, 790, 830, 910],
                borderColor: 'rgba(52, 152, 219, 1)',
                backgroundColor: 'rgba(52, 152, 219, 0.35)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: 'rgba(52, 152, 219, 1)'
            },
            {
                label: 'Ano atual',
                data: [500, 750, 620, 910, 820, 1010, 970, 980, 1020, 860, 940, 1110],
                borderColor: 'rgba(46, 204, 113, 1)',
                backgroundColor: 'rgba(46, 204, 113, 0.30)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: 'rgba(46, 204, 113, 1)'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                grid: { display: false },
                ticks: { color: '#1a1a1a', font: { size: 15, weight: '700' } }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.09)' },
                ticks: { color: '#1a1a1a', font: { size: 13, weight: '600' } }
            }
        },
        plugins: {
            legend: { position: 'top', labels: { color: '#1a1a1a', font: { size: 14 } } },
            tooltip: {
                backgroundColor: 'rgba(255,255,255,0.95)',
                titleColor: '#111',
                bodyColor: '#111',
                borderColor: '#d3d3d3',
                borderWidth: 1,
                boxPadding: 8
            }
        }
    }
});

let idosos = [];
let editIndex = null;

function carregarStorage() {
    const dados = JSON.parse(localStorage.getItem('idososCadastro') || '[]');
    idosos = dados;
    atualizarTabela();
    atualizarContador();
}

function atualizarStorage() {
    localStorage.setItem('idososCadastro', JSON.stringify(idosos));
    atualizarContador();
}

function calcularIdade(dataNascimento) {
    if (!dataNascimento) return null;
    const nascimento = new Date(dataNascimento);
    if (Number.isNaN(nascimento.getTime())) return null;
    const hoje = new Date();
    let idade = hoje.getFullYear() - nascimento.getFullYear();
    const mesAtual = hoje.getMonth();
    const diaAtual = hoje.getDate();
    if (mesAtual < nascimento.getMonth() || (mesAtual === nascimento.getMonth() && diaAtual < nascimento.getDate())) {
        idade -= 1;
    }
    return idade;
}

function atualizarContador() {
    const contador = document.getElementById('contador-idosos');
    if (contador) {
        contador.innerText = idosos.length;
    }
}

function irParaFicha() {
    const tipo = document.getElementById('tipo-ficha');
    const url = tipo ? tipo.value : 'FichaDeCadastro1.html';
    window.location.href = url;
}

window.addEventListener('load', carregarStorage);


// MOSTRAR LISTA
function toggleLista() {
    const lista = document.getElementById("lista-idosos");
    lista.style.display = lista.style.display === "block" ? "none" : "block";
    atualizarTabela();
}

// ABRIR FORM
function abrirForm() {
    document.getElementById("form-container").style.display = "block";
}

// SALVAR (ADD OU EDIT)
function salvar() {
    const idoso = {
        nome: nome.value,
        idade: idade.value,
        cpf: cpf.value,
        responsavel: responsavel.value
    };

    if (editIndex === null) {
        idosos.push(idoso);
    } else {
        idosos[editIndex] = idoso;
        editIndex = null;
    }

    atualizarStorage();
    limparForm();
    atualizarTabela();
}

// ATUALIZAR TABELA
function atualizarTabela() {
    const corpo = document.getElementById("corpo-tabela");
    corpo.innerHTML = "";

    idosos.forEach((idoso, index) => {
        const idadeValor = idoso.idade ? idoso.idade : calcularIdade(idoso.nascimento);
        corpo.innerHTML += `
        <tr>
            <td>${idoso.nome || ''}</td>
            <td>${idadeValor !== null ? idadeValor : '—'}</td>
            <td>${idoso.cpf || ''}</td>
            <td>${idoso.responsavel || ''}</td>
            <td>
                <button class="edit" onclick="editar(${index})">Editar</button>
                <button class="delete" onclick="excluir(${index})">Excluir</button>
            </td>
        </tr>
        `;
    });
}

// EDITAR
function editar(index) {
    const idoso = idosos[index];
    if (!idoso) {
        alert('Idoso não encontrado para edição. Por favor, atualize a página e tente novamente.');
        return;
    }
    localStorage.setItem('editarIdosoIndex', index);
    localStorage.setItem('editarIdosoData', JSON.stringify(idoso));
    window.location.href = 'FichaDeCadastro1.html';
}

// EXCLUIR
function excluir(index) {
    if (confirm("Deseja excluir este idoso?")) {
        idosos.splice(index, 1);
        atualizarStorage();
        atualizarTabela();
    }
}

// LIMPAR FORM
function limparForm() {
    nome.value = "";
    idade.value = "";
    cpf.value = "";
    responsavel.value = "";

    document.getElementById("form-container").style.display = "none";
}
