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
