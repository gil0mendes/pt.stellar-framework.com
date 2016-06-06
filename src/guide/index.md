---
title: Visão Geral
type: guid
order: 1
---

## O que é?

O Stellar é uma _Framework web_ baseada em ações focada apenas no desenvolvimento de APIs. Estas APIs podem recorrer a três protocolos em simultâneo HTTP, WebSocket ou TCP, desta forma é possível usar a mesma API para responder a diferentes cenários de utilização. A _Framework_ é escrita em JavaScript ES6 usando Node.JS. O objetivo do Stellar é criar um ambiente de desenvolvimento fácil de usar, reutilizável e que seja capaz de escalar, tornando o Stellar uma excelente solução tanto para pequenos projetos, como para projetos de dimensões empresariais.

No Stellar não é usado o padrão MVC (este não faz sentido uma vez que o objetivo é não incluir a _view_), invés disso é usado um sistema baseados em ações. Isto quer dizer que todas as funcionalidades são representadas como ações, pode ler mais sobre ações na secção dedicada às mesmas.

Uma instância de execução é capaz de responder tanto a pedidos dos clientes (HTTP, WebSocket, TCP), como processar tarefas (ações que são executadas de forma concorrente em _background_, como por exemplo o envio de um e-mail)

## Arquitetura

O Stellar é composto por um Engine e por uma série de Satellites. Por sua vez o Engine carrega os módulos, os módulos são a forma de agrupar funcionalidades de uma determinada área a fim de serem mais facilmente portadas para outros projetos ou partilhas na comunidade _Open Source_.

> TODO: Colocar uma imagem da arquitetura

## Como Contribuir

Tanto a [documentação](https://github.com/gil0mendes/pt.stellar-framework.com) como o código do [Stellar](https://github.com/gil0mendes/stellar) estão disponíveis no GitHub. Pode submeter pull requests para o branch `dev`, mas antes, por favor, lei-a atentamente o [guia de contribuição](https://github.com/gil0mendes/stellar/blob/dev/CONTRIBUTING.md). Toda a ajuda é bem vinda! 😉

Também pode ajudar ao usar o gestor de _issues_ para reportar _bugs_, fazer sugestões ou pedidos de funcionalidades.

## Estrutura da Aplicação

A baixo está representada a estrutura de pastas típica de um projeto em Stellar, neste exemplo é uma API simples que implementa as funcionalidades de um blog.


```
.
├── config
│   └── (configurações a nivel do projeto)
├── manifest.json
├── modules
│   └── blog
│       ├── actions
│       │   ├── posts.js
│       │   └── comments.js
│       ├── config
│       │   └── comments.js
│       ├── manifest.json
│       ├── models
│       │   ├── post.js
│       │   └── comment.js
│       ├── satellites
│       │   └── something.js
│       └── tasks
│           └── rss.js
└── temp
    └── (ficheiros temporarios)
```

A pasta `/config` contem configurações a nível do projeto, isto que tanto as configurações de sistema, como as dos módulos, podem ser sobrescritas utilizando esta pasta. Em seguida, o ficheiro `manifest.json` contem a descrição do projeto (nome, versão, módulos ativos …). Existem também uma pasta com os módulos que compõem o projeto (que será falada mais à frente) e uma pasta `temp` que contem ficheiros temporários e logs gerados pelo Stellar.
