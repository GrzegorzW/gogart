# gogart


# Table of contents
* [Requirements](#Requirements)
* [Installation](#Installation)
* [Usage](#Usage)
* [Routes](#routes)

## Requirements

* docker
* docker-compose


## Installation

In order to setup project execute:

```
cp .env.dist .env
```

```
./install.sh
```

## Usage

In order to run project execute:

```
docker-compose up -d
```

## [Routes](config/routes.yaml)

### Product

* [POST] /products

```
{
	"title": "Example title",
	"priceAmount": 100,
	"priceCurrency": "USD"
}
```

* [PATCH] /products/{productId}/price

```
{
	"priceAmount": "500",
	"priceCurrency": "USD"
}
```

* [PATCH] /products/{productId}/title

```
{
	"title": "New title"
}
```

* [DELETE] /products/{productId}

* [GET] /products?page=1&perPage=2


### Cart


* [POST] /carts

* [POST] /carts/{cartId}/products/{productId}

* [DELETE] /carts/{cartId}/products/{productId}

* [GET] /carts/{cartId}
