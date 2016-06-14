---
title: Logging
type: guide
order: 11
---

O Stellar faz uso do fantástico pacote [Winston](https://www.npmjs.com/package/winston) para a gestão de _logs_. Usando o Winston é possível melhorar e tornar o sistema de _logs_ extremamente customizável, devido à sua elevada flexibilidade.

## Providers

No ficheiro de configuração `config/logger.js` é possível definir os transportes que pretende usar no projeto. Se não for especificado nenhum provider, por defeito, os _logs_ serão impressos para o stdout. Na documentação do [Winston](https://www.npmjs.com/package/winston) é possível ver quais os providers existentes, alguns deles são: consola, ficheiros, S3 e Riak.

```javascript
export const logger {
  transports: [
    api => {
      return new (winston.transports.Console)({
        colorize: true,
        level: 'debug',
      })
    },
    
    api => {
      return new (winston.transports.File)({
        filename: `./log/${api.pids.title}.log`,
        level: 'info',
        timestamp: true,
      })
    }
  ]
}
```

## Níveis

Existem 8 níveis de _logging_, estes níveis podem ser usados de forma individual por cada _transport_. O níveis são:

* 0 = debug
* 1 = info
* 2 = notice
* 3 = warning
* 4 = error
* 5 = crib
* 6 = alert
* 7 = emerg

> É possível customizar, os níveis e as cores no ficheiro `config/logger.js`.

Por exemplo, se o nível do _log_ for definido para “notice”, mensagens criticas serão visíveis, mas mensagens informativas e de _debug_ não.

```javascript
api.log(‘hello!’) // por defeito é uma mensagem com nível informativo
api.log(‘debug message’, ‘debug’) // não irá aparecer a não ser que o NODE_ENV está definido para debug
api.log(‘Bad things append :(’, ‘emerg’) // será mostrado em todos os níveis 
```

## Métodos

Os métodos `api.logger.log` e `api.logger[severity]` estão acessíveis através do objeto `api` e permitem modificar a instancia do Winston diretamente. O método `api.log` passa a mensagem para todos os _transports_. Abaixo estão alguns exemplos da utilização do método `api.log(message, severity, metadata)`:

```javascript
// este é o caso de uso mais simples, o severity por defeito é igual a ‘info’
api.log(‘hello’)

// o segundo argumento é a severidade da mensagem
api.log(‘Red Alert xD’, ‘emerg’)

// por ultimo, temos um grau de severidade personalizado e metadados
api.log(‘Red Alert xD’, ‘emerg’, { error: new Error(’Some additional information!’) })
```
