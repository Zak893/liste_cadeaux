# liste_cadeaux

Cloner le dépôt depuis GitHub : git clone URL_DU_REPO

Installation des dépendances : composer install

Configuration de la base de données : 1-php bin/console doctrine:database:create
                                      2-php bin/console doctrine:migrations:migrate

Démarrer le serveur de développement : symfony server:start Or  php -S localhost:8080 public/index.php   
