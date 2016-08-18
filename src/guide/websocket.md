---
title: WebSocket
type: guide
order: 13
---

## Visão Geral

O Stellar usa o [Primus](http://primus.io) para trabalhar com _web sockets_. O Primus cria uma camada de abstração sobre o _engine_ do websocket, ele suporta: `ws`, `engine.io`, `socket.io`, entre outros. O _web socket_ encontra-se ligado ao servidor _web_, seja ele HTTP ou HTTPS.

Quando o Stellar inicia é gerado um _script_ com algumas funções úteis para fazer a ligação entre o cliente e o servidor. Esse _script_ pode ser obtido através da chamada do URL `http(s)://stellar_domain.com/stellar-client`.

## Métodos

Abaixo estão expostos todos os métodos disponibilizados pelo cliente para que possa interagir com o servidor:

### Abrir ligação

O método `client.connect(callback)` permite abrir uma ligação com o servidor:

- **`callback(error, detailsView)`**: função de _callback_
  - **`error`**: objeto com o erro que ocorrido durante a chamada ao servidor, caso exista
  - **`detailsView`**: o mesmo que o método `detailsView`

### Chamar uma Ação

O método `client.action(action, params, callback)` permite chamar uma ação:

- **`action`**: nome da ação a ser chamada, por exemplo: "auth.signin"
- **`params`**: objeto com os parâmetros anexos à ação
- **`callback(response)`**: função de _callback_
  - **`response`**: contem a resposta do servidor.

> NOTA: Quando não existe uma ligação aberta através de websocket, seja feito uma _fallback_ para HTTP.

### Enviar Mensagem

O método `client.say(room, message, callback)` permite enviar uma mensagem para uma sala de _chat_:

- **`room`**: sala para onde a mensagem será enviada
- **`message`**: mensagem a ser enviada
- **`callback(error)`**: função de _callback_
  - **`error`**: contem a informação do erro, caso tenha ocorrido algum.

> NOTA: Tem que usar o método `roomAdd` antes de poder interagir com um sala.

### Detalhes

O método `client.detailsView(callback)` permite obter detalhes sobre a conexão do cliente:

- **`callback(error, response)`**: função de _callback_
  - **`error`**: pode conter uma instância de `Error`
  - **`response`**: contem um objeto com os detalhes da conexão

> NOTA: A primeira resposta do `detailsView` é guardada para ser usado no futuro.

### Estado de uma Sala

O método `client.roomView(room, callback)` permite obter alguns meta-dados da sala pedida.

- **`room`**: nome da sala a obter os meta-dados
- **`callback(response, error)`**: função de _callback_
  - **`response`**: objeto com os meta-dados da sala de _chat_ pedida
  - **`error`**: contem uma instância de `Error`, no caso de ter ocorrido um

### Juntar-se a uma Sala

O método `client.roomAdd(room, callback)` permite juntar o cliente a uma sala de _chat_:

- **`room`**: nome da sala onde juntar o cliente
- **`callback(error)`**: função de _callback_
  - **`error`**: pode conter uma instância de `Error`

### Sair de uma Sala de Chat

O método `client.roomLeave(room, callback)` permite um cliente abandonar uma sala de _chat_ de que faz parte:

- **`room`**: sala de onde o cliente deve ser removido
- **`callback(error)`**: função de _callback_
  - **`error`**: pode ter uma instância de `Error`, caso aconteça um

### Pede um ficheiro

O método `client.file(file, callback)` permite um cliente pedir um ficheiro estático ao servidor.

- **`file`**: caminho para o ficheiro a ser pedido
- **`callback(response, error)`**: função de _callback_
  - **response**: objeto com o ficheiro pedido

A resposta assemelha-se a estrutura abaixo:

```json
{
  "content": "Conteúdo do ficheiro",
  "context": "response",
  "error": null,
  "lenght" 20,
  "messageCount" : 3,
  "mime": "text/txt"
}
```

### Desconectar o Cliente

O método `client.disconnect()` permite desconectar o cliente do servidor.

## Eventos

A lista abaixo mostra os eventos disponibilizados pelo cliente.

### Connected

O evento `connected` acontece quando o cliente se conecta ao servidor.

```javascript
client.on('connected', () => { })
```

### Disconnected

O evento `disconnected` acontece quando o cliente de desliga do servidor.

```javascript
client.on('disconnected', () => { })
```

### Error

O evento `error` acontece quando ocorre um erro fora da execução de um evento ou _verb_.

```javascript
client.on('error', error => { })
```

### Reconnect

O evento `reconnect` acontece quando a ligação entre o servidor e o cliente é quebrada temporariamente.

```javascript
client.on('reconnect', () => { })
```

> Nota: os detalhes da conexão podem-se ter alterado

### Reconnecting

O evento `reconnecting` acontece quando o cliente se tenta voltar a ligar ao servidor.

```javascript
client.on('reconnecting', () => { })
```

### Message

O evento `message` acontece quando o cliente recebe uma nova mensagem.

```javascript
client.on('message', message => { })
```

> Atenção: este evento acontece sempre que o cliente recebe uma nova mensagem, é um evento muito "barulhento"

### Alert

O evento `alert` acontece quando o cliente recebe uma mensagem do servidor com o contexto `alert`.

```javascript
client.on('alert', message => { })
```

### API

O evento `api` acontece quando o cliente recebe uma mensagem com um contexto desconhecido.

```javascript
client.on('api', message => { })
```

### Welcome

O evento `welcome` acontece quando o servidor envia a mensagem de boas vindas quando o cliente se liga.

```javascript
client.on('welcome', message => { })
```

### Say

O evento `say` acontece quando o cliente recebe mensagens de outros clientes em todas as salas.

```javascript
client.on('say', message => { })
```

> Nota: A propriedade `message.room` permite saber a origem da mensagem
