<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ECOCURSOS - Solicitação de Declaração</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/form-validation.css" rel="stylesheet">
    
    <style>
      .file-preview {
        margin-top: 10px;
        display: none;
      }
      .file-preview img {
        max-width: 200px;
        max-height: 200px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
      }
      .pdf-preview {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        margin-top: 10px;
        display: none;
      }
      .pdf-icon {
        color: #d63031;
        font-size: 24px;
        margin-right: 10px;
      }
    </style>
  </head>

  <body class="bg-light">

    <div class="container">
      <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="https://www.ecocursos.com.br/assets/images/Logo1.png" width="262.5" height="77.47">
        <h2>Solicitação de Declaração</h2>
      </div>

      <div class="row">
        <div class="col-md-12 order-md-1">
          <div class="alert alert-danger" role="alert">
              Para a emissão da declaração, é necessário o pagamento de uma taxa no valor de R$ 50,00.<br>
              💰 Forma de pagamento: PIX – Chave: 10.930.297/0001-48<br>
              📎 Importante: O comprovante de pagamento deve ser anexado ao pedido para que a solicitação seja processada.<br>
              Após concluir a solicitação aguarde que nossos colaboradores estarão atentos ao seu pedido. <br>
              Dúvidas? Entre em contato com nosso suporte através do telefone +55 (17) 3422-3725.
          </div>
          <form class="card p-2" action="https://srv448021.hstgr.cloud/php_api/declaracao/gravar" method="POST" enctype="multipart/form-data">
              <input type="hidden" id="aluno_id" name="aluno_id" value="<?php echo $matricula_info->aluno_id; ?>">
              <input type="hidden" id="matricula_id" name="matricula_id" value="<?php echo $matricula_info->id; ?>">
              <input type="hidden" id="curso_id" name="curso_id" value="<?php echo $matricula_info->curso_id; ?>">

              <div class="row">
                  <div class="col-md-12 mb-3">
                      <label for="cpf">CPF do Aluno</label>
                      <input type="text" class="form-control" id="cpf" name="cpf" 
                            value="<?php echo $matricula_info->cpf; ?>" 
                            placeholder="Por favor, informe o CPF sem traço e pontos." 
                            maxlength="11" required readonly>
                      <div class="invalid-feedback">
                          Campo obrigatório.
                      </div>
                  </div>
                  
                  <div class="col-md-12 mb-3">
                      <label for="nome">Nome do Aluno</label>
                      <input type="text" class="form-control" id="nome" 
                            value="<?php echo $matricula_info->nome; ?>" 
                            readonly>
                  </div>
                  
                  <div class="col-md-12 mb-3">
                      <label for="curso">Curso</label>
                      <input type="text" class="form-control" id="curso" 
                            value="<?php echo $matricula_info->curso_titulo; ?> (<?php echo $matricula_info->carga_horaria; ?> horas)" 
                            readonly>
                  </div>
                  
                  <div class="col-md-6 mb-3">
                      <label for="inicioPeriodo">Data Início (Obrigatório)</label>
                      <input type="date" class="form-control" id="inicioPeriodo" name="inicioPeriodo" required>
                      <div class="invalid-feedback">
                          Campo obrigatório.
                      </div>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label for="finalPeriodo">Data Final (Obrigatório)</label>
                      <input type="date" class="form-control" id="finalPeriodo" name="finalPeriodo" required>
                      <div class="invalid-feedback">
                          Campo obrigatório.
                      </div>
                  </div>
              </div>

              <div class="mb-3">
                  <label for="file">Anexar Comprovante no formato PDF ou imagem (Obrigatório)</label>
                  <div class="input-group">
                      <input type="file" class="form-control" id="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                      <div class="invalid-feedback" style="width: 100%;">
                          Campo obrigatório.
                      </div>
                  </div>
                  
                  <!-- Preview de Imagem -->
                  <div class="file-preview" id="imagePreview">
                      <img id="previewImage" src="#" alt="Preview da imagem" class="img-thumbnail">
                  </div>
                  
                  <!-- Identificação de PDF -->
                  <div class="pdf-preview" id="pdfPreview">
                      <span class="pdf-icon">📄</span>
                      <span id="pdfFileName">Arquivo PDF selecionado</span>
                  </div>
              </div>
              
              <hr class="mb-4">
              <button class="btn btn-primary btn-lg btn-block" type="submit">Continuar</button>
          </form>
        </div>
      </div>

      <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; <?=date("Y")?> Ecocursos</p>
      </footer>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    
    <script>
      $(document).ready(function() {
          // Função para exibir preview do arquivo selecionado
          $('#file').change(function() {
              var file = this.files[0];
              var fileType = file.type;
              var fileName = file.name;
              
              // Esconde ambos os previews primeiro
              $('#imagePreview').hide();
              $('#pdfPreview').hide();
              
              // Verifica se é uma imagem
              if (fileType.match('image.*')) {
                  var reader = new FileReader();
                  
                  reader.onload = function(e) {
                      $('#previewImage').attr('src', e.target.result);
                      $('#imagePreview').show();
                  }
                  
                  reader.readAsDataURL(file);
              } 
              // Verifica se é PDF
              else if (fileName.toLowerCase().endsWith('.pdf')) {
                  $('#pdfFileName').text(fileName);
                  $('#pdfPreview').show();
              }
          });
          
          // Validação do formulário
          $('form').submit(function(e) {
              var fileInput = $('#file')[0];
              if (fileInput.files.length === 0) {
                  e.preventDefault();
                  alert('Por favor, selecione um arquivo para upload.');
                  return false;
              }
              
              var file = fileInput.files[0];
              var validExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
              var fileExtension = file.name.split('.').pop().toLowerCase();
              
              if ($.inArray(fileExtension, validExtensions) === -1) {
                  e.preventDefault();
                  alert('Por favor, selecione um arquivo PDF ou imagem (JPG, JPEG, PNG).');
                  return false;
              }
              
              return true;
          });
      });
    </script>
  </body>
</html>