
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
          <h4 class="mb-3">Dados da Declaração</h4>
          <form class="card p-2" action="https://srv448021.hstgr.cloud/php_api/declaracao/gravar" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="aluno_id" name="aluno_id">
            <input type="hidden" id="matricula_id" name="matricula_id">
            <input type="hidden" id="curso_id" name="curso_id">

            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="firstName">CPF do Aluno</label>
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Por favor, informe o CPF sem traço e pontos." maxlength="11" required>
                <div class="invalid-feedback">
                  Campo obrigatório.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="firstName">Data Início</label>
                <input type="date" class="form-control" id="inicio_periodo" name="inicio_periodo" placeholder="" required>
                <div class="invalid-feedback">
                  Campo obrigatório.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName">Last name</label>
                <input type="date" class="form-control" id="final_periodo" name="final_periodo" placeholder="" required>
                <div class="invalid-feedback">
                  Campo obrigatório.
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="username">Comprovante</label>
              <div class="input-group">
                <input type="file" class="form-control" id="file" name="file" required>
                <div class="invalid-feedback" style="width: 100%;">
                  Campo obrigatório.
                </div>
              </div>
            </div>
            <div class="bloco-matriculas" style="display: none">
                <hr class="mb-4">
                    <h4 class="mb-3 bloco-matriculas">Selecione a matrícula</h4>
                    <div id="lista-cursos" class="custom-control custom-checkbox">
                    <!-- Aqui será preenchido dinamicamente pelo jQuery -->
                    </div>
                <hr class="mb-4">
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
                        // Limpa a lista de cursos anterior
                        $('#lista-cursos').empty();

                        // Adiciona os cursos ao HTML
                        response.forEach(function(curso) {
                            var checkboxHtml = `
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="custom-control-input" id="curso-${curso.matricula_id}" data-aluno-id="${curso.aluno_id}" data-matricula-id="${curso.matricula_id}" data-curso-id="${curso.curso_id}">
                                    <label class="custom-control-label" for="curso-${curso.matricula_id}">${curso.titulo}</label>
                                </div>
                            `;
                            $('#lista-cursos').append(checkboxHtml);
                            $(".bloco-matriculas").removeAttr("style");
                        });
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
