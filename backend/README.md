# 🛠️ Backend - Calc Confeiteira

Esta pasta contém o núcleo lógico do sistema, desenvolvido em **PHP 8** com foco em performance e segurança.

## 📂 Estrutura de Pastas
- `/api/v1/insumos`: Gerenciamento de matéria-prima.
- `/config.php`: Configurações globais e conexão PDO.

## 🚀 Endpoints Principais
### Insumos
- `GET /estoque_baixo.php?limite=X`: Retorna itens com estoque crítico.

## ⚙️ Configuração
Para rodar este backend localmente:
1. Certifique-se de ter o **PHP 8.x** instalado.
2. Configure as credenciais do banco em `config.php`.


🥧 CalcConfeitera - Backend API v1
Este repositório contém a lógica de servidor e integração com banco de dados para o sistema de gestão de confeitaria. O foco atual é o módulo de Gestão de Insumos.
🏗️ Arquitetura do Projeto
O projeto segue uma estrutura de pastas organizada por versões e módulos, facilitando a escalabilidade:
/backend-confeitaria: Motores em PHP e configurações de servidor.
/api/v1/insumos: Endpoints específicos para manipulação de ingredientes e materiais.
🚀 Funcionalidades Implementadas hoje
API de Busca de Insumos: Sistema de filtragem dinâmica via URL.
Integração com Banco de Dados: Conexão robusta via mysqli.
Output em JSON: Respostas estruturadas para total compatibilidade com o Frontend (React).
🛡️ Segurança (Ponto Principal)
Foi implementado o uso de Prepared Statements (Consultas Preparadas) no motor de busca para prevenir ataques de SQL Injection.
Utilização de bind_param para tratar entradas do usuário.
Uso de operadores LIKE com caracteres curinga (%) para buscas flexíveis e seguras.
📡 Endpoints Disponíveis
GET /buscar_insumos.php?q={termo}
Retorna uma lista de insumos baseada no termo de busca fornecido.
Exemplo: .../buscar_insumos.php?q=chocolate
Resposta: Array de objetos JSON contendo id, nome, preco e unidade.
🔧 Requisitos de Ambiente
Servidor: Apache (via XAMPP).
Linguagem: PHP 8.x.
Banco de Dados: MySQL (Schema: calcconfeitera).
CORS: Configurado para aceitar requisições de origens distintas (necessário para integração com React/Vite).

26/03 # 🍰 Precifica Confeitaria - Gestão Inteligente de Insumos

**Status do Projeto:** 🚀 Em Desenvolvimento Ativo (Sprint 2)
**Responsável Técnica:** ShirleisDev

## 🎯 Sobre o Projeto
Sistema desenvolvido para automação de precificação e gestão de estoque para confeitarias. O foco é o cálculo preciso do **Custo por Grama**, permitindo que o confeiteiro saiba exatamente o lucro de cada receita.

## 🛠️ Tecnologias Utilizadas
- **Backend:** PHP 8.0+ (Arquitetura orientada a objetos com PDO para segurança contra SQL Injection)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3 e JavaScript Moderno (Fetch API para buscas assíncronas)
- **Ferramentas:** XAMPP, Git/GitHub (Fluxo de Code Review via Pull Requests)

## ✨ Funcionalidades Atuais
- [x] Conexão segura com Banco de Dados.
- [x] Busca dinâmica de insumos (Live Search).
- [x] Cálculo automático de custo unitário.
- [x] Proteção de Branch e Governança de Código.

## 🛡️ Governança de Código
Para garantir a integridade do sistema registrado no MEC, este repositório utiliza regras de **Branch Protection**. Contribuições externas devem ser enviadas via Pull Request para revisão e validação técnica antes do merge na branch principal.


24/04
🚀 Radar de Preços Galáctico - Backend
Este projeto é uma API robusta desenvolvida em PHP para gerenciar insumos e preços de uma loja de confeitaria, utilizando técnicas de web scraping para monitoramento de mercados em tempo real.
🛠️ Tecnologias e Arquitetura
O projeto foi reformulado para seguir padrões modernos de desenvolvimento:
PHP 8.0+ (Backend Core).
MySQL (Persistência de dados).
Composer (Gerenciador de dependências).
PHP IDS / Segurança: Implementação de variáveis de ambiente com vlucas/phpdotenv.
Arquitetura: Separação de responsabilidades (Configuração, API e Lógica de Negócio).
CI/CD Ready: Estrutura preparada para testes automatizados com phpunit.
🛡️ Segurança (Implementada)
O sistema utiliza um arquivo .env para proteger credenciais sensíveis (Banco de Dados).

25/04
# 🎂 CalcConfeiteira - Backend API

Sistema de gestão de insumos e cálculo de custos para confeitaria, desenvolvido com foco em segurança, escalabilidade e boas práticas de programação PHP.

## 🚀 Tecnologias Utilizadas

- **PHP 8.x** (Programação Orientada a Objetos)
- **Composer** (Gerenciador de dependências)
- **MySQL** (Banco de dados)
- **vlucas/phpdotenv** (Proteção de credenciais)
- **PDO** (Interação segura com o banco de dados)

## 🛡️ Camadas de Segurança (Blindagem)

O projeto foi construído com foco em segurança nível profissional:
1. **Proteção contra SQL Injection**: Uso exclusivo de *Prepared Statements* com PDO.
2. **Cofre de Credenciais**: Senhas e usuários do banco ocultos em arquivo `.env`.
3. **Prevenção de XSS**: Sanitização de entradas com `htmlspecialchars` e `strip_tags`.
4. **Validação de Dados**: Tipagem rigorosa para preços e pesos, evitando transbordo de dados ou valores inválidos.
5. **Arquitetura Limpa**: Separação de responsabilidades entre conexão (`Database.php`) e lógica de negócio (`Insumo.php`).

## 📋 Pré-requisitos

1. Ter o **Composer** instalado.
2. Servidor local (XAMPP, WAMP ou Laragon).
3. Criar o banco de dados com a tabela `insumos`:
   ```sql
   CREATE TABLE insumos (
       id INT AUTO_INCREMENT PRIMARY KEY,
       nome_item VARCHAR(100) NOT NULL,
       preco_total DECIMAL(10,2) NOT NULL,
       peso_total DECIMAL(10,2) NOT NULL,
       custo_por_grama DECIMAL(10,4) NOT NULL
   );
   ```

## 🛠️ Instalação

1. Clone o repositório ou copie os arquivos.
2. Na pasta raiz, instale as dependências:
   ```bash
   composer install
   ```
3. Crie um arquivo `.env` na raiz e configure seu banco:
   ```env
   DB_HOST=localhost
   DB_NAME=calcconfeiteira
   DB_USER=root
   DB_PASS=sua_senha
   ```

## 📂 Estrutura de Arquivos

- `Database.php`: Classe singleton para conexão segura com o banco.
- `Insumo.php`: Classe gerente que contém os métodos `buscar()` e `salvar()`.
- `.env`: Arquivo de configuração (não deve ser enviado ao servidor).
- `vendor/`: Dependências instaladas pelo Composer.

---
Desenvolvido por **Costa** com auxílio de IA - 2024
