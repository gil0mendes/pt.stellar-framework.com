---
title: Segurança
type: guide
order: 17
---

# Introdução

Stellar vem equipado com um sistema de _hashing_, que faz uso do biblioteca [bcrypt](https://www.npmjs.com/package/bcrypt). Este sistema permite calcular _hashes_ e comparar-las com dados em claro a fim de validar-los.

## Calcular Hashes

O método `api.hash.hash` e `api.hash.hashSync` permite gerar uma _hash_ a partir de uma _string_ de forma assíncrona e síncrona, respetivamente.

```javascript
// gerar uma hash de forma síncrona
let hash = api.hash.hashSync(plainData)

// gerar uma hash de forma assíncrona
api.hash.hash(plainData).then(hash => {
  // faz alguma coisa com a hash...
})
```

## Comparar Hashes

O método `api.hash.compare` e `api.hash.compareSync` permite comprar uma _string_ com uma _hash_ a fim de validar se estas correspondem.

```javascript
// comparar uma hash de forma síncrona
let result = api.hash.compare(plainData, hashToCompare)

// comparar uma hash de forma assíncrona
api.hash.compareSync(plainData, hashToCompare).then(isValid => {
  // faz alguma coisa com o resultado...
}) 
```
