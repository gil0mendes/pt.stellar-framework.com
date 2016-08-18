---
title: TCP
type: guide
order: 14
---

## Visão Geral

Também é possível interagir com o Stellar usando uma ligação persistente através de um _socket_ TCP. Por defeito a porta usada é a `5000`, mas esta pode ser alterada através da propriedade `api.config.tcp.port`. Uma vez que se trata de uma ligação persistente a forma de funcionamento é semelhante à do [WebSocket](websocket.html), onde se utiliza _verbs_ para passar comandos ao servidor. A lista abaixo mostra os _verbs_ disponíveis:

- **`quit`**: termina a ligação com o servidor (a sessão é destruída)
- **`paramAdd`**: guarda uma variável que fica anexada à conexão
  - Exemplo: `addParam query=something`
- **`paramView`**: devolve o valor de um parâmetro, caso este exista
  - Exemplo: `paramView query`
- **`paramDelete`**: remove um parâmetro
  - Exemplo: `paramDelete query`
- **`paramsView`**: devolve um objeto JSON com todos os parâmetros
- **`paramsDelete`**: remove todos os parâmetros definidos
- **`roomAdd`**: junta o cliente a uma sala de _chat_
- **`roomLeave`**: faz o cliente deixar sala de _chat_
- **`detailsView`**: obtém os detalhes da conexão do cliente, inclui o ID publico
- **`say`**: envia uma mensagem para uma sala de _chat_.

> Nota: os parâmetros adicionados nas chamadas anteriores são fixados à conexão, isso quer dizer que é necessários remover os parâmetros antes de chamar novos _verbs_.

![Telnet TCP](/images/telnet_tcp.png)

Uma das principais vantagens de utilizar uma ligação TCP é a possibilidade de poder chamar várias ações em simultâneo. O Stellar mantêm um contador das chamadas feitas, deste modo é possível manter a gestão das diferentes chamadas a decorrer.

## TLS

O servidor TCP suporta ligações cifradas através de TLS, caso seja desejado. Para isso é necessário fazer algumas pequenas configurações no servidor:

```javascript
exports.default = {
  servers: {
    socket: api => {
      secure: true,
      key: fs.readFileSync('certs/server-key.pem'),
      cert: fs.readFileSync('certs/server-cert.pem')
    }
  }
}
```

A ligação segura pode ser testada com o comando abaixo:

```shell
$ openssl s_client -connect 127.0.0.1:5000
```

Ou então em outro processo node:

```javascript
let fs = require('fs')
let tls = require('tls')

let options = {
  key: fs.readFileSync('certs/server-key.pem'),
  cert: fs.readFileSync('certs/server-cert.pem')
}

let socket = tls.connect(5000, options, () => {
  console.log('cliente', socket.authorized ? 'autorizado' : 'não autorizado')
})

socket.setEnconding('utf8')
socket.on('data', data => console.log(data))
```

## JSON

A forma por defeito para executar ações no Stellar através de uma ligação TCP é usando os _verbs_ disponíveis nas ligações persistentes. Contudo, é possível recorrer a JSON para escolher a ação a executar e enumerar os parâmetros a usar com essa ação. Por exemplo, `{"action": "actionName", "params": {"key": "some_value"}}`.
