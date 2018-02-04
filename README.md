[![Build Status](https://travis-ci.org/rcnchris/mycore.svg?branch=master)](https://travis-ci.org/rcnchris/mycore)
[![Coveralls github](https://img.shields.io/coveralls/github/rcnchris/mycore.svg)](https://github.com/rcnchris/mycore)
[![GitHub tag](https://img.shields.io/github/tag/rcnchris/mycore.svg)]()
[![GitHub Release Date](https://img.shields.io/github/release-date/rcnchris/mycore.svg)]()
[![Packagist License](https://img.shields.io/packagist/l/rcnchris/core.svg)](https://img.shields.io/packagist/l/rcnchris/core.svg)
[![Packagist Downloads](https://img.shields.io/packagist/dt/rcnchris/core.svg)](https://img.shields.io/packagist/dt/rcnchris/core.svg)

<img src="public/img/icon_readme.png" align="right" />

# My Core
> Mes librairies PHP.

<div class="alert alert-info">
    L'application s'appuie sur le framework <strong>Slim</strong> dans sa version 3.9.
    <ul>
        <li>La configuration est gérée par le <strong>conteneur de dépendances</strong> de Slim à partir de deux fichiers <code>config.php</code> et <code>dependances.php</code></li>
        <li>Les routes sont stockées dans un fichier <code>routes.php</code></li>
        <li>Structure <strong>MVC</strong></li>
        <li>Moteur de rendu <strong>Twig</strong>, bien qu'il soit possible de rendre des vues
            <strong>PHP</strong></li>
    </ul>
</div>

-------

## Installation
````
# Pour l'utiliser dans son projet
composer require rcnchris/core
# ou créer un projet vide
composer create-project -s rcnchris/core new-project
````

## Packages

### Tools
Package qui regroupe les classes utilisées de manière autonomes un peu partout.

#### Cmd
Classe statique instanciable qui permet d'exécuter des commandes *shell*.
````
$ls = Cmd::exec("cd $path && ls");
````

#### Collection
Facilite la manipulation d'un tableau via un objet.
````
$col = new Collection('ola,ole,oli', "Liste de valeurs dans une chaîne avec séparateur");
````

#### Common
Classe statique instanciable qui fournit des méthodes diverses.
````
$m = Common::getMemoryUse();
````

#### Composer
Facilite la manipulation d'un fichier composer.json
````
$composer = new Composer('/path/to/file/composer.json');
$composer->get('name');
````

#### Folder
Facilite la manipulation de fichiers et dossiers.
````
$folder = new Folder($path);
$size = $folder->size();
````

#### Image
Facilite la manipulation des images.
````
$img = new Image('path/to/file');
$src = $img->getEncode();
````

#### Text
Facilite la manipulation des chaînes de caractères.
````
$slug = Text::slug('Le slug qui va bien !');
````

#### RandomItems
Obtenir des données aléatoires.
````
$item = RandomItems::users();
$items = RandomItems::users(3);
$items = RandomItems::users(3, 'fr_FR');
````

-------

## PDF
Gestion des documents PDF.
````
$pdf = new MyFPDF();

// Sauvegarder le fichier sur le serveur
$pdf->toFile(/path/to/file/filename);

// Télécharger via le navigateur
$pdf->toDownload(ResponseInterface $response, 'filename');

// Visualiser dans le navigateur
$pdf->toView(ResponseInterface $response, 'filename');
````

-------

## Apis
Package qui permet d'utiliser n'importe qu'elle API facilement et quelques APIs dédiées.
#### APITrait
Comportement communs à toutes les APIs sur la base de `curl`

#### CurlResponse
Représente une réponse de la commande <code>curl_exec()</code>

#### OneAPI
Utiliser n'importe quelle API à partir de son URL.
````
$api = new OneAPI('https://randomuser.me/api');
$users = $api->r(['results' => 3])->toArray('results');
````

#### AlloCiné
Obtenir des informations de l'API.
````
$api = new AlloCine();
$search = $api->search('Le Parrain');
````

#### Synology
Utiliser les API d'un NAS Synology.
````
$api = new AbstractSynology($config);
$genres = $api
    ->getPackage('AudioStation')
    ->get('Genre');

$movies = $api
    ->getPackage('VideoStation')
    ->get('Movie', 'list', ['limit' => 20, 'offset' => 0], 'movies');
````

-------

## ORM
Abstraction des bases de données qui s'appuient sur `PDO`.
````
// Obtenir une connexion PDO à MySQL
$pdo = DbFactory::get('localhost', 3306, 'al', 'secret', 'home', 'mysql');

// Obtenir une connexion PDO à SQL Server
$pdo = DbFactory::get('MYSERVER\SQLEXPRESS', 1433, 'al', 'secret', 'home', 'sqlsrv');

// Gérer plusieurs sources de données depuis un tableau et s'y connecter
$manager = new SourcesManager($sources);
$pdo = $manager->connect('home');

// Effectuer une requête
$query = (new Query($pdo))
            ->from('posts')
            ->where('category_id = 3')
            ->order('title');
$result = $query->all()->toArray();

// Utiliser un modèle
$posts = new PostsModel($pdo);
$items = $posts->findAll()->all()->toArray();
````

-------

## Twig
Ajoute des extensions à Twig.

- Debug
- Fichier et dossier
- Texte
- Dates
- Tableaux
- HTML
- Formulaire
- Icônes
- Bootstrap 4

-------

## Todo <progress></progress>
- ORM
 - Méthode find avec jointure, défaillante
 - Relations d'un model
- Synology
 - Utilisation des cookies
- Twig
 - ArrayExtension : Améliorer toHml pour pouvoir déterminer le sens du tableau

-------