---
title: Satélites
type: guide
order: 6
---

## O que é um Satellite?

O Engine por si só não faz absolutamente nada, a única lógica que este possui logo que a instância do Stellar é iniciada, é a de procurar pelos Satellites, estes é que irão ditar os próximos passos a serem dados.

Os Satellites são o nome dado aos componentes que permitem estender e subscrever as funcionalidades do Stellar. Através deste mecanismo é possível isolar as funcionalidades por áreas, facilitando a manutenção do core, tornar a _Framework_ extremamente extensível e permitindo aos desenvolvedores criar módulos que permitam estender as funcionalidades base do Stellar.

Todo o core do Stellar é criado por satellites, estes carregam as funcionalidades básicas da _Framework_, mas o _core_ não é o único local onde estes componentes podem existir. Os módulos também podem fazer uso deles para carregar novas funcionalidades, subescrever módulos existentes e até realizar tarefas assim que a _Framework_ inicie ou termine a sua execução.

## Lifecycle

Todos os Satellites passam por uma série de fases de inicialização durante a execução de uma instância do Stellar. Abaixo é explicado esse processo e o que deve ser realizado em cada uma das três etapas.

![Fases de um Satellite](/images/satellite_stages.png)

Como se pode verificar, a imagem acima mostra as três fazes de carregamento de um Satellite, são elas: _load_, _start_ e _stop_. A etapa de carregamento é obrigatória, enquanto a inicialização e a paragem são opcionais. No caso de ser iniciada uma operação sem previsão de paragem, na etapa de inicialização, é recomendado efetuar a sua paragem na terceira etapa (_stop_), isto porque é possível reiniciar o servidor, sem que o processo de execução do Stellar tenha que ser terminado.


Na fase de carregamento do Satellite, deve ser carregada toda a lógica no objeto da API de forma a tornar as funcionalidades públicas, nenhum tipo de operação complexa deve ser realizada nesta fase, o carregamento deve ser feito o mais rapidamente possível. Na fase de inicialização devem ser iniciadas todas as tarefas continuas, como por exemplo servidores ou algum outro tipo de _listener_. Por fim, na etapa de paragem todas as tarefas pendentes não concluídas devem ser terminadas, assim como todos os servidores.

## Formato

Um Satellite deve ser uma classe escrita segundo o padrão [ES6](http://www.ecma-international.org/ecma-262/6.0/index.html). O único requisito para o Satellite ser carregado pelo Stellar é conter um método `load(api, next)`. Existem outras propriedades, que se encontram descritas abaixo:

- **`loadPriority`**: Permite alterar a ordem de carregamento do Satellite, o valor por defeito é 100;
- **`startPriority`**: Permite alterar a ordem de inicio do Satellite, o valor por defeito é 100;
- **`stopPriority`**: Permite alterar a ordem de paragem, o valor por defeito é 100;
- **`load(api, next)`**: Operação a ser executada aquando o carregamento do Satellite;
- **`start(api, next)`**: Operação a ser executada aquando o inicio do Satellite;
- **`stop(api, next)`**: Operação a ser executada quando o Satellite for terminado.

## Exemplo

```javascript
'use strict'

/**
 * Class do satellite.
 *
 * É recomendado usar esta classe apenas para especificar as funções do 
 * satellite, toda a outra lógica deve ser escrita numa classe à parte.
 */
exports.default = class {

    /**
     * Construtor.
     *
     * O densevolvedor deve definir a prioridade do sattelite nos suas 
     * diferentes etapas.
     */
    constructor () {
      // define a prioridade de carregamento
      this.loadPriority = 10
    }

    /**
     * Função de carregamento.
     *
     * @param  {{}}}      api  Referencia para a API
     * @param  {Function} next Função de callback
     */
    load (api, next) {
      // regista uma mensagem
      api.log('This is awesome!', 'info')

      // finaliza o carregamento do satellite
      next()
    }

}
```
