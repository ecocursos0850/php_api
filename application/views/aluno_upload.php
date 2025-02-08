<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Alunos</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body>

    <h2>Upload de Arquivo Excel</h2>
    <input type="file" id="fileInput" />
    <button id="uploadBtn">Upload</button>
    
    <h2>Dados Importados</h2>
    <table id="alunosTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>CPF</th>
                <th>Email</th>
                <th>Parceiro</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <button id="confirmBtn" style="display:none;">Confirmar Atualização</button>

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
