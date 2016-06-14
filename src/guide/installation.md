---
title: Instalação
type: guide
order: 2
---

### Compatibilidade

O Stellar não suporta versões do Node.js inferiores à 6, uma vez que o Stellar faz uso do ECMAScript 6 e algumas funcionalidades não são suportadas por versões anteriores.

### Release Notes

Os detalhes das _releases_ para cada versão estão disponíveis no GitHub na aba [Releases](https://github.com/gil0mendes/stellar/releases) e no ficheiro de [Changelog](https://github.com/gil0mendes/stellar/blob/dev/CHANGELOG.md).

## NPM

Como não poderia deixar de ser o NPM é a forma escolhida para distribuir e instalar o Stellar. 

```bash
# ultima release
npm install stellar 
```

## Versões de Desenvolvimento

Para usar a versão de desenvolvimento do Stellar basta fazer o _clone_ do repositório no GitHub. O _branch_ “master” contem a ultima versão estável da framework, já a versão de desenvolvimento encontra-se no _branch_ “dev”.

```bash
cd directorio/onde_o_stellar_sera_clonado

# faz o clone para a pasta atual
git clone https://github.com/gil0mendes/stellar .

# instala das dependencias do Stellar
npm install

# faz o transpile do código da pasta ‘/src’ para ES5
npm run build 

# adiciona a pasta ‘/bin’ ao path
```


