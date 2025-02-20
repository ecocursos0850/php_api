
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ECOCURSOS - Solicitação de Declaração</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="form-validation.css" rel="stylesheet">
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
            <input type="hidden" id="aluno_id" name="aluno_id">
            <input type="hidden" id="matricula_id" name="matricula_id">
            <input type="hidden" id="curso_id" name="curso_id">

            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="firstName">CPF do Aluno (Obrigatório)</label>
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Por favor, informe o CPF sem traço e pontos." maxlength="11" required>
                <div class="invalid-feedback">
                  Campo obrigatório.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="firstName">Data Início (Obrigatório)</label>
                <input type="date" class="form-control" id="inicioPeriodo" name="inicioPeriodo" placeholder="" required>
                <div class="invalid-feedback">
                  Campo obrigatório.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName">Data Final (Obrigatório)</label>
                <input type="date" class="form-control" id="finalPeriodo" name="finalPeriodo" placeholder="" required>
                <div class="invalid-feedback">
                  Campo obrigatório.
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="username">Anexar Comprovante no formato PDF (Obrigatório)</label>
              <div class="input-group">
                <input type="file" class="form-control" id="file" name="file" required>
                <div class="invalid-feedback" style="width: 100%;">
                  Campo obrigatório.
                </div>
              </div>
            </div>
            <div class="bloco-matriculas" style="display: none">
            <div class="d-block my-3">
                <label for="country">Selecione a matrícula a qual deseja a solicitação de declaração (Obrigatório)</label>
                    <select class="custom-select d-block w-100" id="matricula" name="matricula" required=""></select>
                    <div class="invalid-feedback">
                    Campo obrigatório.
                    </div>
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

    <!-- Seu código JavaScript -->
    <script>
        $(document).ready(function() {
            $('#matricula').on('change', function() {
                // Obtém o <option> selecionado
                var selectedOption = $(this).find('option:selected');

                // Define os valores nos campos ocultos
                $('#aluno_id').val(selectedOption.data('aluno-id'));
                $('#curso_id').val(selectedOption.data('curso-id'));
                $('#matricula_id').val(selectedOption.val());
            });

            // Escuta o evento de digitação no campo CPF
            $('#cpf').on('input', function() {
                var cpf = $(this).val().replace(/\D/g, ''); // Remove qualquer caractere não numérico
                if (cpf.length === 11) { // Verifica se o CPF tem 11 dígitos
                    // Executa a busca das matrículas do aluno e atualiza a lista de cursos
                    buscarCursos(cpf);
                }
            });

            function buscarCursos(cpf) {
                // A partir do CPF capturado, você pode fazer uma chamada AJAX para o servidor
                $.ajax({
                    url: "https://srv448021.hstgr.cloud/php_api/alunos/get_matriculas_por_cpf/" + cpf,
                    method: 'GET',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (Array.isArray(response) && response.length > 0) {
                            $(".bloco-matriculas").removeAttr("style","display: none");
                            // Limpa a lista de cursos anterior
                            $('#lista-cursos').empty();
                            // Adiciona os cursos ao HTML
                            response.forEach(function(curso) {
                                var optionHtml = `
                                    <option value="${curso.matricula_id}" data-aluno-id="${curso.aluno_id}" data-curso-id="${curso.curso_id}">
                                        ${curso.titulo}
                                    </option>
                                `;
                                $('#matricula').append(optionHtml);
                            });

                            // Seleciona a primeira opção e dispara o evento change
                            if (response.length > 0) {
                                $('#matricula').val(response[0].matricula_id).change();
                            }
                        }else{
                            $(".bloco-matriculas").attr("style","display: none");
                        }
                    },
                    error: function() {
                        $(".bloco-matriculas").attr("style", "display: none");
                        alert('Erro ao buscar cursos.');
                    }
                });
            }
        });

    </script>
  </body>
</html>
