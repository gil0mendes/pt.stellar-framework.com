---
title: Chat
type: guid
order: 8
---

## Para que serve?

O Stellar já vem equipado com uma solução de salas de chat, que pode ser usada com todas as conexões persistentes (socket ou websocket). Existem métodos para criar e gerir as salas de chat e os utilizadores dessas salas. Este sistema pode ser usado para diferentes finalidades, como por exemplo: atualização de dados em tempo real, propagação de informação de forma rápida entre clientes que estejam ligados e até mesmo para criação de jogos multiplayer (uma vez que estes necessitam de constante partilha de informação entre todos os jogadores).Os clientes comunicam com as salas através de verbs. Os verbs são comandos curtos que permitem alterar o estado da conexão, como juntar-se ou sair de uma sala. Os clientes podem estar em diferentes salas ao mesmo tempo. Os verbs mais relevantes são:* roomAdd* roomLeave* roomView* sayEsta funcionalidade pode ser usada out-of-the-box sem que seja necessária qualquer instalação de pacotes adicionais, configuração ou programação. Por defeito, é criada uma sala com o nome “defaultRoom”. Quando o servidor de websocket está ativo é gerado um script de cliente que pode ser usado em aplicações web para facilitar a chamada de ações e as comunicações com as salas de chat.Não existem limites no numero de salas que podem ser criadas, mas é necessário ter em mente que cada sala guarda informação no Redis, assim existe carga por cada ligação criada.

> TODO
 * Métodos
 * Middleware
 * Chat para um cliente especifico
