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
