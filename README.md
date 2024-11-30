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
* PHP Unit

## Principais Arquivos
* AdminMiddleware.php
* AuthMiddleware.php
* AbstractController.php
* AuthController.php
* CardController.php
* ExpensesController.php
* UsersController.php
* CardRepositoryInterface.php
* EmailServiceInterface.php
* ExpenseRepositoryInterface.php
* LoginRepositoryInterface.php
* UserRepositoryInterface.php
* Card.php
* Expense.php
* User.php
* ExpenseCreated.php
* ExpenseCreatedListener.php
* LoginRepository.php
* CardRepository.php
* ExpenseRepository.php
* UserRepository.php
* EmailService.php
* JWTService.php
* CardRequest.php
* ExpenseRequest.php
* LoginRequest.php
* UserRegisterRequest.php
* UserRequest.php
* routes.php
* migrations
* test

## Design Patterns usados.

* **Repository** - Gerenciamento de Entidades, para centralizar o acesso ao banco.
* **Observer** - Envio de E-mail após criar a despesa, para notificar o usuário quando uma despesa é criada.
* **Singleton** - Gerenciamento de JWT, para garantir que apenas uma instância do gerenciador de JWT seja usada.
* **Strategy** - Gerenciamento do fluxo de envio de emails.

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

1. Crie o arquivo .env de acordo com o .env.example, nele estará a configuração para envio de emails, banco de dados e autenticação.

2. Execute o comando abaixo para iniciar a aplicação:
    ```bash
    docker compose up -d
    ```

3. Para acessar o container do banco de dados da aplicação, execute o comando abaixo:

    ```bash
    docker exec -it expenses-mysql-1 mysql -uroot -ppassword
    ```

4. Para criar o banco de dados da aplicação, execute o comando abaixo:

    ```bash
    CREATE DATABASE `expenses-mysql`;
    ```

5. Para sair do container do banco de dados da aplicação, execute o comando abaixo:
    ```bash
    exit
    ```

6. Após sair do container do banco de dados da aplicação, instale as dependências da aplicação com o comando abaixo:

    ```bash
    composer install
    ```

7. Depois de instalar as dependências da aplicação, derrube o container e suba de novo com o comando abaixo:

    ```bash
    docker compose down
    ```
   
    ```bash
    docker compose up -d
    ```

8. Para acessar o container da aplicação, execute o comando abaixo:

    ```bash
    docker exec -it hyperf /bin/sh
    ```

9. Para sair do container da aplicação, execute o comando abaixo:
    ```bash
    exit
    ```

10. Para executar a migrations do banco de dados, execute o comando abaixo:
    ```bash
    docker exec -it hyperf php bin/hyperf.php migrate
    ```

11. Para voltar as migrations do banco de dados, execute o comando abaixo:
    ```bash
    docker exec -it hyperf php bin/hyperf.php migrate:rollback
    ```

12. Para monitoramento dos logs do container da aplicação, execute o comando abaixo:
    ```bash
    docker logs -f hyperf
    ```

13. Execute o comando abaixo para parar a aplicação:
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

## Testes:

Para executar todos os testes de uma única vez execute o seguinte comando, dentro do container:

```bash
docker exec -it hyperf /bin/sh
```
```bash
composer test
```
