Simple private API for auth
-----------------------
## Documentation

[Xdebug configuration](https://github.com/olevv/gatekeeper/blob/master/docs/GetStarted/Xdebug.md)

## Implementations

- [x] Environment in Docker
- [x] Command Bus, Query Bus
- [x] Read Model
- [x] Rest API
- [x] Swagger API Doc

## Stack

- PHP 7.3
- Postgres 11

## Use Cases

#### Auth
- [x] Get access token

#### User
- [x] Sign Up
- [x] Get user info
- [x] Block
- [x] Unblock
- [x] Change password
- [x] Change email
- [x] Get users

## Project Setup

Up environment:

`make start`

Execute tests:

`make phpunit`

Static code analysis:

`make style`

Code style fixer:

`make cs`

Code style checker:

`make cs-check`

Add to /etc/hosts:

`127.0.0.1	gatekeeper.local`

## API Doc

See docs on:

`http://gatekeeper.local/api/doc`
