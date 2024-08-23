# Documentação da API de despesas

Esta API fornece uma maneira de gerenciar despesas. Ela usa a autenticação JWT para proteger as rotas.

Baixe o código-fonte em um arquivo zip, ou, se você tiver o Git instalado, use o comando git clone. Escolha a opção que melhor se adapta às suas necessidades - **HTTPS, SSH, GitHub CLI**. Abaixo estão as configurações para o ambiente de desenvolvimento.

## Tecnologias usadas
* PHP
* Hyperf
* MySQL
* Composer
* GIT
* Docker
* JWT
* PHP Mailer

## Principais Arquivos
* AdminMiddleware.php
* AuthMiddleware.php
* AbstractController.php
* AuthController.php
* CardController.php
* ExpensesController.php
* UsersController.php
* LoginRepositoryInterface.php
* Card.php
* Expense.php
* User.php
* ExpenseCreated.php
* ExpenseCreatedListener.php
* LoginRepository.php
* EmailService.php
* CardRequest.php
* ExpenseRequest.php
* LoginRequest.php
* UserRegisterRequest.php
* UserRequest.php
* routes.php
* migrations

## Começando

Para começar a utilizar a Api de despesas, você precisa ter o seguinte configurado:

- Docker: Certifique-se de que o Docker está instalado e disponível no seu sistema. Você pode verificar se o PHP está instalado executando o seguinte comando:
``` bash
$  docker --version
```

- PHP: Certifique-se de que o PHP está instalado e disponível no seu sistema. Você pode verificar se o PHP está instalado executando o seguinte comando:
``` bash
$  php --version
```

- Composer: Certifique-se de que o Composer está instalado e disponível no seu sistema. Você pode verificar se o Composer está instalado executando o seguinte comando:
``` bash
$  composer --version
```

## Clonando o projeto

Escolha uma das opções abaixo ou baixe o projeto em formato zip:

``` bash
$    HTTPS - git clone https://github.com/hugojose39/api-expenses-hyperf.git
$    SSH - git clone git@github.com:hugojose39/api-expenses-hyperf.git
$    GitHub CLI - gh repo clone hugojose39/api-expenses-hyperf.git
```

Quando o projeto estiver em seu computador, acesse sua pasta e execute os comandos no seu terminal:

1. Crie o arquivo .env de acordo com o .env.example, nele estará a configuração para envio de emails e banco de dados.

2. Construa a imagem do Docker com o comando abaixo:
    ```bash
    docker compose build --force-rm
    ```

3. Para acessar a linha de comando da aplicação, execute o comando abaixo:

   **O comando docker-compose run --user=root app bash permite acessar o contêiner do aplicativo Docker como usuário root.**
    ```bash
    docker compose run --user=root app bash
    ```

4. Na linha de comando da aplicação, instale as dependências da aplicação com o comando abaixo:

   - Nesta parte será necessário que você dê permissões de gravação temporária, basta selecionar a opção **Y**.

    ```bash
    composer install
    ```

5. Ainda linha de comando da aplicação, execute as migrations da aplicação com o comando abaixo:

    ```bash
    bin/cake migrations migrate
    ```

6. Para sair da linha de comando da aplicação, execute o comando abaixo:
    ```bash
    exit
    ```

7. Execute o comando abaixo para iniciar a aplicação:
    ```bash
    docker compose up -d
    ```

8. Execute o comando abaixo para parar a aplicação:
    ```bash
    docker compose down
    ```

## Autenticação

Essa Api faz o uso da autenticação via JWT token e possui rotas que verificam o scope do token.

Exemplo: A rota indexAll destinada a listagem completa dos dados do usuário, do cartão e das despesas so pode ser acessada por usuário que possuem o scope = admin, que está ligado ao type do usuário.

- Para obter um token de acesso, será preciso registrar um usuário para isso envie uma solicitação POST para rota /api/register. A solicitação deve conter os seguintes campos:
   - *email*: O endereço de e-mail do usuário.
   - *name*: Nome do usuário.
   - *password*: A senha do usuário.
   - *type*: Tipo do usuário se ele é admin ou um usuário comum.

- Após o registro envie uma solicitação POST para a rota /api/token, para obter o token de forma efetiva:
   - *email*: O endereço de e-mail do usuário.
   - *password*: Nome do token. A senha do usuário.

## Uso

A aplicação deve ser acessível em `http://localhost:9501`.

## Endpoints

### A API fornece os seguintes endpoints:

POST /api/register: Registra um usuário.

POST /api/login: Cria a token de autenticação (Guarde ela com atenção pois será utilizada nas demais requisições).

POST /api/cards: Cria um novo cartão.

GET /api/cards/indexAll: Lista todos os cartões, apenas usuários do tipo admin possuem acesso.

GET /api/cards: Lista todos as cartões do usuário.

GET /api/cards/{id}: Obtém um cartão específico.

PUT /api/cards/{id}: Atualiza um cartão especifico.

DELETE /api/cards/{id}: Exclui um cartão específico.

POST /api/expenses: Cria uma nova despesa.

GET /api/expenses/indexAll: Lista todas as despesas, apenas usuários do tipo admin possuem acesso.

GET /api/expenses: Lista todas as despesas do usuário.

GET /api/expenses/{id}: Obtém uma despesa específica.

PUT /api/expenses/{id}: Atualiza uma despesa específica.

DELETE /api/expenses/{id}: Exclui uma despesa específica.

GET /api/users/indexAll: Lista todos os usuários, apenas usuários do tipo admin possuem acesso.

GET /api/users/{id}: Obtém um usuário específico.

PUT /api/users/{id}: Atualiza um usuário específico.

DELETE /api/users/{id}: Exclui um usuário específico.

## Exemplos:

### Registra um novo usuário

curl -X POST

- H "Content-Type: application/json"
- d `{ "email": "johndoe@example.com", "name": "John Doe", "password": "Password1234", "type": "admin" }`

http://localhost:9501/api/register

### Obter um token de acesso

curl -X POST

- H "Content-Type: application/json"
- d `{ "email": "johndoe@example.com", "password": "Password1234" }`

http://localhost:9501/api/login

### Listar todos os cartões
Middleware: AdminMiddleware

curl -X GET

- H "Authorization: {token}"

http://localhost:9501/api/cards/indexAll

### Listar todos os cartões do usuário
Middleware: AuthMiddleware

curl -X GET

- H "Authorization: {token}"

http://localhost:9501/api/cards

### Criar um novo cartão
Middleware: AuthMiddleware

curl -X POST

- H "Authorization: {token}"
- H "Content-Type: application/json"
- d `{ "number": 1234567489, "user_id": 1, "balance": 100 }`

http://localhost:9501/api/cards

### Listar um cartão específico
Middleware: AuthMiddleware

curl -X GET

- H "Authorization: {token}"

http://localhost:9501/api/cards/1

### Atualizar um cartão
Middleware: AuthMiddleware

curl -X PUT

- H "Authorization: {token}"
- H "Content-Type: application/json"
- d `{ "number": 1234567489, "user_id": 1, "balance": 100 }`

http://localhost:9501/api/cards/1

### Excluir um cartão
Middleware: AuthMiddleware

curl -X DELETE

- H "Authorization: {token}"

http://localhost:9501/api/cards/1

### Listar todas as despesas
Middleware: AdminMiddleware

curl -X GET

- H "Authorization: {token}"

http://localhost:9501/api/expenses/indexAll

### Listar todas as despesas do usuário
Middleware: AuthMiddleware

curl -X GET

- H "Authorization: {token}"

http://localhost:9501/api/expenses

### Criar uma nova despesa
Middleware: AuthMiddleware

curl -X POST

- H "Authorization: {token}"
- H "Content-Type: application/json"
- d `{ "description": "Despesa de teste", "card_id": 1, "amount": 100 }`

http://localhost:9501/api/expenses

### Listar uma despesa específica
Middleware: AuthMiddleware

curl -X GET

- H "Authorization: {token}"

http://localhost:9501/api/expenses/1

### Atualizar uma despesa
Middleware: AuthMiddleware

curl -X PUT

- H "Authorization: {token}"
- H "Content-Type: application/json"
- d `{ "description": "Despesa de teste", "card_id": 1, "amount": 100 }`

http://localhost:9501/api/expenses/1

### Excluir uma despesa
Middleware: AuthMiddleware

curl -X DELETE

- H "Authorization: {token}"

http://localhost:9501/api/expenses/1

### Listar todos as usuários
Middleware: AdminMiddleware

curl -X GET

- H "Authorization: {token}"

http://localhost:9501/api/users/indexAll

### Listar um usuário específico
Middleware: AuthMiddleware

curl -X GET

- H "Authorization: {token}"

http://localhost:9501/api/users/1

### Atualizar um usuário
Middleware: AuthMiddleware

curl -X PUT

- H "Authorization: {token}"
- H "Content-Type: application/json"
- d `{ "name": John Doe, "email": "johndoe@example.com", "password": "Password1234" }`

http://localhost:9501/api/users/1

### Excluir um usuário
Middleware: AuthMiddleware

curl -X DELETE

- H "Authorization: {token}"

http://localhost:9501/api/users/1
