---
title: Modo de Desenvolvimento
type: guide
order: 10
---

## O que é?

O modo de desenvolvimento, tal como o próprio nome diz, é um modo especial para facilitar o desenvolvimento dos módulos e aplicações no Stellar. Ao alterar os ficheiros de rotas, tarefas, ações e modelos, o servidor consegue substituir essa lógica em memória logo que seja detetada uma alteração no sistema de ficheiros. Deste modo não terá que estar constantemente a parar e a reexecutar o servidor a cada alteração feita. Alterações mais severas, como configurações e [Satellites](satellites.html) fazem com que o servidor reinicie por completo, tudo é feito de forma automática.

Para ativar o modo de desenvolvimento basta criar um ficheiro (caso ainda não tenha um) `config/api.js` e definir a opção `developmentMode` para `true`:

```javascript
'use strict'

exports.default = {
  general: api => {
    return {
      developmentMode: true,
    }
  }
}
```

> Atenção: a propriedade `api.config.general.developmentMode` é diferente do `NODE_ENV`. O ambiente apenas informa o Stellar de qual configurações usar, por defeito é _development_, mas não tem nenhum efeito no `developmentMode`.

## Efeitos

Quando o modo de desenvolvimento está ativo o Stellar vai recarregar ações, tarefas, modelos, configurações e [Satellites](satellites.html) assim que eles forem modificados, tudo _on the fly_.

- uma vez que o Stellar faz uso do método `fs.watchFile()` o recarregar pode não funcionar em todos os sistemas operativos / sistemas de ficheiros;
- novos ficheiros não serão carregados, apenas os ficheiros com que a instância foi iniciada serão monitorizados;
- apagar um ficheiro pode causar num _crash_ da aplicação, o Stellar não tenta recarregar ficheiros apagados;
- se o valor da frequência em que uma tarefa periódica é executada (`task.frequency`) for alterado, será usado o valor antigo até que essa tarefa seja "disparada" de novo;
- ao alterar configurações e ou [Satellites](satellites.html), será feito um _reboot_ total ao servidor e não apenas dos ficheiros alterados.
