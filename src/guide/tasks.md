---
title: Tarefas
type: guide
order: 3
---

## O que são e para que servem?

As tarefas são trabalhos que correm separadamente dos pedidos dos clientes. Elas podem ser iniciados por uma ação ou pelo próprio servidor. Com o Stellar, não existe a necessidade de executar uma _deamon_ separadamente para processar os trabalhos. O Stellar usa o pacote `node-resque` para armazenar e processar as tarefas.No Stellar existem três modos de processar as tarefas: normal, com atraso e periodicamente. No processamento normal, as tarefas são inseridas na queue e processadas uma por uma pelo `TaskProcessor`. Quando a tarefa é executada com um atraso, ela é inserida numa _queue_ especial para o efeito onde será processada algum tempo no futuro, o atraso é definido em milissegundos a partir da ora de inserção ou então através de uma _timestamp_. Por ultimo, as tarefas com execução periódica são como as tarefas com um atraso, mas são executas com uma certa frequência. As tarefas periódicas não conseguem receber parâmetros de entrada.Por vezes os _workers_ podem _crashar_ de forma severa que não seja possível notificar o servidor Redis de que vão sair da _poll_ (isto acontece inúmeras vezes em PAAS [Platform As A Service] como o Heroku). Quando isto acontece é necessário extrair a tarefa do _worker_ que morreu, inserida numa _queue_ especial para as tarefas que falharam, para serem reprocessados mais tarde e por fim remover o _worker_.

> Recomenda-se o uso de tarefas para o envio de emails e outras operações que podem ser executadas de forma assíncrona, a fim de diminuir o tempo de resposta dos pedidos do cliente.

## Tipos de tarefas

Nesta sub secção será falado um pouco mais dos tipos de tarefas que existem e podem estas podem ser adicionadas ao sistema.

Em primeiro, temos as tarefas normais. Este tipo de tarefas é adicionado numa _queue_ e processadas por ordem de chegada assim que existirem _workers_ livres.

```javascript
//
// api.tasks.enqueue(nomeDaTarefa, argumentos, queue, callback)
//
api.tasks.enqueue(‘sendResetPasswordEmail’, { to: ‘gil00mendes@gmail.com’ }, ‘default’, (error, toRun) => {
  // tarefa inserida!
})
```

Em seguida, temos as tarefas com atrazo. Estas tarefas não inseridas no momento, mas numa _queue_ especial em que serão processadas num dado _timestamp_ ou num atrazo de milissegundos.

Podem ser executadas quando um determinado _timestamp_ for atingido:

```javascript
//
// api.tasks.enqueueAt(timestamp, nomeDaTarefa, argumentos, queue, callback)
//
api.tasks.enqueueAt(1591629508, ‘sendNotificationEmail’, { to: ‘gil00mendes@gmail.com’ }, ‘default’, (error, toRun) => {
  // tarefa inserida!
})
```

Ou, quando um determinado numero de milissegundos ter passado:


```javascript
//
// api.tasks.enqueueIn(atrazo, nomeDaTarefa, argumentos, queue, callback)
//
api.tasks.enqueueIn(60000, ‘sendNotificationEmail’, { to: ‘gil00mendes@gmail.com’ }, ‘default’, (error, toRun) => {
  // tarefa inserida!
})
```

## Criar uma ação

As ações estão contidas na pasta `/tasks` dentro de cada módulo. Para gerar uma nova tarefa pode ser usada a ferramenta de linha de comandos, usando o comando `stellar makeTask --name=nomeDaTarefa --module=nomeDoModulo`. As tarefas têm algumas propriedades obrigatórias, pode encontrar mais informação sobre este assunto no sub-capitulo a seguir.

### Propriedades

A lista abaixo encontram-se listadas as propriedades suportadas pelas tarefas. A propriedade `name`, `description` e `run`, são obrigatórias.

* `name`: Nome da tarefa, este deve ser único;
* `description`: Deve conter uma pequena descrição da finalidade da tarefa;
* `queue`: `Queue` onde irá correr a tarefa, por defeito esta propriedade assume o valor de `default`. Este valor pode ser substituído quando é usado o método `api.tasks.enqueue`;
* `frequency`: Caso será superior a zero, será considerada uma tarefa periódica e será executada a cada passar dos milissegundos definidos nesta propriedade;
* `plugins`: Nesta propriedade pode ser declarado um _array_ de _plugins_ resque, estes plugins modificam a forma como a tarefa é inserida na _queue_. Pode ler mais sobre isto na página do [node-resque](https://github.com/taskrabbit/node-resque);
* `pluginOptions`: Trata-se de uma _hash_ com opções para os _plugins_;
* `run(api, params, next)`: Função que contem as operações a serem realizadas pela tarefa.

> Para a declaração dos nomes das tarefas é recomendado que seja usado um _namespace_, como por exemplo, “auth::sessionValidation”.


### Exemplo

O exemplo a baixo mostra a estrutura de uma tarefa, esta tarefa loga uma mensagem “Hello!!!” a cada 1 segundo:

```javascript
exports.sayHello = {
  name: 'sayHello',
  description: 'I say hello',
  queue: 'default',
  frequency: 1000,

  run: (api, params, next) => {
    // loga uma mensagem
    api.log(“Hello!!!”)

    // finaliza a execução da tarefa
    next()
  }
}
```



> TODO
 * Adicionar tarefas periódicas
 * Gerir as tarefas/trabalhos
 * Gerir as tarefas falhadas
