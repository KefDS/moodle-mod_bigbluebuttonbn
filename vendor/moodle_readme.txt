# Instale composer
sudo curl -sS https://getcomposer.org/installer | php

# Cree un archivo composer.json con lo siguiente:

{
  "config": {
        "vendor-dir": "dependencies"
    },
    "require": {
      "rackspace/php-opencloud": "*"
    }
}


# Actualice las dependencias con composer 

php composer.phar install 
