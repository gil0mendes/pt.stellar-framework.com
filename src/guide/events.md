---
title: Eventos
type: guide
order: 14
---

## O que é?

O Stellar possui um sistema de eventos que permite subscrever e ficar à escuta por eventos na aplicação. Isto é util para manipular dados durante a execução ou estender funcionalidades adicionando novos comportamentos à lógica existente. Os _listeners_ devem estar armazenados na pasta `listeners` dos módulos.

## Gerar Listeners

Claro que, criar manualmente os ficheiros para cada _listener_ é pesado. Em vez, os desenvolvedores podem recorrer à ferramenta de linha de comandos para o fazer de forma automática:


```shell
stellar generateEvent <nomeDoEvento> --module=<nomeDoModulo>
```

## Definir um Listener

O código abaixo mostra a implementação de um _listener_, neste exemplo o _listener_ irá responder ao evento `social.newComment` e irá adicionar uma nova tarefa ao sistema para proceder ao envio de um email a cada comentário feito na aplicação.

```javascript
// Ficheiro: social/listeners/comments.js

'use strict'

exports.default = [{
  event: 'social.newComment',
  run: (api, params, next) {
    // envia um novo email notificando o novo comentário
    api.tasks.enqueue('sendNewCommentEmail', params)
    
    // cria uma nova propriedade chamada `emailSent` e define-a para `true`
    params.emailSent = true

    // next(error <Error>)
    next()
  }
}]
```

## Disparar Eventos

O código abaixo mostra a forma de como um evento pode ser disparado. Neste caso o desenvolvedor quer disparar o evento `social.newComment` e dar aos _listeners_ uma variavel com os dados do novo comentário:

```javascript
api.events.fire('social.newComment', newComment, response => {
  // faz alguma coisa com os dados modificados...
})
```

## Registar um Listener Manualmente

Para registar um _listener_ manualmente o desenvolvedor pode usar a seguinte API:

```javascript
api.events.listener('blog.newUser', (api, params, next) => {
  // faz alguma coisa...

  // termina a execução do listener
  next()
})
```