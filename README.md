# api-sae

## Setup

- Ajouter les clés JWT dans project > config > jwt (créer un dossier jwt)
- Ajouter le .env.local dans project
- Lancer le container `docker compose up -d`
- Se mettre dans le bash du container `docker exec -it www_docker_symfony bash`
- `cd project`
- Lancer `composer install`
- `php bin/console doctrine:database:create`
- Ouvrir phpmyadmin au localhost:8080 (pas de mdp et username root)
- Importer la bdd (fichier harmonize.sql)
- L'api est dispo à l'adresse localhost:8741/api

## Update la BDD

- Se mettre dans le bash du container `docker exec -it www_docker_symfony bash`
- `cd project`
- `php bin/console make:migration`
- `php bin/console doctrine:migration:migrate`
