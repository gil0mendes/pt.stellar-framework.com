---
title: Visão Geral
type: guide
order: 1
---

## O que é?

O Stellar é uma _Framework web_ baseada em ações focada apenas no desenvolvimento de APIs. Cada instância de execução do Stellar pode responder a pedidos de três protocolos diferentes em simultâneo (HTTP, WebSocket ou TCP), desta forma é possível usar a mesma API em diferentes cenários de utilização. A _Framework_ é escrita em JavaScript ES6 usando [Node.JS](https://nodejs.org/en/). O objetivo do Stellar é criar um ambiente de desenvolvimento fácil de usar, reutilizável e que seja capaz de escalar, tornando o Stellar uma excelente solução tanto para pequenos projetos, como para projetos de dimensões empresariais.

No Stellar não é usado o padrão MVC (isso não fazia sentido uma vez que o objetivo é não incluir a parte das vistas), invés disso é usado um sistema baseados em ações. Isto quer dizer que todas as funcionalidades são representadas como ações, pode ler mais sobre ações na [secção](actions.html) dedicada às mesmas.

Uma instância de execução é capaz de responder tanto a pedidos dos clientes (HTTP, WebSocket, TCP), como processar tarefas (operações que são executadas de forma concorrente em _background_, como por exemplo o envio de um email).

## Arquitetura

O _core_ do Stellar é composto por um _Engine_, um conjunto de _Satellites_ e por três servidores. O _Engine_ é resposavel por carregar os módulos e fornecer mecanismos que permitam aos _Satellites_ expor as suas APIs para o resto da plataforma, para que a sua lógica possa ser usada por outros componentes. Os módulos são a forma de agrupar as funcionalidades de uma determinada área a fim de serem mais facilmente portadas para outros projetos ou partilhar com a comunidade _Open Soure_.

![Arquitetura do Core](/images/core_arch.png)

## Como Contribuir

Tanto a [documentação](https://github.com/StellarFw/pt.stellar-framework.com) deste _website_ como o código do [Stellar](https://github.com/StellarFw/stellar) estão disponíveis no GitHub. Pode submeter _pull requests_ para o _branch_ `dev`, mas antes, por favor, lei-a atentamente o [guia de contribuição](https://github.com/StellarFw/stellar/blob/dev/CONTRIBUTING.md). Toda a ajuda é bem vinda! 😉

Também pode ajudar ao usar o gestor de [_issues_](https://github.com/StellarFw/stellar/issues) para reportar _bugs_, fazer sugestões ou pedidos de funcionalidades.

## Estrutura de uma Aplicação

A baixo está representada a estrutura de pastas típica de um projeto em Stellar, neste exemplo é uma API simples que implementa as funcionalidades de um blog.

```
.
├── config
│   └── (configurações a nível do projeto)
├── manifest.json
├── modules
│   ├── private
│   └── blog
│       ├── actions
│       │   ├── posts.js
│       │   └── comments.js
│       ├── config
│       │   └── comments.js
│       ├── listeners
│       │   ├── del_post.js
│       │   └── new_post.js
│       ├── manifest.json
│       ├── middleware
│       │   └── edit_permission.js
│       ├── models
│       │   ├── post.js
│       │   └── comment.js
│       ├── satellites
│       │   └── something.js
│       └── tasks
│           └── rss.js
└── temp
    └── (ficheiros temporários)
```

- **`config`**: Esta pasta contem configurações a nível do projeto. Estas configurações sobrepõem-se não só as configurações de sistema, mas também as dos módulos, assim sendo, mostra-se uma funcionalidade muito útil para configurar as aplicações aos requisitos do seu cenário de utilização, sem que seja necessário alterar a lógica dos componentes já desenvolvidos, tornando-os assim reutilizáveis para outras projetos.

- **`manifest.json`**: Este ficheiro contem a descrição do projeto, este é composto por três propriedades: nome do projeto, a sua versão e módulos ativos.

- **`modules`**: Esta pasta contem todos os módulos que compõem a aplicação, estes módulos podem estar ou não a ser usados, conforma a propriedade `modules` do ficheiro `manifest.json`.

  - **`actions`**: Pasta que contem os ficheiros com a implementação das ações do módulos, estes ficheiros podem ser uma ação única ou então uma coleção de ações.

  - **`config`**: Esta pasta contém as configurações do módulo. Estas configurações podem ser nova, devido as novas funcionalidades adicionadas pelo módulos, ou então subscrevem configurações do Stellar.

  - **`listeners`**: Esta pasta contém os _listeners_ para os eventos que podem ocorrer ao longo do tempo de execução.

  - **`manifest.json`**: Este ficheiro contem a descrição do módulo. Este contem campos como: `id`, `name`, `version`, `description`, `npmDependencies`.

  - **`middleware`**: A pasta contem a declaração de `middleware`, estes poderão ser utilizados em outros módulos.

  - **`models`**: Esta pasta contem a declaração dos modelos de dados, estes modelos correspondem à _syntax_ do [Mongoose](http://mongoosejs.com).

  - **`satellites`**: Esta pasta contem Satellites.

  - **`tasks`**: Esta pasta contem a declaração das tarefas, são trabalhos a ser executados em _background_ de forma assíncrona.

- **`temp`**: Por fim, esta pasta contem ficheiros temporários e _logs_ gerados pelo Stellar.
