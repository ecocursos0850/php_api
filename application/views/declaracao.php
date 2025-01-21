<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Declaração</title>
</head>
<body>
    <h1>Solicitar Declaração</h1>
    <form action="https://srv448021.hstgr.cloud/php_api/declaracao/gravar" method="POST" enctype="multipart/form-data">
        <!-- Campo de data início -->
        <div>
            <label for="inicioPeriodo">Data Início:</label>
            <input type="date" id="inicioPeriodo" name="inicioPeriodo" required>
        </div>
        
        <!-- Campo de data fim -->
        <div>
            <label for="finalPeriodo">Data Fim:</label>
            <input type="date" id="finalPeriodo" name="finalPeriodo" required>
        </div>
        
        <!-- Campo para selecionar arquivo -->
        <div>
            <label for="file">Anexar Arquivo (PDF, JPG, JPEG, PNG):</label>
            <input type="file" id="file" name="file" accept=".pdf, .jpg, .jpeg, .png" required>
        </div>
        
        <!-- Campo oculto para aluno_id -->
        <input type="hidden" name="aluno_id" value="1"> <!-- Substitua pelo ID correto do aluno -->

        <!-- Campo oculto para curso_id -->
        <input type="hidden" name="curso_id" value="1"> <!-- Substitua pelo ID correto do curso -->

        <!-- Campo oculto para matricula_id -->
        <input type="hidden" name="matricula_id" value="1"> <!-- Substitua pelo ID correto da matrícula -->
        
        <!-- Botão de envio -->
        <div>
            <button type="submit">Solicitar Declaração</button>
        </div>
    </form>
</body>
</html>
