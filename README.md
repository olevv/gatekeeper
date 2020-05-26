Simple private API
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

- PHP 7.4
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

Init environment:

`make init`

Execute tests:

`make tests`

Static code analysis:

`make style`

Code style fixer:

`make cs`

Code style checker:

`make cs-check`

Enter in php container:

`make s=php-fpm sh`

Show all commands:

`make help`

Add to /etc/hosts:

`127.0.0.1	gatekeeper.local`

## API Doc

See docs on:

`http://gatekeeper.local/api/doc`
