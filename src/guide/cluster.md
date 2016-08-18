---
title: Cluster
type: guide
order: 7
---

## Objetivo

O Stellar pode ser executado num servidor único ou como parte de um _cluster_. O objetivo do _cluster_ é criar um conjunto de servidores que partilham o mesmo estado entre eles de forma a responderem a um maior numero de pedidos dos clientes e executar tarefas. Com este mecanismo, é possível adicionar e remover nós do _cluster_ sem haver perda de dados ou tarefas duplicadas. Também é possível executar múltiplas instâncias do Stellar na mesma máquina usando o comando `stellar startCluster`.

O nome das instâncias do _cluster_ sequenciais, começando em `stellar-worker-1`. O nome da instância pode ser obtido chamando o `api.id`.

## Cache

Uma vez que o Stellar usa um _backend_ em Redis para reter informação das tarefas a serem executadas e objetos em _cache_, o _cluster_ tira partido desse sistema para partilhar a mesma informação através de todos os nós. Isto faz com que não seja necessário qualquer alteração no código para aplicação poder fazer _deploy_ num _cluster_.

> Atenção: Outros clientes/servidores podem aceder à _cache_ em simultâneo. É necessário ter em atenção como se desenvolve as ações para não haver conflitos. Pode ler mais sobre [_cache_ aqui](cache.html).

## RPC

O Stellar implementa um sistema de _Remote Procedure Calls_ (RPC), que permite executar um determinado comando em todos os nós do _cluster_ ou num nó especifico através do objeto _connection_. Para fazer uso deste sistema apenas tem que usar o método `api.redis.doCluster(metodo, argumentos, Id_da_conexao, callback)`, ao especificar um _callback_, irá receber a primeira resposta do _cluster_ (ou um erro de _timeout_).

### Exemplo

O exemplo abaixo, faz com que todos os nós imprimam os seus ID para o ficheiro de logs.

```javascript
api.redis.doCluster('api.log', [`olá a partir do nó ${api.id}`])
```

> Atenção: Este mecanismo permite executar qualquer método da API, incluindo a função de `stop()`.

## Redis Pub/Sub

Também está disponível um mecanismo de pub/sub, através do Redis, que permite comunicações entre os nós do _cluster_. É possível enviar mensagens em _broadcast_ e receber mensagens de outros nós do _cluster_ usando o método: `api.redis.publish(payload)`. O _payload_ deve conter as seguintes propriedades:

- **`messageType`**: Nome to tipo de _payload_;
- **`serverId`**: Id do servidor, `api.id`;
- **`serverToken`**: `api.config.general.serverToken`


### Exemplo

O exemplo a seguir mostra como é possível subscrever a um determinado tipo de mensagem:

```javascript
api.redis.subscriptionHandlers['tipoDaMensagem'] = menssage => {
  // faz alguma coisa!
}
```

Para enviar uma mensagem deve ser usado um código parecido com o seguinte:

```javascript
let payload = {
  messageType: 'tipoDaMensagem',
  serverId: api.id,
  serverToken: api.config.general.serverToken,
  message: 'Conteúdo da mensagem!'
}

api.redis.publish(payload)
```

> O `api.config.general.serverToken` permite autenticar a mensagem no _cluster_.
