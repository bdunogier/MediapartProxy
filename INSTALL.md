## Installation

### Clone the git repository
`git clone https://github.com/bdunogier/MediapartProxy.git`

So far, the master branch is the stable version.

### Give rights to the web server's user
`chown www-data:www-data MediapartProxy/ -R`

### Run composer
`cd MediapartProxy/`

If you don't have composer installed globally on your system:

`curl -s http://getcomposer.org/installer | php`

`php composer.phar install --prefer-dist`

Answer all the questions.

### Check your server's compatibility
`php app/check.php`

### Configure your webserver
Use [symfony's documentation](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html).

Make sur to create subdomains and aliases to match the proxy you will be using:
- mediapart.myproxy.com
- lmd.myproxy.com

