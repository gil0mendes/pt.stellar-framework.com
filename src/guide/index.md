---
title: Visão Geral
type: guide
order: 1
---

## O que é?

O Stellar é uma _Framework web_ baseada em ações e focada no desenvolvimento de APIs. Cada instância de execução do Stellar pode responder a pedidos de múltiplos protocolos em simultâneo, sendo desta forma possível usar a mesma API em diferentes cenários de utilização. A _Framework_ é escrita em JavaScript ES6 usando [Node.JS](https://nodejs.org/en/). O objetivo do Stellar é criar um ambiente de desenvolvimento de usabilidade fácil, reutilizável e escalavél, tornando o Stellar uma excelente solução tanto para pequenos como para projetos de dimensões empresariais.

É usado um sistema baseados em ações. Isto quer dizer que todas as funcionalidades são representadas como ações, pode ler mais sobre ações na [secção](actions.html) dedicada às mesmas.

Uma instância de execução é capaz de responder tanto a pedidos dos clientes, como processar tarefas - operações que são executadas de forma concorrente em _background_. Ex: envio de um email.

## Protocolos Suportados
* HTTP
* WebSocket
* TCP

## Arquitetura

O _core_ do Stellar é composto por um _Engine_, um conjunto de _Satellites_ e por três servidores. O _Engine_ é resposavel por carregar os módulos e fornecer mecanismos que permitam aos _Satellites_ expor as suas APIs para o resto da plataforma, isto, para que a sua lógica possa ser usada por outros componentes. Os módulos são a forma de agrupar as funcionalidades de uma determinada área a fim de serem mais facilmente portadas para outros projetos ou para partilhar com a comunidade _Open Soure_.

![Arquitetura do Core](/images/core_arch.png)

## Como Contribuir

Tanto a [documentação](https://github.com/StellarFw/pt.stellar-framework.com) deste _website_ como o código do [Stellar](https://github.com/StellarFw/stellar) estão disponíveis no GitHub. Pode submeter _pull requests_ para o _branch_ `dev`, mas antes, por favor, lei-a atentamente o [guia de contribuição](https://github.com/StellarFw/stellar/blob/dev/CONTRIBUTING.md). Toda a ajuda é bem-vinda! 😉

Também pode ajudar ao usar o gestor de [_issues_](https://github.com/StellarFw/stellar/issues) para reportar _bugs_, fazer sugestões ou mesmo pedidos de funcionalidades.

## Estrutura de uma Aplicação

Abaixo está representada a estrutura de pastas típica de um projeto Stellar. Neste exemplo é uma API simples que implementa as funcionalidades de um blog.

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


- **`config`**: Contêm configurações a nível do projeto. Estas configurações sobrepõem-se não só às configurações de sistema, mas também às dos módulos. Assim, mostra-se uma funcionalidade muito útil para configurar as aplicações aos requisitos do seu cenário de utilização sem que seja necessário alterar a lógica dos componentes já desenvolvidos, tornando-os assim reutilizáveis para outros projetos.

- **`manifest.json`**: Este ficheiro contêm a descrição do projeto, composto por três propriedades: nome, versão e módulos ativos.

- **`modules`**: Contêm todos os módulos que compõem a aplicação, que podem ser ou não usados, conforme a propriedade `modules` do ficheiro `manifest.json`.

  - **`actions`**: Contêm os ficheiros com a implementação das ações dos módulos. Estes ficheiros podem ser uma ação única ou então uma coleção de ações.
  
  - **`config`**: Contém as configurações do módulo. Estas configurações são carregadas de acordo com o nível de prioridade do módulo, assim sobrepõem-se às do _core_ e às configurações dos módulos de menor prioridade. Também pode conter novas configurações para controlar o comportamento das novas funcionalidades adicionadas pelo módulo.

  - **`listeners`**: Contém os _listeners_ dos eventos que podem ocorrer ao longo do tempo de execução.

  - **`manifest.json`**: Este ficheiro contêm a descrição do módulo através da descrição de: `id`, `name`, `version`, `description`, `npmDependencies`.

  - **`middleware`**: Contêm a declaração de `middleware`, que poderá ser utilizada em outros módulos.

  - **`models`**: Contêm a declaração dos modelos de dados, correspondentes à _syntax_ do [Mongoose](http://mongoosejs.com).

  - **`satellites`**: Contêm os [Satellites](satellites.html).

  - **`tasks`**: Contêm a declaração das tarefas, são trabalhos a ser executados em _background_ de forma assíncrona.

- **`temp`**: Por fim, esta pasta contem ficheiros temporários e _logs_ gerados pelo Stellar.

### manifest.json

O ficheiro **manifest.json** permite descrever o projeto através do nome, versão e módulos ativos. Abaixo encontra-se um exemplo com o formato deste ficheiro:


```json
{
  "name": "blog",
  "version": "1.0.0",
  "description": "Um sistema simples de blog com suporte a autenticação",
  "modules": [
    "authentication"
  ]
}
```
