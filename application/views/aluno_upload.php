<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECOCURSOS - Atualizar Afiliados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="text-center mb-4">
            <img src="https://www.ecocursos.com.br/assets/images/Logo1.png" width="262.5" height="77.47">
            <h2 class="mt-3">Atualizar Afiliados</h2>
        </div>

        <div class="card p-4">
            <h4 class="mb-3">Instruções:</h4>
            <p>Para garantir o correto processamento do arquivo, siga estas orientações:</p>
            <ul>
                <li>Selecione um arquivo <strong>Excel (.xlsx)</strong> contendo os dados organizados corretamente.</li>
                <li>O arquivo deve ter <strong>duas colunas</strong>:</li>
                <ul>
                    <li><strong>Coluna A</strong>: Deve conter o título <strong>"NOME"</strong> na célula A1 e os nomes dos afiliados abaixo.</li>
                    <li><strong>Coluna B</strong>: Deve conter o título <strong>"CPF"</strong> na célula B1 e os números de CPF abaixo.</li>
                </ul>
                <li>Todos os afiliados devem ter <strong>NOME e CPF preenchidos</strong>. Campos vazios não serão aceitos.</li>
                <li>Certifique-se de que o arquivo não contém colunas adicionais.</li>
            </ul>
            <div class="mb-3">
                <label for="fileInput" class="form-label"><strong>Selecione o arquivo Excel:</strong></label>
                <input type="file" class="form-control" id="fileInput" accept=".xlsx" required>
            </div>
            <button class="btn btn-primary btn-lg btn-block" id="uploadBtn">Pré-visualização</button>
        </div>

        <div class="mt-5">
            <h4>Dados Importados</h4>
            <table id="alunosTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>CPF</th>
                        <th>Email</th>
                        <th>Parceiro</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <button id="confirmBtn" class="btn btn-primary btn-lg btn-block" style="display:none;">Confirmar Atualização</button>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let alunosData = [];
            let table = $('#alunosTable').DataTable();

            $('#uploadBtn').on('click', function () {
                let fileInput = document.getElementById('fileInput').files[0];
                if (!fileInput) {
                    alert("Por favor, selecione um arquivo.");
                    return;
                }

                let formData = new FormData();
                formData.append("file", fileInput);

                fetch("<?= site_url('alunos/uploadExcel') ?>", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        alunosData = data.data;
                        table.clear().rows.add(alunosData.map(d => [d.cpf, d.email, d.parceiro_id])).draw();
                        $('#confirmBtn').show();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error("Erro:", error));
            });

            $('#confirmBtn').on('click', function () {
                if (alunosData.length === 0) {
                    alert("Nenhum dado para atualizar.");
                    return;
                }

                fetch("<?= site_url('aluno/atualizarParceiro') ?>", {
                    method: "POST",
                    body: JSON.stringify(alunosData),
                    headers: { "Content-Type": "application/json" }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        $('#confirmBtn').hide();
                        table.clear().draw();
                    }
                })
                .catch(error => console.error("Erro:", error));
            });
        });
    </script>
</body>
</html>
