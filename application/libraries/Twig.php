<?php
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Twig {
    protected $twig;

    public function __construct() {
        // Carregar o autoloader do Composer
        require_once APPPATH . '../vendor/autoload.php';

        // Configurar o carregador do Twig com o caminho das views
        $loader = new FilesystemLoader(APPPATH . 'views');
        $this->twig = new Environment($loader, [
            'cache' => APPPATH . 'cache/twig',
            'auto_reload' => true,
        ]);
    }

    /**
     * Renderiza um template Twig
     *
     * @param string $template Nome do template
     * @param array $data Dados a serem passados para o template
     * @return string Conteúdo renderizado do template
     */
    public function render($template, $data = []) {
        try {
            return $this->twig->render($template, $data);
        } catch (\Twig\Error\LoaderError $e) {
            // Manejo de erro do carregador
            log_message('error', 'LoaderError: ' . $e->getMessage());
            show_error('Erro ao carregar o template Twig: ' . $e->getMessage(), 500);
        } catch (\Twig\Error\RuntimeError $e) {
            // Manejo de erro em tempo de execução
            log_message('error', 'RuntimeError: ' . $e->getMessage());
            show_error('Erro durante a execução do template Twig: ' . $e->getMessage(), 500);
        } catch (\Twig\Error\SyntaxError $e) {
            // Manejo de erro de sintaxe
            log_message('error', 'SyntaxError: ' . $e->getMessage());
            show_error('Erro de sintaxe no template Twig: ' . $e->getMessage(), 500);
        }
    }
}
