---
title: Ações
type: guid
order: 2
---

## O que é uma ação?

As ações são os _building blocks_ do Stellar, está é a unidade básica da Framework. Sendo o Stellar uma Framework baseada em ações, isto significa que existe um repositório com todas as ações registadas no projeto. Uma ação representa uma pequena funcionalidade do projeto, elas podem ser chamadas diretamente pelo cliente ou então por outras ações. As ações podem receber um conjunto de _inputs_ que depois de processados devolvem um conjunto de _outputs_. Estas ações podem ser privadas, podendo apenas serem chamadas por outras ações e não pelo cliente e também podem ser sobrescritas por outros módulos, amenos que se encontrem protegidas contra isso.Os desenvolvedores podem criar as suas próprias ações criando um novo ficheiro na pasta `actions` do módulo ou então recorrer à ferramenta de linha de comandos para gerar o ficheiros e a estrutura de forma automática (`stellar makeAction <nome_da_action> --module=<modulo_onde_criar_a_ação>`).As ações são carregadas para o Engine quando este é iniciado, as ações podem ser chamadas em qualquer zona da aplicação, incluindo em outros módulos.
```javascript
exports.randomNumber = {
    name: 'randomNumber',
    description: 'Generate a random number',
    outputExample: {
        number: 0.40420848364010453
    },

    run: function(api, data, next) {
        // generate a random number
        var number = Math.random()

        // save the generated number on the response property
        data.response.number = number

        // return a formated string
        data.response.formatedNumber = 'Your random number is ' + number

        // finish the action execution
        next()
    }
}
```

As ações são compostas por duas propriedades obrigatórias, uma é a identificação da ação (`name`) e a outra é a lógica (`run`) da ação, mas esta pode contem muitas mais informações adicionais tal como uma descrição, restrições aos valores de _input_, _middleware_ e um exemplo de _output_. Com esta meta informação o Stellar é capaz de gerar documentação de forma totalmente automática sem intervenção humana. Isto é excelente para grandes equipas de forma a que todos os elementos possam de forma fácil ficar a saber de todas as funcionalidades do projeto sem terem que perguntar a outros elementos da equipa. Na figura acima pode-se ver a estrutura de uma ação, esta ação é responsável por gerar uma numero aleatório.As ações são assíncronas e recebem uma referencia para a API (funções partilhadas do Engine), o objeto da conexão e a função de _callback_. Para completar a execução de uma ação basta chamar a função `next(error)`, se existir um erro, tem que se assegurar que se passa uma instância de `Error` e não uma `String`.Por causa da anatomia das ações estas podem ser chamadas internamente pelo cliente ou através de outras ações sem a necessidade de alterações ou escrever código especifico para cada senário de utilização.## Propriedades

Existe um conjunto de opções a que podem ser adicionadas as ações, abaixo encontram-se enumeradas e descritas todas as opções disponíveis.

* `name`: Identificador único da ação;
* `description`: Descreve de forma extensa a ação (o seu objetivo), esta informação é importante para obter a documentação automática;
* `inputs`: Enumera os parâmetros de entrada da ação. Também é possível aplicar restrições aos valores de _input_;
* `middleware`: Indica os _middlewares_ a serem aplicados antes e depois da execução da ação. Os _middlewares_ globais são automaticamente aplicados;
* `outputExample`: Contem um exemplo de uma resposta da ação. Este exemplo será anexado automaticamente à documentação gerada pelo Stellar;
* `blockedConnectionTypes`: Permite bloquear tipos de conexões na ação que está a ser definida;
* `logLevel`: Permite definir como a ação deve ser registada;
* `toDocument`: Por defeito esta opção está definida para `true`, caso contrario não será gerada documentação para esta ação;
* `run`: Por fim, a lógica da ação, trata-se de uma função composta por três parâmetros de entrada (api, action, next).> Alguns dos meta dados, como o caso do `outputExample` e o `description`, são usados para alimentar a documentação automática.## Versões

O Stellar suporta múltiplas versões da mesma ação. Isto permite suportar ações com o mesmo nome, mas com funcionalidades melhoradas. Está funcionalidade é bastante util quando existem muitas aplicações cliente a se alimentar da API e pode-se assim atualizar cada aplicação individualmente para a nova API sem interrupção do serviço nas demais.

As ações podem conter opcionalmente o parâmetro `version` para definir a versão da mesma. A quando o pedido do cliente pode-se usar o parâmetro `apiVersion` para pedir uma versão especifica da ação.

> Nota-se que quando não é especificado o parâmetro `apiVersion` o Stellar irá responder com a ultimas versão da ação.

## Declaração de Inputs

Na declaração das ações podem ser indicados os campos de _input_ recorrendo à propriedade `inputs`, isto fará com que sejam aplicadas restrições aos dados de entrada. Estas restrições podem ser validators já existentes no sistema, uma expressão regular ou uma função que retorna um _boolean_ (em que `true` indica que o valor de _input_ é valido).

A lista a baixo mostra as opções disponíveis para a declaração dos _inputs_:

* `required`: Este campo informa se o parâmetro é obrigatório;
* `default`: Valor por defeito, caso o parâmetro não esteja presente no conjunto de _inputs_ na chamada co cliente;
* `validator`: Valida o parâmetro conta uma ou um conjunto de restrições.

## Parâmetro action

O segundo parâmetro da função run, o objeto `data`, guarda o estado da conexão no momento em que a ação é chamada, neste momento os _midlewares_ de pré processamento já foram executados e os valores de _input_ validados.O objetivo da maioria das ações é realizar uma série de operações e alterar os dados da resposta `data.response`, que posteriormente serão enviados para o cliente. É possível modificar as propriedades da conexão acedendo à `data.connection`, como por exemplo alterar os headers do pedido HTTP.Caso o desenvolvedor não queira que o Engine envie uma resposta para o cliente (por exemplo, já foi enviado um erro), apenas tem que definir a propriedade `data.toRender` para `false`.
———

> TODO
 * middleware
