# Test Charlie Solutions

## fonctionnement
### configuration
- créer le fichier .env à partir de .env.dist et mettre les infos concernant la base de données
### migrations
- `php artisan migrate`

### lancer serveur laravel
- `php artisan serve`

### lancer application vue
- dans le dossier vue-app
- `npm run dev`
- ouvrir localhost:3000 dans un navigateur

## script traitement données
- dans scripts/charliescript.php
- changer les infos de base de données (username, password)
- le lancer en faisant `php charliescript.php`

## temps passé et difficultés rencontrées

environ 7-8 heures :

- 2-3 heures sur le script :
    - 1 heure sur écriture code
    - 1-2 heures sur recherches vocabulaire (RSSI, capteurs, BLE, etc...) et compréhension des données
- ~30 minutes sur laravel
- 4-5 heures sur application vue :
    - 2 heures sur création application et connexion au serveur laravel
    - 1 heure sur recherche et utilisation de vue-leaflet pour la carte
    - 2 heures de tentative de correction d'un bug avec leaflet

- A l'heure actuelle j'ai toujours le bug qui fait que leaflet ne charge pas, ce qui m'attriste beaucoup car j'avais réussi à l'utiliser et afficher des marqueurs dans un rayon autour du point central [50.6337848 ; 3.0217842]

