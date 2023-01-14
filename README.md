
1. Install Php

```
brew install php
```

2. Install composer

```
 curl -sS https://getcomposer.org/installer | php
```

```
 sudo mv composer.phar /usr/local/bin/composer
```

3. Install Symfony Cli

```
brew install symfony-cli/tap/symfony-cli
```

4. Create project

```
symfony new Alphalyr
```

5. config composer

```
composer require symfony/maker-bundle --dev
```

6. Add doctrine maker

```
composer require doctrine maker
```

7. Config .env file

change .env DATABASE_URL with your database url

8. symfony server:start


9. Test apis using postman localhost:8000/{your_endpoint_here}