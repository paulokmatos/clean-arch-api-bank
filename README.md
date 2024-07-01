# Desafio Técnico - OBJECTIVE

## Resumo do Projeto
O projeto consiste em um desafio técnico da criação de uma API que realiza criação de contas e operações de pagamento.

## Requisitos do Projeto

- Docker
- Docker Compose

## Tecnologias utilizadas

#### **Infraestrutura:** 
 - **Docker:** Ferramenta de virtualização de containers.
 - **Docker Compose:** Utilizado para definir e gerenciar os containers Docker.

#### Análise de Código
 - **PHP CS FIXER:** Ferramenta para formatação automática do código PHP.
 - **PHPStan:** Ferramenta de análise de código estática

#### Testes Unitários
  - **PHPUnit:** Framework para testes unitários em PHP

#### Desenvolvimento
  - **Bramus Router:** Biblioteca de roteamento em PHP utilizada para definir e gerenciar as rotas da aplicação.
  - **PDO (PHP Data Objects):** Manipulação de banco de dados mysql.

### Como iniciar o projeto

 1 - Clone o repositório

``` bash
git clone https://github.com/paulokmatos/objbank.git
cd objbank
```

 2 - Suba os containers da aplicação
 ``` bash
docker compose up -d
```

 3 - Dentro da pasta do repositório, instale as dependencias do composer utilizando o comando:
 
``` bash
docker exec -i -t obj-bank-api composer install
```

 4 - Copie o arquivo .env.example
 ``` sh
cp .env.example .env
```
 

 Após executar este comando, o projeto estará acessível na URL: http://localhost:8080.
 
 # Fluxo da Aplicação:

 ![image](https://github.com/paulokmatos/objbank/assets/68530385/3ef7ee57-41c7-4ee7-9faa-449743112c7f)

# Arquitetura do projeto 

**Domain:** Contém as Entities e ValueObjects do domínio.
**Application:** Inclui os UseCases, Factories e Strategies.
**Infra:** Responsável pela interação com o banco de dados.
**Presentation:** Contém os Controllers que gerenciam as requisições HTTP.

# Endpoints do projeto:
[ POST ] /conta <br />
exemplo de entrada:
``` json
{
  "numero_conta": 234,
  "saldo": 180.37
}
```

exemplo de saída: <br />
**HTTP STATUS 201**
``` json
{
  "numero_conta": 234,
  "saldo": 180.37
}
```
**HTTP STATUS 422** (Caso não enviado dados inválidos)


[ GET ] /conta <br />
exemplo de entrada: <br/>
**HTTP STATUS 200**
``` json
{
  "numero_conta": 234
}
```
exemplo de saída: 
``` json
{
  "numero_conta": 234,
  "saldo": 180.37
}
```
**HTTP STATUS 404 (Caso a conta não exista)**

[ POST ] /transacao <br />

Formas de pagamento disponíveis <br />
- P => Pix (livre de taxas)
- C => Cartão de Crédito (aplicada a taxa de 5%)
- D => Cartão de Débito (aplicada a taxa de 3%)

exemplo de entrada:
``` json
{
  "forma_pagamento": "D",
  "numero_conta": 234,
  "valor": 10,
}
```

exemplo de saída: <br />
**HTTP STATUS 200**
``` json
{
	"numero_conta": "234",
	"saldo": "170.07"
}
```
**HTTP STATUS 404 (Caso não tenha saldo disponível)**

