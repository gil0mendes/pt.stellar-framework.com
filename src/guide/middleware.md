---
title: Middleware
type: guide
order: 5
---

## O que é um middleware?

Os desenvolvedores podem criar _middlewares_ que podem ser aplicados antes e depois da execução de uma ação. Existem dois tipos de _middlewares_, os globais, que são aplicados a todas as ações e os individuais que são aplicados de forma individual a cada ação utilizando a propriedade `action.middleware`. Cada _middleware_ tem um nome e opcionalmente pode conter uma prioridade que irá definir a ordem de execução dos _middleware_.
Existem três tipos de _middlewares_, para ações, conexões e _chat_. Cada um é distinto dos outros e operam em diferentes partes do _lifecycle_ do cliente.

### Lifecycle

![Request Flow](/images/middleware_lifecycle.png)

Como se pode verificar na imagem acima, existem diferentes locais onde pode ser executado um _middleware_. A lista a seguir mostra os diferentes _middlewares_ disponíveis no Stellar:

- Quando um cliente se liga
  - _Middleware_ de conexão, `create`
- O cliente faz o pedido de uma ação
  - _Middleware_ de ação, `preProcessor`
  - _Middleware_ de ação, `postProcessor`
- Um cliente se junta a uma sala de chat
  - _Middleware_ de _chat_, `join`
- O cliente envia uma mensagem para a sala de _chat_
  - _Middleware_ de _chat_, `say`
  - _Middleware_ de _chat_, `onSayReceive`
- O cliente faz um pedido de desconexão (_quit_)
  - _Middleware_ de _chat_, `leave`
  - _Middleware_ de conexão, `destroy`

## Tipos de middlewares

### Middleware de Ação

O Stellar oferece _hooks_ para se executar código antes e depois de algumas ações, este é o local apropriado para adicionar lógica relacionada com a autenticação ou validar o estado de um determinado recurso.

### Middleware de Conexão

É possível criar _middlewares_ para reagir à criação e destruição de todas as conexões. Ao contrário dos _middleware_ de ações, estes não bloqueiam o pedido até ao fim da execução, são assíncronos.

É preciso ter em atenção que algumas conexões persistem (TCP e WebSocket) e parte delas apenas existem durante um único pedido (HTTP), mas pode-se inspecionar o valor `connection.type` de forma a saber que tipo de ligação está a ser criada ou destruída.

### Middleware de Chat

Por ultimo, existe os _middlewares_ para o _chat_. Este tipo de _middleware_ é ativado quando um cliente se junta ou sai de uma sala, ou comunica dentro de uma sala de _chat_. Existem quatro tipos de _middleware_ para cada etapa: `say`, `onSayReceive`, `join` e `leave.
