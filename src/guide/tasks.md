---
title: Tarefas
type: guide
order: 4
---

## O que são e para que servem?

As tarefas são trabalhos que correm separadamente dos pedidos dos clientes. Elas podem ser iniciados por uma ação ou pelo próprio servidor. Com o Stellar, não existe a necessidade de executar um _deamon_ separadamente para processar os trabalhos. O Stellar usa o pacote `node-resque` para armazenar e processar as tarefas.
No Stellar existem três modos de processar as tarefas: normal, com atraso e periodicamente. No processamento normal, as tarefas são inseridas na _queue_ e processadas uma por uma pelo `TaskProcessor`. Quando a tarefa é executada com um atraso, ela é inserida numa _queue_ especial para o efeito onde será processada em algum momento no futuro, o atraso é definido em milissegundos a partir da hora de inserção ou então através de um _timestamp_. Por ultimo, as tarefas com execução periódica são como as tarefas com um atraso, mas são executas com uma certa frequência. As tarefas periódicas não conseguem receber parâmetros de entrada.
Por vezes os _workers_ podem _crashar_ de forma severa que não seja possível notificar o servidor Redis de que vão sair da _poll_ (isto acontece inúmeras vezes em PAAS [Platform As A Service] como o Heroku). Quando isto acontece é necessário extrair a tarefa do _worker_ que morreu, inserida numa _queue_ especial para as tarefas que falharam, para serem reprocessados mais tarde e por fim remover o _worker_.

> NOTA: Recomenda-se o uso de tarefas para o envio de emails e outras operações que podem ser executadas de forma assíncrona, a fim de diminuir o tempo de resposta dos pedidos do cliente.

## Tipos de tarefas

Nesta sub secção será falado um pouco mais dos tipos de tarefas que existem e podem estas podem ser adicionadas ao sistema.

Em primeiro, temos as tarefas normais. Este tipo de tarefas é adicionado numa _queue_ e processadas por ordem de chegada assim que existirem _workers_ livres.

```javascript
// api.tasks.enqueue(nomeDaTarefa, argumentos, queue, callback)
api.tasks.enqueue('sendResetPasswordEmail', { to: 'gil00mendes@gmail.com' }, 'default', (error, toRun) => {
  // tarefa inserida!
})
```

Em seguida, temos as tarefas com atraso. Estas tarefas não inseridas no momento, mas numa _queue_ especial em que serão processadas num dado _timestamp_ ou num atraso de milissegundos. Podem ser executadas quando um determinado _timestamp_ for atingido:

```javascript
// api.tasks.enqueueAt(timestamp, nomeDaTarefa, argumentos, queue, callback)
api.tasks.enqueueAt(1591629508, 'sendNotificationEmail', { to: 'gil00mendes@gmail.com' }, 'default', (error, toRun) => {
  // tarefa inserida!
})
```

Ou, quando um determinado numero de milissegundos ter passado:


```javascript
// api.tasks.enqueueIn(atrazo, nomeDaTarefa, argumentos, queue, callback)
api.tasks.enqueueIn(60000, 'sendNotificationEmail', { to: 'gil00mendes@gmail.com' }, 'default', (error, toRun) => {
  // tarefa inserida!
})
```

## Criar uma ação

As ações estão contidas na pasta `/tasks` dentro de cada módulo. Para gerar uma nova tarefa pode ser usada a ferramenta de linha de comandos, executando o comando `stellar makeTask <nome_da_tarefa> --module=<nome_do_modulo>`. As tarefas têm algumas propriedades obrigatórias, pode encontrar mais informação sobre este assunto no subcapítulo a seguir.

### Propriedades

A lista abaixo encontram-se listadas as propriedades suportadas pelas tarefas. A propriedade `name`, `description` e `run`, são obrigatórias.

* `name`: Nome da tarefa, este deve ser único;
* `description`: Deve conter uma pequena descrição da finalidade da tarefa;
* `queue`: `Queue` onde irá correr a tarefa, por defeito esta propriedade assume o valor de `default`. Este valor pode ser substituído quando é usado o método `api.tasks.enqueue`;
* `frequency`: Caso será superior a zero, será considerada uma tarefa periódica e será executada a cada passar dos milissegundos definidos nesta propriedade;
* `plugins`: Nesta propriedade pode ser declarado um _array_ de _plugins_ resque, estes plugins modificam a forma como a tarefa é inserida na _queue_. Pode ler mais sobre isto na página do [node-resque](https://github.com/taskrabbit/node-resque);
* `pluginOptions`: Trata-se de uma _hash_ com opções para os _plugins_;
* `run(api, params, next)`: Função que contem as operações a serem realizadas pela tarefa.

> NOTA: Para a declaração dos nomes das tarefas é recomendado que seja usado um _namespace_, como por exemplo `auth.sessionValidation`.


### Exemplo

O exemplo a baixo mostra a estrutura de uma tarefa, esta tarefa regista uma mensagem "Hello!!!" a cada 1 segundo:

```javascript
exports.sayHello = {
  name: 'sayHello',
  description: 'I say hello',
  queue: 'default',
  frequency: 1000,

  run: (api, params, next) => {
    // regista uma mensagem
    api.log('Hello!!!')

    // finaliza a execução da tarefa
    next()
  }
}
```
## Gestão das Tarefas

O Stellar disponibiliza alguns métodos que permitem fazer a gestão e verificar o estado das _queues_. Abaixo são apresentados alguns métodos que permitem fazer a gestão das tarefas.

### Remover Tarefas

Remove todas as tarefas que correspondem aos parâmetros passados na função `api.tasks.del(queue, taskName, args, count, callback)`:
  
- **`queue`**: nome da _queue_  onde o comando deve ser executado
- **`taskName`**: nome da tarefa a eliminar
- **`args`**: argumentos de pesquisa (mais informação ver a documentação do `node-resq`)
- **`count`**: numero de instância da tarefa que devem ser removidas.

### Remover Tarefas com Atraso

Remove todas as tarefas com atraso que correspondem aos parâmetros passados na função `api.tasks.delDelayed(queue, taskName, args, count, callback)`:
  
- **`queue`**: nome da _queue_  onde o comando deve ser executado
- **`taskName`**: nome da tarefa a eliminar
- **`args`**: argumentos de pesquisa (mais informação ver a documentação do `node-resq`)

### Limpar uma Queue

O método `api.tasks.delQueue(queue, callback)` remove todas as tarefas de uma _queue_:

- **`queue`**: nome da _queue_ de onde as tarefas serão removidas.

### Trabalhos Recorrentes

O método `api.tasks.enqueueRecurrentJob(taskName, callback)`, permite adicionar trabalho a uma _queue_ como recorrente:

- **`taskName`**: nome da tarefa a ser adicionada.

### Parar Trabalhos Recorrentes

O método `api.tasks.stopRecurrentJob(taskName, callback)`, permite parar um trabalho recorrente:

- **`taskName`**: nome da tarefa a ser removida.

### Timestamps com Tarefas

O método `api.tasks.timestamps(callback)` permite obter um _array_ com todos os _timestamps_ com pelo menos uma tarefa associada.

### Estatísticas

O método `api.tasks.stats(callback)` permite obter um _array_ com todas as estatísticas do _cluster_ do resque.

### Locks

O método `api.tasks.locks(callback)` permite obter um _array_ com todos os _locks_ presentes no _cluster_.

### Remover um Lock

O método `api.tasks.delLock(lockName, callback)` permite remove um _lock_ do _cluster_:

- **`lockName`**: nome do _lock_ a ser removido
- **`callback(removed, error)`**
  - **`removed`**: definido como `1` se o _lock_ for removido
  - **`error`**: instância de `Error` no caso de ter ocorrido um problema durante o pedido.

### Remove as Tarefas de um Timestamp

O método `api.tasks.delDelayesAt(timestamp, callback)` permite remover todas as tarefas do _timestamp_ pedido:

- **`timestamp`**: _timestamp_ de onde as tarefas serão removidas.

### Remove Todas as tarefas com Atraso

O método `api.tasks.allDelayed(callback)` permite remover todas as tarefas com atraso.

### Obter os Workers

O método `api.tasks.workers(callback)` permite obter uma lista com todos os `TaskProcessors` da instância.

### Detalhes

O método `api.tasks.details(callback)` permite obter uma lista com informação das _queue_ da instância.

### Numero de Falhados

O método `api.tasks.failedCount(callback)` devolve o numero de tarefas na _queue_ de operações falhadas.

### Remover uma Tarefa Falhada

O método `api.tasks.removeFailed(failedJob, callback)` permite remover uma tarefas da _queue_ de operações falhadas.

### Tenta Voltar a Executar uma Tarefa que Falhou

O método `api.tasks.retryAndRemoveFailed(failedJob, callback)` permite voltar a tentar executar uma tarefas falhada e remove essa tarefas da _queue_ de operações falhadas.

- **`failedJob`**: nome da tarefa a voltar executar.
