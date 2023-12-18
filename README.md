# api-sae

## Setup

- Ajouter les clés JWT dans project > config > jwt
- Ajouter le .env.local dans project
- Lancer `composer install`
- Lancer le container `docker compose up -d`
- Se mettre dans le bash du container `docker exec -it www_docker_symfony bash`
- `cd project`
- `php bin/console doctrine:database:create`
- Ouvrir phpmyadmin au localhost:8080 (pas de mdp et username root)
- Importer la bdd (fichier harmonize.sql)
- L'api est dispo à l'adresse localhost:8741/api
