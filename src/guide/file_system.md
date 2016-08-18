---
title: Sistema de Ficheiros
type: guide
order: 11
---

## Visão Geral

O Stellar está equipado com um sistema de ficheiros que permite aos clientes fazer pedidos de ficheiros estáticos.

Se for pedido um diretório em vez de um ficheiro, o Stellar irá procurar pelo ficheiro definido em `api.config.general.directoryFileType` (que por defeito contem o valor `index.html`). Se falhar, por não encontrar o ficheiro será devolvido um erro.

Pode-se usar o método `api.staticFile.get(connection, next)` nas ações para obter um ficheiro (em que `next(connection, error, fileStream, mime, length)`), o ficheiro a ser procurado será o definido em `connection.params.file`. Note-se que o fileStream é um _stream_ que pode ser _pipe'd_ para um cliente.

> NOTA: Em sistema operativos *NIX os _links_ simbólicos para pastas e ficheiros são permitidos.

## Clientes Web

Nos clientes _web_ os _headers_ `Cache-Control` e `Expires` serão enviados, o valor destes encontra-se definido na configuração `api.config.general.flatFileCacheDuration`.

Para o _header_ `Content-Type` será usado o pacote [mime](https://npmjs.org/package/mime) para determinar o _mime_ do ficheiro.

Os clientes podem pedir um ficheiro através do parâmetro `file` ao executar a chamada de uma ação que faça uso do método `api.sendFile`, ou então podem fazer um pedido para o URL definido em `api.config.servers.web.urlPathForFiles` (por defeito é `/public`), o Stellar irá procurar pelo ficheiro pedido na pasta `/public`.

Também é possível enviar o conteúdo de um ficheiro diretamente para um cliente, para isso basta usar o método `api.sendFile(connection, null, stream, 'text/html', length)`.

## Outros Clientes

No caso de estar a usar uma ligação que não seja _web_ deve usar o parâmetro `file` para fazer o pedido do ficheiro.

O conteúdo do ficheiro é enviado em `raw`, que pode ser binário ou conter quebras de linha. Deve fazer o _parse_ de acordo com o tipo de pedido que fez.

## Enviar Ficheiros pelas Ações

É possível enviar ficheiros através das ações usando o método `connection.sendFile()`. Abaixo encontra-se um exemplo de uma chamada de sucesso e outra de falha:

```javascript
// ficheiro encontrado
action.connection.sendFile('/path/to/file.mkv')
action.toRender = false
next()

// mensagem de erro
action.connection.rawConnection.responseHttpCode = 404
action.connection.sendFile('404.html')
action.toRender = false
next()
```

> NOTA: Deve definir a propriedade `action.toRender = false` uma vez que já foi enviada uma resposta para o cliente.
