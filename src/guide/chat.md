---
title: Chat
type: guide
order: 9
---

## Para que serve?

O Stellar vem equipado com uma solução de salas de _chat_, que pode ser usada com todas as conexões persistentes (socket ou websocket). Existem métodos para criar e gerir as salas de _chat_ e os utilizadores dessas salas. Este sistema pode ser usado para diferentes finalidades, como por exemplo: atualização de dados em tempo real, propagação de informação de forma rápida entre clientes que estejam ligados e até mesmo para criação de jogos _multiplayer_ (uma vez que estes necessitam de constante partilha de informação entre todos os jogadores).

Os clientes comunicam com as salas através de _verbs_. Os _verbs_ são comandos curtos que permitem alterar o estado da conexão, como juntar-se ou sair de uma sala. Os clientes podem estar em diferentes salas ao mesmo tempo. Os _verbs_ mais relevantes são:

- roomAdd
- roomLeave
- roomView
- say

Esta funcionalidade pode ser usada _out-of-the-box_ sem que seja necessária qualquer instalação de pacotes adicionais, configuração ou programação. Por defeito, é criada uma sala com o nome "defaultRoom". Quando o servidor de websocket está ativo é gerado um _script_ de cliente que pode ser usado em aplicações _web_ para facilitar a chamada de ações e a comunicação com as salas de _chat_.

Não existe limite de salas que a ser criadas, mas é necessário ter em mente que cada sala guarda informação no Redis, assim existe carga por cada ligação criada.

## Métodos

Existem métodos que permitem gerir as salas de _chat_ e os seus membros. Estes métodos não estão disponíveis diretamente para o cliente, mas podem ficar caso crie uma ação.

### Broadcast

O método `api.chatRoom.broadcast(connection, room, message, callback)` permite emitir uma mensagem para todos os membros de uma determinada sala. O parâmetro `connection` pode ser uma conexão real de uma mensagem a chegar de um cliente, ou pode ser uma conexão construída manualmente. As conexões construídas deve por menos conter a propriedade `{room: 'umaOutraRoom'}`, quando não é especificado um `id` é assumido o valor de `0`.

```javascript
api.chatRoom.broadcast({room: 'general'}, 'general', 'Olá!', error => {
  // faz alguma coisa depois da mensagem ser enviada!
})
```

### Lista das rooms

O método `api.chatRoom.list(callback)` permite obter a lista de _rooms_ existentes. O código de exemplo abaixo lista todas as _rooms_ existentes na consola.

```javascript
api.chatRoom.list((error, rooms) => {
  for (let k in rooms) { console.log(`${k} => ${rooms[k]}`) }
})
```

### Criar uma room

Para criar uma _room_ usa-se o método `api.chatRoom.add(room, callback)`. A função _callback_ recebe um parâmetro que assume o valor de `0` quando a _room_ já existe e de `1` caso ela tenha sido criada. O código abaixo mostra a criação de uma nova _room_ com o nome de "labs":

```javascript
api.chatRoom.add('labs', res => {
  if (res === 0) {
    // a room já existe!
    return
  }

  // a room foi criada!
})
```

### Remover uma room

Usando o método `api.chatRoom.destroy(room, callback)` pode-se remover uma _room_. A função _callback_ não recebe  parâmetros, a _room_ é sempre removida, o código a seguir mostra como a remoção pode ser feita:

```javascript
api.chatRoom.destroy('labs', () => {
  // a room foi removida!
})
```

### Verifica se uma room existe

Pode-se usar o método `api.chatRoom.exists(room, callback)` para verificar se uma _room_ existe na instância do Stellar. A função `callback(error, found)` recebe dois parâmetros:

* `error`: assume o valor de `null` no caso se não ocorrer nenhum problema;
* `found`: `true` no caso da _room_ ter sido encontrada, `false` caso contrario.

O código abaixo mostra a verificação da existência da _room_ "coffeTable":

```javascript
api.chatRoom.exists('coffeTable', (error, found) => {
  if (!found) {
    // a room não existe!
    return
  }

  // a room existe!
})
```

### Obter o estado de uma room

Através do método `api.chatRoom.roomStatus(room, callback)` é possível obter informações do estado da _room_. A função `callback(error, state)`, recebe dois parâmetros:

* `error`: `null` no caso de não ocorrer um erro durante a chamada do método;
* `state`: é uma _hash_ que contem informação sobre a _room_, nome, número de membros inscritos e a lista desses membros.


O código abaixo mostra como essa informação pode ser obtida e em seguida uma possível resposta:

```javascript
api.chatRoom.roomStatus('Random', (error, status) => {
  // faz alguma coisa com a informação da room!
})
```

```javascript
{
  room: 'Random',
  membersCount: 3,
  members: {
    g0m: {id: 'g0m', joinedAt: 1465829955},
    afls: {id: 'afls', joinedAt: 1465829985},
    amg: {id: 'amg', joinedAt: 1465830011}
  }
}
```

### Adicionar um membro

Para adicionar um novo membro usa-se o método `api.chatRoom.addMember(connectionId, room, callback)`, é necessário o ID da conexão do cliente e o nome da _room_ onde se quer adicionar o novo membro. A função `callback(error, wasAdded)` recebe dois parâmetros:

* `error`: `null` no caso de não ocorrer erro durante a chamada;
* `wasAdded`: pode assumir o valor de `true` ou `false` dependendo se o membro foi adicionado ou não.

```javascript
api.chatRoom.addMember(idDaConexao, 'newUsers', (error, wasAdded) => {
  if (!wasAdded) {
    // não foi possível adicionar o novo membro!
    return
  }

  // cliente adicionado como novo membro!
})
```

> É possível adicionar conexões do servidor atual ou de outro qualquer que faça parte do _cluster_.

### Remover um membro

O método `api.chatRoom.removeMember(connectionId, room, callback)` permite remover um membro de uma dada _room_. Para isso é necessário o ID da conexão do membro a ser removido da _room_ de onde se pretende remover. A função `callback(error wasRemoved)` recebe dois parâmetros:

* `error`: `null` no caso de não ocorrer erro durante a operação;
* `wasRemoved`: `true` no caso do membro ter sido removido, `false` caso contrário.

```javascript
api.chatRoom.removeMember(idDaConexao, 'heaven', (error, wasRemoved) => {
  if (!wasRemoved) {
    // o membro não foi removido!
  }

  // o membro foi removido da room!
})
```

> É possível remover conexões do servidor atual ou de qualquer outro servidor do _cluster_.

## Middleware

Existem quatro tipos diferentes de _middleware_ que podem ser instalados no sistema de _chat_: `say`, `onSayReceive`, `join` e `leave`. Toda a documentação sobre os _middlewares_ está disponível na [secção](./middleware.html) criada especificamente para esse tema.

## Comunicar para um cliente especifico

Cada objeto de conexão contém o método `connection.sendMessage(message)`, este método está acessível diretamente através do servidor.

```javascript
connectionObj.sendMessage('Bem-Vindo ao Stellar :)')
```

## Funções do Cliente

A forma como é possível comunicar através do cliente encontra-se descrita na sub-secção de cada tipo de servidor bidirecional [websocket](websocket.html) e [TCP](tcp.html).
