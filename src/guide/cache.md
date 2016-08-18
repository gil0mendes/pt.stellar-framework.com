---
title: Cache
type: guide
order: 8
---

## Geral

O Stellar já vem equipado com um sistema de _cache_, é permitido usar números, _strings_, _arrays_ e objetos. Trata-se de um sistema distribuído de chave-valor, faz uso de um servidor Redis e pode usar qualquer objeto que seja suportado pela função `JSON.stringify`.

## Usar a cache

No sistema de _cache_ existem três métodos fundamentais para fazer a gestão dos objetivos guardados em _cache_. São estes os métodos `save`, `load` e `destroy`.

### Adicionar uma entrada

Para adicionar uma nova entrada na _cache_ usa-se o método `api.cache.save(chave, valor, msAteExpirar, callback)`, este método também permite atualizar uma entrada já existente. O `msAteExpirar` pode ser `null` no caso de não querer que o objeto expire. O (parâmetro) `callback` é uma função que recebe dois parâmetros `callback(callback, novoObjeto)`, em que o primeiro contem um erro caso exista e o segundo é o novo objeto criado na _cache_. No caso de estar a atualizar um objeto já existente o `novoObjeto` irá assumir o valor de `true`.

```javascript
api.cache.save('websiteTile', 'XPTO Website')
```

> Atenção: Assim que o `msAteExpirar` for atingido a entrada será removida do sistema, mas pode não corresponder ao exato instante.

### Obter uma entrada

Para obter a entrada que se encontra em _cache_ usa-se o método `api.cache.load(cache, callback)` ou `api.cache.load(cache, opções, callback)`, as opções deve ser uma _hash_ que pode contem a propriedade `expireTimeMS `, que irá fazer o _reset_ do tempo de expiração do valor, assim que for lido.

```javascript
api.cache.load('webSiteTitle', (error, value, expireTime, createdAt, readAt) => {
  // faz alguma coisa com o valor lido!
})
```

A função _callback_ recebe os seguintes parâmetros:

- **`error`**: assume o valor de `null` caso não exista erro;
- **`value`**: contem o valor correspondente à chave pedida, ou `null` caso o registo não exista na _cache_ ou tenha expirado;
- **`expireTime`**: tempo em milissegundos em que o objeto irá expirar (tempo do sistema);
- **`createdAt`**: tempo em milissegundos em que o objeto foi criado;
- **`readAt`**: tempo em milissegundos em que o objeto foi lido pela ultima vez através do método `api.cache.load`, isto é útil para saber se o objeto foi consumido recentemente por outro _worker_.

### Remove uma entrada

Para remover uma entrada da _cache_ é tão fácil como chamar o método `api.cache.destroy(key, callback)`.

- **`key`**: nome o objeto a ser destruído;
- **`callback(error, destroyed)`**: função de _callback_;
  - **`error`**: contem a informação do erro, caso tenha ocorrido algum problema;
  - **`destroyed`**: `true` no caso de o objeto ter sido destruído, `false` no caso do objeto não ter sido encontrado.


```javascript
api.cache.destroy('webSiteTile', (error, destroyed) => {
  // faz alguma coisa depois de destruir o objeto
})
```

## Listas

As listas têm um comportamento semelhante a uma _queue_, os elementos são inseridos na cauda e retirados na cabeça da estrutura. As listas são uma excelente forma de guardar objetos que necessitam de ser processados por ordem ou mais tarde.

### Inserir

Para inserir um novo elemento na lista recorre-se ao método `api.cache.push(key, item, callback)`. Caso a lista exista o novo elemento será inserido no fim da lista, caso contrario uma nova lista será criada.

- **`key`**: nome da lista onde pretende inserir o novo elemento;
- **`item`**: item que pretende guardar na lista;
- **`callback(error)`**: Função de _callback_:
  - **`error`**: assume o valor de `null` caso não tenha ocorrido erro.

```javascript
api.cache.push('commands', {player: 'xpto', command: 'exec:abc:param1'}, error => {
  if (error) {
    // ocorreu um erro!
    return
  }

  // elemento inserido
})
```

> Atenção: Apenas pode guardar objetos suportados pela função `JSON.stringify`.

### Obter

Para obter um elemento da lista usa-se o método `api.cache.pop(key, callback)`. Caso a lista que procura não exista, será retornado o valor `null`, caso contrario será obtido o elemento presente na cabeça da lista.

- **`key`**: nome da lista de onde obter o elemento;
- **`callback(error, item)`**: função de _callback_:
  - **`error`**: assume o valor de `null` caso não ocorra erro durante o pedido;
  - **`item`**: item presente na cabeça da lista ou `null`  caso a lista não exista.

```javascript
api.cache.pop('commands', (error, item) => {
  if (error) {
    // ocorreu um erro!
    return
  }

  // faz alguma coisa com o `item`
})
```

### Tamanho

O Stellar, também permite obter o tamanho de uma lista que esteja em _cache_. No caso de ser feito um pedido do tamanho de uma lista que não exista, será devolvido o valor de `0`. Para obter o tamanho usa-se a função `api.cache.listLength(key, callback)`:

- **`key`**: nome da lista que se pretende obter o tamanho;
- **`callback(error, size)`**: função de _callback_:
  - **`error`**: `null` caso não ocorra erro com o pedido;
  - **`size`**: tamanho da lista.

```javascript
api.cache.listLength('commands', (error, size) => {
  if (error) {
    // ocorreu um erro!
    return
  }

  // faz alguma coisa com o tamanho da lista
})
```

## Métodos de Bloqueio

É possível, opcionalmente, usar métodos para bloquear a edição de objetos que se encontram na _cache_. Estes métodos são interessantes para cenários em que o Stellar se encontra a correr num _cluster_, corrigindo possíveis problemas de concorrência.

### Bloquear

O método `api.cache.lock(key, expireTimeMS, callback)` permite bloquear um objeto presente na _cache_. Abaixo encontra-se uma lista que descreve os parâmetros deste método:

- **`key`**: nome do objeto a ser bloqueado;
- **`expireTimeMS`**: este parâmetro é opcional, por defeito irá ser usado o valor definido no ficheiro de configuração `api.config.general.lockDuration`;
- **`callback(error, lockOK)`**: função de _callback_;
  - **`error`**: objeto que contem as informações do erro, caso tenha ocorrido algum;
  - **`lockOK`**: irá tomar o valor de `true` ou `false`, depende se o bloqueio foi feito.

```javascript
api.cache.lock('inTransaction', (error, lockOk) => {
  if (!lockOk) {
    // Foi impossível obter o bloqueio!
    return
  }

  // Faz alguma coisa depois de obter o bloqueio!
})
```

### Desbloquear

Para desbloquear um objeto basta fazer uso do método `api.cache.unlock(key, callback)`. A lista abaixo explica os parâmetros do método _unlock_:

* `key`: nome do objeto a desbloquear;
* `callback(error, lockOK)`: função de _callback_;
  * `error`: tem o valor de `null` caso não tenha ocorrido nenhum erro, caso contrario terá a informação do erro;
  * `lockOK`: `true` no caso de o bloqueio ter sido removido, `false` caso contrario.

```javascript
api.cache.unlock('inTransaction', (error, lockOl’) => {
  if (!lockOk) {
    // foi impossível remover o bloqueio!
    return
  }

  // o bloqueio foi removido!
})
```

### Verifica o Bloqueio

Existe também um método que permite obter o estado de bloqueio de um determinado objeto, `api.cache.checkLock(key, retry, callback)`. A lista abaixo mostra a descrição dos parâmetros:

* `key`: nome do objeto do qual se quer verificar o bloqueio;
* `callback(error, lockOk)`: função de _callback_;
  * `error`: `null` a não ser que tenha ocorrido um erro durante a ligação ao servidor Redis;
  * `lockOk`: `true` ou `false` dependendo do estado do bloqueio.

```javascript
api.cache.chechLock('inTransaction', (error, lockOk) => {
  if (!lockOk) {
    // o objeto não contem um bloqueio!
    return
  }

  // o objeto encontra-se bloqueado!
})
```

### Lista de Bloqueios

O método `api.cache.locks(callback)` permite obter todos os bloqueios ativos na plataforma.

* `callback(error, locks)`: função de _callback_;
  * `error`: `null` ou a informação de erro caso ocorra algum;
  * `locks`: `array` com todos os bloqueios ativos.

```javascript
api.cache.locks((error, locks) => {
  // a variável ‘locks’ é um array que contem todos
  // os bloqueios ativos.
})
```
