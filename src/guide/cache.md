---
title: Cache
type: guid
order: 7
---

## Geral

O Stellar já bem equipado com um sistema de cache, é permitido usar números, strings, arrays e objetos. O sistema de cache, mas uso de um servidor Redis definido no ficheiro de configurações, nesse ficheiro também é possível indicar se é para usar um _fake server_ para facilitar no desenvolvimento. Trata-se de um sistema distribuído de chave-valor, pode-se usar qualquer objeto que seja suportado pela função `JSON.stringify`.

## Usar a cache

No sistema de cache existem três métodos fundamentais para fazer a gestão dos objetivos guardados em _cache_. São eles os métodos `save`, `load` e `destroy`.

### Adicionar uma entrada

Para adicionar uma nova entrada na _cache_ usa-se o método `api.cache.save(chave, valor, msAteExpirar, callback)`, este método também permite atualizar uma entrada já existente. O `msAteExpirar` pode ser `null` no caso de não querer que o objeto não expire. O parâmetro `callback` é um função que recebe dois parâmetros `callback(next, novoObjeto)`, em que o primeiro contem um erro caso exista e o segundo é o novo objeto criado na _cache_. No caso de estar a atualizar um objeto já existente o `novoObjeto` irá assumir o valor de `true`.

```javascript
api.cache.save(‘websiteTile’, ‘XPTO Website’)
```

> Atenção: Assim que o `msAteExpirar` for atingido a entrada será removida do sistema, mas pode não corresponder ao exato instante.

### Obter uma entrada

Para obter a entrada que se encontra em _cache_ usa-se o método `api.cache.load(cache, callback)` ou `api.cache.load(cache, opções, callback)`, as opções deve ser uma _hash_ que pode contem a propriedade `expireTimeMS `, que irá fazer o _reset_ do tempo de expiração do valor, assim que for lido.

```javascript
api.cache.load(‘webSiteTitle’, (error, value, expireTime, createdAt, readAt) => {
  // faz alguma coisa com o valor lido!
})
```

A função _callback_ recebe os seguintes parâmetros:

* `error`: assume o valor de `null` caso não exista nenhum erro;
* `value`: contem o valor correspondente à chave pedida, ou `null` caso o registo não exista na _cache_ ou tenha expirado;
* `expireTime`: tempo em milissegundos em que o objeto irá expirar (tempo do sistema);
* `createdAt`: tempo em milissegundos em que o objeto foi criado;
* `readAt`: tempo em milissegundos em que o objeto foi lido pela ultima vez através do método `api.cache.load`, isto é util para saber se o objeto foi consumido recentemente por outro _worker_.

### Remove uma entrada

Para remover uma entrada da _cache_ é tão fácil como chamar o método `api.cache.destroy(key, callback)`.

* `key`: nome o objeto a ser destruido;
* `callback(error, destroyed)`: função de _callback_;
  * `error`: contem a informação do error, caso tenha ocorrido algum problema;
  * `destroyed`: `true` no caso de o objeto ter sido destruído, `false` no caso do objeto não ter sido encontrado.


```javascript
api.cache.destroy(‘webSiteTile’, (error, destroyed) => {
  // faz alguma coisa depois de destruir o objeto
})
```

## Métodos de Bloqueio

É possível, opcionalmente, usar métodos para bloquear a edição de objetos que se encontram na _cache_. Estes métodos são interessantes para cenários em que o Stellar se encontra a correr num _cluster_, corrigindo possíveis problemas de concorrência.

### Bloquear

O método `api.cache.lock(key, expireTimeMS, next)` permite bloquear um objeto presente na _cache_. Abaixo encontra-se uma lista que descreve os parâmetros deste método:

* `key`: nome do objeto a ser bloqueado;
* `expireTimeMS`: este parâmetro é opcional, por defeito irá ser usado o valor definido no ficheiro de configuração `api.config.general.lockDuration`;
* `next(error, lockOK)`: função de _callback_;
  * `error`: objeto que contem as informações do erro, caso tenha ocorrido algum;
  * `lockOK`: irá tomar o valor de `true` ou `false`, depende se o bloqueio foi feito.

```javascript
api.cache.lock(‘inTransaction’, (error, lockOk) => {
  if (!lockOk) { 
    // Foi impossível obter o bloqueio!
    return 
  }

  // Faz alguma coisa depois de obter o bloqueio!
})
```

### Desbloquear

Para desbloquear um objeto basta fazer uso do método `api.cache.unlock(key, next)`. A lista abaixo explica os parâmetros do método _unlock_:

* `key`: nome do objeto a desbloquear;
* `next(error, lockOK)`: função de callback;
  * `error`: tem o valor de `null` caso não tenha ocorrido nenhum erro, caso contrario tera a informação do error;
  * `lockOK`: `true` no caso de o bloqueio ter sido removido, `false` caso contrario.

```javascript
api.cache.unlock(‘inTransaction’, (error, lockOl’) => {
  if (!lockOk) { 
    // foi impossível remover o bloqueio!
    return 
  }

  // o bloqueio foi removido!
})
```

### Verifica o Bloqueio

Existe também um método que permite obter o estado de bloqueio de um determinado objeto, `api.cache.checkLock(key, retry, next)`. A lista abaixo mostra a descrição dos parâmetros:

* `key`: nome do objeto do qual se quer verificar o bloqueio;
* `next(error, lockOk)`: função de _callback_;
  * `error`: `null` a não ser que tenha ocorrido um erro durante a ligação ao servidor Redis;
  * `lockOk`: `true` ou `false` dependendo do estado do bloqueio.

```javascript
api.cache.chechLock(‘inTransaction’, (error, lockOk) => {
  if (!lockOk) {
    // o objeto não contem um bloqueio!
    return 
  }

  // o objeto encontra-se bloqueado!
})
```

### Lista de Bloqueios

O método `api.cache.locks(next)` permite obter todos os bloqueios ativos na plataforma.

* `next(error, locks)`: função de _callback_;
  * `error`: `null` ou a informação de erro caso ocorra algum;
  * `locks`: `array` com todos os bloqueios ativos.

```javascript
api.cache.locks((error, locks) => {
  // a variável ‘locks’ é um array que contem todos 
  // os bloqueios ativos.
})
```
