<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//require_once APPPATH . 'vendor/autoload.php'; // Caminho para o autoload.php

class Token extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function prd() {
        // URL da API
        $url = "https://srv448021.hstgr.cloud:8443/api/v1/auth/authenticate";

        // Caminho para o certificado SSL e chave privada

        $cert = "/etc/ssl/certificate.crt";
        $key = "/etc/ssl/private/private.key";

        // Dados para enviar no corpo da requisição
        $data = array(
            "email" => "user@user.com.br",
            "password" => "123456"
        );

        $payload = json_encode($data);

        // Inicializar a sessão cURL
        $ch = curl_init();

        // Configurar a URL e outras opções
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Verificar o certificado do servidor
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // Verificar o nome do host no certificado
        curl_setopt($ch, CURLOPT_SSLCERT, $cert); // Certificado SSL
        curl_setopt($ch, CURLOPT_SSLKEY, $key); // Chave privada
        curl_setopt($ch, CURLOPT_CAINFO, '/etc/ssl/ca-bundle.crt');
        curl_setopt($ch, CURLOPT_POST, true); // Enviar como POST
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Dados a serem enviados no corpo da requisição

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        // Executar a solicitação e obter a resposta
        $response = curl_exec($ch);

        // Verificar se ocorreu algum erro
        if ($response === false) {
            echo "Erro ao fazer a solicitação HTTP: " . curl_error($ch);
        } else {
            // Decodificar o JSON retornado para um array associativo
            $data = json_decode($response, true);

            // Verificar se a decodificação foi bem-sucedida
            if ($data === null) {
                echo "Erro ao decodificar a resposta JSON";
            } else {
                // Acessar o access_token
                $access_token = $data["access_token"];

                // Primeiro arquivo
                // Ler o conteúdo do arquivo environment.ts
                $file_content = file_get_contents('/home/rdpuser/projects/token/loja/src/environments/environment.ts');

                // Novo token
                $new_token = $access_token;

                // Encontrar a posição inicial do token atual no arquivo
                $start_pos = strpos($file_content, "'Bearer");

                // Encontrar a posição final do token atual no arquivo
                $end_pos = strpos($file_content, "'", $start_pos + 1);

                // Substituir o token atual pelo novo token
                $new_file_content = substr_replace($file_content, "'Bearer $new_token'", $start_pos, $end_pos - $start_pos + 1);

                // Escrever o conteúdo atualizado de volta ao arquivo
                file_put_contents('/home/rdpuser/projects/token/loja/src/environments/environment.ts', $new_file_content);


                // Segundo arquivo
                // Ler o conteúdo do arquivo environment.ts
                    $file_content = file_get_contents('/home/rdpuser/projects/token/loja/src/environments/environment.prod.ts');

                    // Encontrar a posição inicial do token atual no arquivo
                    $start_pos = strpos($file_content, "'Bearer");

                    // Encontrar a posição final do token atual no arquivo
                    $end_pos = strpos($file_content, "'", $start_pos + 1);

                    // Substituir o token atual pelo novo token
                    $new_file_content = substr_replace($file_content, "'Bearer $new_token'", $start_pos, $end_pos - $start_pos + 1);

                    // Escrever o conteúdo atualizado de volta ao arquivo
                    file_put_contents('/home/rdpuser/projects/token/loja/src/environments/environment.prod.ts', $new_file_content);

                    // Token de acesso pessoal
                    $api_key = getenv('GITHUB_API_KEY'); // Use a variável de ambiente para a API key
                    if (!$api_key) {
                        http_response_code(500); // Internal Server Error
                        echo "Erro: a chave da API não está configurada.";
                        return;
                    }
                    // URL do repositório com token de acesso pessoal incorporado
                    $repository_url = "https://$api_key@github.com/ecocursos0850/loja.git";
                    
                    // Configuração do nome de usuário
                    $username = "ecocursos";
                    
                    $dataHora = date("dmYHis");
                    // Executar git commit e push
                    $branch = "Hotfix/" . $dataHora;
                    $git_commands = [
                        "cd /home/rdpuser/projects/token/loja/",
                        "git checkout -b " . $branch,
                        "git add /home/rdpuser/projects/token/loja/src/environments/environment.prod.ts",
                        "git add /home/rdpuser/projects/token/loja/src/environments/environment.ts",
                        "git commit -m 'Atualização token " . $dataHora . "'",
                        "GIT_ASKPASS=echo GIT_USERNAME=$username git push --set-upstream $repository_url " . $branch,
                        "git checkout develop",
                        "git merge " . $branch,
                        "GIT_ASKPASS=echo GIT_USERNAME=$username git push $repository_url develop",
                        "git checkout main",
                        "GIT_ASKPASS=echo GIT_USERNAME=$username git pull $repository_url main", // Integra as mudanças do repositório remoto
                        "git merge develop",
                        "git checkout --theirs .", // Resolve conflitos automaticamente usando a versão remota
                        "git add .", // Marca os conflitos como resolvidos
                        "git commit -m 'Resolve merge conflicts using theirs strategy'", // Commit das resoluções de conflito
                        "GIT_ASKPASS=echo GIT_USERNAME=$username git push $repository_url main",
                        "git branch -d " . $branch, // Deletar a branch localmente
                        "GIT_ASKPASS=echo GIT_USERNAME=$username git push $repository_url --delete " . $branch // Deletar a branch remotamente
                    ];
                   
                    // Executar os comandos Git em uma sequência
                    foreach ($git_commands as $command) {
                        // Executar o comando Git
                        $output = shell_exec($command);
                        // Exibir a saída
                        //echo $output;
                    }
                    
                }
                // Imprimir o access token, se necessário
                //print("\nAccess Token: " . $access_token . "\n");
            }

        // Fechar a sessão cURL
        curl_close($ch);        
    }

    public function hmg() {
        // URL da API
        $url = "https://srv448021.hstgr.cloud:3000/api/v1/auth/authenticate";

        // Caminho para o certificado SSL e chave privada

        $cert = "/etc/ssl/certificate.crt";
        $key = "/etc/ssl/private/private.key";

        // Dados para enviar no corpo da requisição
        $data = array(
            "email" => "user@user.com.br",
            "password" => "123456"
        );

        $payload = json_encode($data);

        // Inicializar a sessão cURL
        $ch = curl_init();

        // Configurar a URL e outras opções
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Verificar o certificado do servidor
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // Verificar o nome do host no certificado
        curl_setopt($ch, CURLOPT_SSLCERT, $cert); // Certificado SSL
        curl_setopt($ch, CURLOPT_SSLKEY, $key); // Chave privada
        curl_setopt($ch, CURLOPT_CAINFO, '/etc/ssl/ca-bundle.crt');
        curl_setopt($ch, CURLOPT_POST, true); // Enviar como POST
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Dados a serem enviados no corpo da requisição

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        // Executar a solicitação e obter a resposta
        $response = curl_exec($ch);

        // Verificar se ocorreu algum erro
        if ($response === false) {
            echo "Erro ao fazer a solicitação HTTP: " . curl_error($ch);
        } else {
            // Decodificar o JSON retornado para um array associativo
            $data = json_decode($response, true);

            // Verificar se a decodificação foi bem-sucedida
            if ($data === null) {
                echo "Erro ao decodificar a resposta JSON";
            } else {
                // Acessar o access_token
                $access_token = $data["access_token"];

                // Primeiro arquivo
                // Ler o conteúdo do arquivo environment.ts
                $file_content = file_get_contents('/home/rdpuser/projects/loja/src/environments/environment.ts');

                // Novo token
                $new_token = $access_token;

                // Encontrar a posição inicial do token atual no arquivo
                $start_pos = strpos($file_content, "'Bearer");

                // Encontrar a posição final do token atual no arquivo
                $end_pos = strpos($file_content, "'", $start_pos + 1);

                // Substituir o token atual pelo novo token
                $new_file_content = substr_replace($file_content, "'Bearer $new_token'", $start_pos, $end_pos - $start_pos + 1);

                // Escrever o conteúdo atualizado de volta ao arquivo
                file_put_contents('/home/rdpuser/projects/loja/src/environments/environment.ts', $new_file_content);


                // Segundo arquivo
                // Ler o conteúdo do arquivo environment.ts
                    $file_content = file_get_contents('/home/rdpuser/projects/loja/src/environments/environment.prod.ts');

                    // Encontrar a posição inicial do token atual no arquivo
                    $start_pos = strpos($file_content, "'Bearer");

                    // Encontrar a posição final do token atual no arquivo
                    $end_pos = strpos($file_content, "'", $start_pos + 1);

                    // Substituir o token atual pelo novo token
                    $new_file_content = substr_replace($file_content, "'Bearer $new_token'", $start_pos, $end_pos - $start_pos + 1);

                    // Escrever o conteúdo atualizado de volta ao arquivo
                    file_put_contents('/home/rdpuser/projects/loja/src/environments/environment.prod.ts', $new_file_content);
                    
                }
                // Imprimir o access token, se necessário
                //print("\nAccess Token: " . $access_token . "\n");
            }

        // Fechar a sessão cURL
        curl_close($ch);        
    }
    
}
