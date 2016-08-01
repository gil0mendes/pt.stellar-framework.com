---
title: Instalação
type: guide
order: 2
---

### Compatibilidade

O Stellar faz uso de todo o potencial do [ECMAScript 6](http://es6-features.org/), por isso, versões do Node.js inferiores à 6 não são suportadas.

### Release Notes

Os detalhes das _releases_ para cada versão estão disponíveis no GitHub na aba [Releases](https://github.com/StellarFw/stellar/releases) e no ficheiro de [Changelog](https://github.com/StellarFw/stellar/blob/dev/CHANGELOG.md).

## NPM

O NPM é o método recomendado para a instalação do Stellar, uma vez que este é usado para satisfazer as dependências não só do _core_ como dos módulos.

```bash
# ultima versão estável
$ npm install -g stellar-fw
```

## Versões de Desenvolvimento

Para usar a versão de desenvolvimento do Stellar apenas tem que fazer o _clone_ do repositório do GitHub. O _branch_ `master` contem a ultima versão estável da framework, já a versão de desenvolvimento encontra-se no _branch_ `dev`.

```bash
# faz o clone do repositório para a pasta stellar
$ git clone https://github.com/StellarFw/stellar stellar

# entra na pasta stellar e instala as dependências
$ cd stellar && npm install

# faz o transpile do código da pasta ‘/src’ para ES5
$ npm run build

# faz o link pelo npm para adicionar a ferramenta de linha de comandos ao 
# sistema
$ npm link
```

> Nota: O `npm link` pode necessitar de permissões de administrador.

