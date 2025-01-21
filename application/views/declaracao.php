
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
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Por favor, informe o CPF sem traço e pontos." required>
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

            <hr class="mb-4">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="same-address">
              <label class="custom-control-label" for="same-address">.</label>
            </div>
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="save-info">
              <label class="custom-control-label" for="save-info">.</label>
            </div>
            <hr class="mb-4">

            
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
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.min.js"></script>

  </body>
</html>
