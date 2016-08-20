---
title: HTTP
type: guide
order: 16
---

## Introdução

O servidor HTTP permite executar [ações](./actions.html) e expor ficheiros sob o protocolo HTTP e HTTPS. A API pode ser acedida através de um browser, pelo comando Curl, etc. Através do endereço `<url>?action=<nome_da_ação>` ou `<url>/api/<nome_da_ação>` é onde pode aceder às ações. Por exemplo, se quiser aceder à ação 'getPosts' num servidor local que está à escuta na porta 8080, teria que fazer uma chamada para o URL `http://127.0.0.1/?action=getPosts`.

O código JSON abaixo mostra uma exemplo de uma resposta do servidor.

```json
{
  "questions": [ ],
  "serverInformation": {
    "serverName": "Stellar API",
    "apiVersion": "0.0.1",
    "requestDuration": 194,
    "currentTime": 1471702323600
  },
  "requesterInformation": {
    "id": "6f9e36f49e49dd7ac30348a0d9826e367ac747d7-cec5fb6e-2b0b-416e-8529-f897ea666d39",
    "fingerprint": "6f9e36f49e49dd7ac30348a0d9826e367ac747d7",
    "remoteIP": "127.0.0.1",
    "receivedParams": {
      "action": "getQuestions",
      "apiVersion": 1
    }
  }
}
```

## Enviar Ficheiros

Stellar também pode servir ficheiros ao cliente. O Stellar não faz fazer _cache_ dos ficheiros, a cada pedido eles são lidos do disco. A seguir encontra-se um exemplo de como servir um ficheiro ao cliente a partir de uma ação.

```javascript
// especifica o ficheiro a enviar para o cliente
action.connection.sendFile('/path/to/file.txt')

// informa que não é para fazer o render da resposta
action.toRender = false

// termina a execução da ação
next()
```

- A raiz do servidor _web_ `/` pode ser usada para servir ficheiros (`/files`) ou ações (`/api`). O seu comportamento pode ser alterado na configuração `api.config.servers.web.rootEndpointType`, por defeito serve ficheiros.

- Quando um ficheiro não é encontrado o resultado é uma página com o erro HTTP 404.

- Sempre que possível será recorrido ao pacote [mime](https://www.npmjs.com/package/mime) para adicionar uma entrada no cabeçalho da resposta com o `mime-type` do ficheiro servido.

> Nota: Na secção ["Sistema de Ficheiros"](./file_system.html) podem ser encontrado alguns _helpers_ que ajudam no envio de ficheiros.

## Rotas

Para os cliente _web_ (HTTP e HTTPS), é possível, opcionalmente, definir rotas RESTful para as ações. Se o cliente não especificar a ação via parâmetro e se o URL não se tratar de uma _named action_, o servidor irá tentar encontrar uma rota definida nos ficheiros `routes.json` que podem existir na raiz de cada módulo.

Existem três formas dos clientes aceder a ações via servidor _web_:

-  sem nenhuma rota, recorrendo a parâmetros GET: `example.com/api?action=getPosts`

- através de _basic routing_, onde o nome das ações irão responder depois do caminho `/api`, por exemplo: `example.com/api/getPosts`

- ou através de rotas definidas pelos desenvolvedores, por exemplo, é possível servir uma ação com a seguinte rota `example.com/api/posts`

Se a configuração `api.config.servers.web.rootEndpointType` tiver o valor `'file'` isso quer dizer que as rotas irão responder sobre o prefixo `/api`. Para o servidor responder à rota `example.com/posts`, o `api.config.servers.web.rootEndpointType` deve estar definido como `'api'`.

> Nota: Ao alterar a configuração de `'file'` para `'api'` as rotas em `/api` continuam a funcionar.

O JSON abaixo mostra um exemplo da declaração de rotas:

```json
{
  "all": [
    { "path": "/cache/:key/:value", "action": "setCache" }
  ],
  "get": [
    { "path": "/question",      "action": "getQuestions" },
    { "path": "/question/:id",  "action": "getQuestion" }
  ],
  "post": [
    { "path": "/question", "action": "createQuestion" }
  ],
  "put": [
    { "path": "/question/:id", "action": "editQuestion" }
  ],
  "delete": [
    { "path": "/question/:id", "action": "removeQuestion" }
  ]
}
```

### Usar Versões

Também é possível especificar a versão da ação a associar à rota, por defeito é executada a ultimação versão da ação. O exemplo abaixo mostra essa funcionalidade:

```json
{
  "all": [
    { "path": "/actionName/old", "action": "actionName", "apiVersion": 1 },
    { "path": "/actionName/new", "action": "actionName", "apiVersion": 2 }
  ]
}
```

### Desativar os Acessos em /api

Para desativas os acessos em `/api` e apenas seja possível aceder à ações através da raiz do servidor, apenas é necessário alterar o valor de `api.config.servers.web.urlPathForActions` para `null`.

> Nota: A parâmetro `api.config.servers.web.rootEndpointType` deve ser igual a `'api'`, caso contrario não será possível fazer chamadas às ações.

## Parâmetros

Os parâmetros podem ser especificados através de parâmetros GET ou por POST. A ordem de carregamento dos parâmetros é: GET -> POST (normal) -> POST (multipart). Isto quer dizer que se for feito uma chamada ao URL `example.com/?key=getValue` e no post estiver uma variável definida `key=postValue`, o `postValue` será o valor usado.

Os ficheiros para _upload_ carregados através de um formulário também irão aparecer no objeto `connection.params`, mas será uma objeto com mais informação. Isto é, se for carregado um ficheiro chamado `'image'`, o objeto `connection.params.image` irá existir com as informações: `name` (nome original do ficheiro), `path` (caminho para o ficheiro), `type` (tipo do ficheiro).

## Upload de Ficheiros

O Stellar usa a biblioteca [formidable](https://www.npmjs.com/package/formidable) para fazer o _parse_ dos parâmetros da chamada dos clientes. É possível fazer o _uplaod_ de múltiplos ficheiros que irão estar disponíveis no objeto `connection.params`, como referências para um objeto **formidable**, que contem o nome original do ficheiro, a localização onde o ficheiro foi temporariamente guardado, etc.

## Biblioteca do Cliente

Por fim, o StellarClient é uma biblioteca _client-side_, maioritariamente em WebSockets, que permite interagir de forma simples com o servidor Stellar. Antes do método `connect` ser executado ou quando a ligação através de WebSocket é quebrada, é usada uma ligação HTTP.

```javascript
'use strict'

// cria um novo cliente
let stellar = new StellarClient({ url: 'server.example.com:8080' })

// executa uma ação
stellar.action('createPost', { title: 'Example!', content: 'Some content...' }, error, resposta => {
  // faz alguma coisa...
})
```

> Nota: Uma vez que não foi chamado o método `connect` a chamada à ação é feita através de HTTP. Mais informação nos _docs_ do [WebSocket](./websocket.html).
