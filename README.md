# Mara Vicentina Pinto RA:1012023100321

# 🐾 Animal World

**Animal World** é uma aplicação web desenvolvida com Laravel e Vue.js, projetada para gerenciar informações sobre animais de forma eficiente e intuitiva.

## 🚀 Tecnologias Utilizadas

* [Laravel](https://laravel.com/) – Framework PHP para o backend
* [Vue.js](https://vuejs.org/) – Framework JavaScript para o frontend
* [Vite](https://vitejs.dev/) – Ferramenta de build rápida para projetos modernos
* [Bootstrap](https://getbootstrap.com/) – Framework CSS para estilização
* [MySQL](https://www.mysql.com/) – Sistema de gerenciamento de banco de dados relacional

## 📁 Estrutura do Projeto

O projeto está organizado da seguinte forma:

* `app/` – Contém os modelos, controladores e lógica de negócios
* `resources/` – Arquivos de views e componentes Vue.js
* `routes/` – Definições de rotas da aplicação
* `database/` – Migrações e seeders do banco de dados
* `public/` – Arquivos públicos acessíveis via web
* `tests/` – Testes automatizados
* `.env.example` – Arquivo de exemplo para configuração de variáveis de ambiente

## ⚙️ Instalação

Para configurar o projeto localmente, siga os passos abaixo:

1. Clone o repositório:

   ```bash
   git clone https://github.com/mara-vicentina/animal_world.git
   cd animal_world
   ```

2. Instale as dependências do PHP e do Node.js:

   ```bash
   composer install
   npm install
   ```

3. Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente conforme necessário:

   ```bash
   cp .env.example .env
   ```

4. Gere a chave da aplicação:

   ```bash
   php artisan key:generate
   ```

5. Configure o banco de dados no arquivo `.env` e execute as migrações:

   ```bash
   php artisan migrate
   ```

6. Compile os assets do frontend:

   ```bash
   npm run dev
   ```

7. Inicie o servidor de desenvolvimento:

   ```bash
   php artisan serve
   ```

A aplicação estará disponível em `http://localhost:8000`.

## ✅ Funcionalidades

* Cadastro, edição e exclusão de informações sobre animais
* Interface amigável e responsiva
* Integração entre backend Laravel e frontend Vue.js
* Validação de dados e mensagens de erro claras

## 📄 Licença

Este projeto está licenciado sob a [MIT License](LICENSE).
