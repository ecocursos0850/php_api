
````markdown
<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/seu-usuario/contratos-api/actions">
    <img src="https://github.com/seu-usuario/contratos-api/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Laravel Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

# 📄 Contratos API - Sistema de Cadastro de Contratos

API RESTful desenvolvida em **Laravel 12**, utilizando **Docker** e **PostgreSQL**, para o **gerenciamento de contratos, convênios e bancos**.

---

## 🧩 Tecnologias Utilizadas

- **Laravel 12**
- **PHP 8.3+**
- **PostgreSQL**
- **Docker e Docker Compose**
- **Composer**
- **Eloquent ORM**

---

## 📋 Pré-requisitos

Antes de iniciar, você precisa ter instalado:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Git](https://git-scm.com/)

---

## 🚀 Instalação e Execução

### 1️⃣ Clonar o projeto
```bash
git clone <url-do-repositorio>
cd contratos-api
````

### 2️⃣ Subir os containers

```bash
docker-compose up -d
```

### 3️⃣ Instalar dependências do Laravel

```bash
docker-compose exec app composer install
```

### 4️⃣ Configurar variáveis de ambiente

O arquivo `.env` já está configurado para uso com Docker.
Verifique as configurações:

```bash
docker-compose exec app cat .env
```

### 5️⃣ Executar as migrations

```bash
docker-compose exec app php artisan migrate
```

### 6️⃣ Popular o banco com dados de teste

```bash
docker-compose exec app php artisan db:seed
```

### 7️⃣ Verificar se a aplicação está rodando

```bash
curl http://localhost:8000/api/contratos
```

---

## 🗄 Estrutura do Banco de Dados

O projeto utiliza as seguintes tabelas:

| Tabela                | Descrição                         |
| --------------------- | --------------------------------- |
| `tb_banco`            | Cadastro de bancos                |
| `tb_convenio`         | Cadastro de convênios             |
| `tb_convenio_servico` | Serviços vinculados aos convênios |
| `tb_contrato`         | Contratos cadastrados             |

---

## 🔗 Endpoints Principais

### 1️⃣ Listar Contratos

**GET** `/api/contratos`

**Exemplo de Retorno:**

```json
{
  "success": true,
  "data": [
    {
      "nome_banco": "Banco do Brasil",
      "verba": 500000.00,
      "codigo_contrato": 1,
      "data_inclusao": "2024-01-15",
      "valor": 15000.00,
      "prazo": 24
    }
  ]
}
```

---

### 2️⃣ Agrupamento por Banco e Verba (Eloquent)

**GET** `/api/contratos/agrupamento`

```json
{
  "success": true,
  "data": [
    {
      "nome_banco": "Banco do Brasil",
      "verba": "500000.00",
      "data_inclusao_mais_antiga": "2024-01-15",
      "data_inclusao_mais_nova": "2024-02-10",
      "soma_valor_contratos": "85000.00"
    }
  ]
}
```

---

### 3️⃣ Agrupamento por Banco e Verba (SQL Bruto)

**GET** `/api/contratos/agrupamento-sql`

```json
{
  "success": true,
  "sql": "CONSULTA SQL...",
  "data": [
    {
      "nome_banco": "Banco do Brasil",
      "verba": "500000.00",
      "data_inclusao_mais_antiga": "2024-01-15",
      "data_inclusao_mais_nova": "2024-02-10",
      "soma_valor_contratos": "85000.00"
    }
  ]
}
```

---

## 🛠️ Comandos Úteis

| Ação                           | Comando                                                    |
| ------------------------------ | ---------------------------------------------------------- |
| Parar containers               | `docker-compose down`                                      |
| Reiniciar containers           | `docker-compose restart`                                   |
| Logs da aplicação              | `docker-compose logs app`                                  |
| Logs do PostgreSQL             | `docker-compose logs postgres`                             |
| Acessar container da aplicação | `docker-compose exec app bash`                             |
| Recriar banco e popular        | `docker-compose exec app php artisan migrate:fresh --seed` |
| Executar testes                | `docker-compose exec app php artisan test`                 |

---

## 🧪 Dados de Teste Gerados

Ao executar o seeder (`db:seed`), são criados automaticamente:

* **7 bancos**
* **10 convênios**
* **20 serviços**
* **20 contratos**

Com variação de **datas, valores e prazos**.

---

## 🧰 Solução de Problemas (Troubleshooting)

### 🔑 Permissões

```bash
docker-compose exec app chmod -R 775 storage
```

### 🧩 Erro de conexão com banco

```bash
docker-compose ps
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

### ⚙️ Porta já em uso

Edite o `docker-compose.yml` e altere a porta mapeada para `8001` (por exemplo).

---

## 📦 Estrutura do Projeto

```
contratos-api/
├── app/
│   ├── Models/              # Models Eloquent
│   ├── Http/Controllers/    # Controllers da API
│   └── Providers/           # Service Providers
├── database/
│   ├── migrations/          # Migrations do banco
│   └── seeders/             # Seeders para dados de teste
├── routes/
│   └── api.php              # Rotas da API
└── docker-compose.yml       # Configuração Docker
```

---

## 🌐 Acesso

| Serviço        | Endereço                                               |
| -------------- | ------------------------------------------------------ |
| **API**        | [http://localhost:8000/api](http://localhost:8000/api) |
| **PostgreSQL** | `localhost:5432`                                       |
| **Database**   | `contratos_db`                                         |
| **Usuário**    | `postgres`                                             |
| **Senha**      | `password`                                             |

---

## 📜 Licença

Este projeto é destinado a **fins de teste técnico**.
Distribuído sob a licença **MIT**.

---

<p align="center">
Desenvolvido com ❤️ utilizando <a href="https://laravel.com" target="_blank">Laravel</a>
</p>
```

---

Deseja que eu adicione também **badges personalizadas** (ex: Docker, PHP, PostgreSQL, Laravel) e um **sumário (Table of Contents)** clicável no topo (para navegação no GitHub)?
Posso gerar essa versão aprimorada do README também.
