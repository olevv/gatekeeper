# Using Xdebug

### Requirements: 

- `docker` 
- `docker-compose`

## Setup

- Start the project using `make start`.
```bash
➜ docker-compose ps        
       Name                      Command               State                                             Ports                                           
---------------------------------------------------------------------------------------------------------------------------------------------------------
gatekeeper_nginx_1      nginx -g daemon off;            Up      0.0.0.0:8080->80/tcp
gatekeeper_php-fpm_1    docker-php-entrypoint php-fpm   Up      9000/tcp
gatekeeper_postgres_1   docker-entrypoint.sh postgres   Up      0.0.0.0:54321->5432/tcp
```
We'll use this to connects to the container as a remote interpreter in our favourite IDE. In PHPStorm, i.e., go to `Languajes & Frameworks > PHP` and configure the connection to have something like:
- Change Debug port in `Debug`.
![debugger](https://i.imgur.com/Kw1RdhM.png)
- Next you'll need to configure server in `Servers`. Configure mappings `File/Directory` your project, `Absolute path on the server` on `/app`:
![debugger](https://i.imgur.com/HhfPBqy.png)
