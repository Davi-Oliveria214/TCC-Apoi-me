# Apoie-me Condomínios 🏢

> **Link do Projeto:** https://tcc-apoime.infinityfree.me/index.php

>---

## 📋 Sobre o Projeto
Apoi-me é um programa, focado na gestão e agendamento de serviços para condomínios. A plataforma permite que moradores e prestadores se conectem de forma organizada.

---

## 🚀 Tecnologias e Justificativas
* **Linguagem:** PHP. Escolhida pela facilidade de aprendizado e conexão, segurança ao banco de dados.
* **Banco de Dados:** Supabase/Postegre.
* **Interface:** HTML5, CSS3 e JavaScript.
* **Hospedagem:** O site foi hospedado no infinityfree.

---

## 📦 Bibliotecas e Gerenciamento de Dependências
Para este projeto, utilizamos o **Composer** como gerenciador de dependências, garantindo que todas as bibliotecas externas sejam mantidas de forma organizada e segura.

* **PHPMailer:** Utilizado para o sistema de notificações e recuperação de senha.
* **vlucas/phpdotenv:** Essencial para a segurança do projeto. Esta biblioteca permite que informações sensíveis (como links e senhas do banco de dados no Supabase) fiquem em um arquivo `.env` protegido, não ficando expostas diretamente no código-fonte.
* **Symfony Polyfills (Ctype/Mbstring):** Utilizado para garantir a compatibilidade do sistema com diferentes versões do servidor, assegurando que funções modernas de tratamento de texto funcionem perfeitamente no ambiente da InfinityFree.
* **GrahamCampbell/Result-Type:** Usada para um tratamento de erros mais elegante e funcional dentro do código, aumentando a estabilidade das funções de registro.

---

## 🏛️ Organização do Projeto (Arquitetura)

Atualmente, utilizamos no projeto uma abordagem de **Desenvolvimento Estruturado**, priorizando a agilidade na entrega das funcionalidades principais e a estabilidade da integração entre o site e o banco de dados **Supabase**.

A estrutura de diretórios foi organizada para separar as responsabilidades da seguinte forma:

* **Raiz (Root):** Contém as páginas principais de acesso do usuário (ex: `index.php`, `login.php`, `cadastro.php`) e os arquivos de configuração do ambiente como `.env` e os arquivos do Composer.
* **`/controls`:** Pasta destinada aos scripts de processamento de dados e regras de negócio intermediárias.
* **`/util`:** Concentra funções utilitárias e ferramentas auxiliares que são reaproveitadas em diversos pontos do sistema.
* **`/includes`:** Armazena fragmentos de código repetitivos (como o menu de navegação e rodapé) para facilitar a manutenção via `include/require`.
* **`/vendor`:** Diretório gerenciado pelo **Composer**, contendo as dependências externas do projeto (como PHPMailer e Dotenv).
* **`/js`, `/css`, `/img`, `/icon`:** Pastas dedicadas aos ativos de frontend, garantindo a organização de estilos, scripts de interface e recursos visuais.

---

## ⚙️ Principais Funcionalidades
* **Verificação de E-mail:** Fluxo de segurança via PHPMailer para validar novos registros.
* **Marketplace de Serviços:** Interface para visualização de prestadores disponíveis no condomínio.
<<<<<<< HEAD
* **Sistema de Agendamento:** Lógica para reserva de horários e gestão de serviços solicitados.
=======
* **Sistema de Agendamento:** Lógica para reserva de horários e gestão de serviços solicitados.
>>>>>>> tcc-host
