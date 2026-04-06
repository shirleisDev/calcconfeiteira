# 🍰 Master Bakery Framework - Controle de Custos

Sistema **Full Stack** para gestão de custos de confeitaria, focado em precisão de insumos e materiais.

## 🚀 O que o projeto faz hoje?

### 💻 Versão Web (PHP & MySQL)
- **Cálculo de Precisão:** Lógica de "Regra de Três" para converter preço de atacado em custo por grama.
- **Persistência SQL:** Salva automaticamente ingredientes e materiais no banco de dados.
- **Relatório Dinâmico:** Soma automática de custos para controle de lucro.

### 📱 Versão Mobile (React Native)
- **Espinha Dorsal:** Mesma lógica de cálculo (o famoso custo de **0,63**) rodando no celular via Expo.
- **Análise de Insumos:** Exibe o custo por grama (4 casas decimais) para evitar desperdício.

## 🛠️ Tecnologias de Backend & Frontend
- **Linguagens:** PHP 8, JavaScript (ES6+).
- **Mobile:** React Native com Expo Router.
- **Banco de Dados:** MySQL (MariaDB).
- **Servidor Local:** XAMPP (Apache).

## 📈 Evolução de Aprendizado (Foco Backend)
- **Integração:** Conexão entre App Mobile e Banco de Dados via API PHP.
- **Tratamento de Dados:** Manipulação de Arrays e conversão de unidades (kg para gramas).
- **Versionamento:** Controle de projeto Web e Mobile em um único repositório Git.

---
 Evolução do Projeto - Hoje
Organizei a casa! Saí de scripts soltos para uma **Arquitetura de API Profissional**.

**O que mudou:**
- 📂 **Estrutura de Pastas:** Criei o padrão `/api/v1/insumos/` para melhor organização.
- 📡 **Listagem Dinâmica:** Criei o `lista.php`, que busca os ingredientes direto do MySQL.
- 🔐 **Segurança:** Reforcei o uso de PDO para proteger os dados da confeitaria.
- 🛠️ **Backend Robusto:** Agora o sistema está pronto para conversar com o App (React Native).


06/04/

# 👩‍🍳 BakeSpy Radar de Insumos

O **BakeSpy** é um robô de busca (Web Scraper) desenvolvido em PHP para ajudar confeiteiras a monitorar preços de ingredientes em tempo real em diversos mercados simultaneamente.

## 🚀 Funcionalidades
- **Busca Multimercado:** Consulta várias URLs ao mesmo tempo através de um Array dinâmico.
- **Detecção Inteligente:** Localiza termos de busca e tenta extrair preços (R$) próximos ao produto.
- **Interface Futurista:** Painel de controle com tema escuro e feedback visual de status da operação.

## 🛠️ Tecnologias
- **PHP 8+** (Lógica do robô e extração de dados)
- **HTML5/CSS3** (Interface responsiva e temática)
- **Stream Context** (Simulação de User-Agent para evitar bloqueios)
- **Fonte** de extração de conhecimento documentos php sit ja existes e o mais importante consulta a varias ferramentas como (IA ).
## 🎯 Objetivo do Projeto
Este projeto nasceu para resolver uma dor real: o tempo gasto comparando preços manualmente. Ele automatiza a "Operação Espião", garantindo que a produção de doces tenha sempre o melhor custo-benefício.

---
*Projeto desenvolvido com carinho para o mundo da confeitaria e como parte do aprendizado em desenvolvimento backend.*
-**foi feita modificação:** renomeação de pasta modificação de json e atualização no  atalho (Alias)xampp rodando sem erro