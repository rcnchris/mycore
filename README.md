[![Travis](https://img.shields.io/travis/rcnchris/mycore.svg)](https://travis-ci.org/rcnchris/mycore)
[![GitHub tag](https://img.shields.io/github/tag/rcnchris/mycore.svg)]()
[![PHP from Packagist](https://img.shields.io/packagist/php-v/rcnchris/core.svg)]()
[![GitHub Release Date](https://img.shields.io/github/release-date/rcnchris/mycore.svg)]()
[![Packagist License](https://img.shields.io/packagist/l/rcnchris/core.svg)](https://img.shields.io/packagist/l/rcnchris/core.svg)
[![Packagist Downloads](https://img.shields.io/packagist/dt/rcnchris/core.svg)](https://img.shields.io/packagist/dt/rcnchris/core.svg)

<img src="public/img/icon_readme.png" align="right" />

# My Core
> Librairies PHP.

-------

## Installation

````
# Pour l'utiliser dans son projet
composer require rcnchris/core
# ou créer un projet vide
composer create-project -s rcnchris/core new-project
````

-------

## Packages

### Tools
Package qui regroupe les classes utilisées de manière autonomes un peu partout. Outils de gestion de base, tels que l'exécution de commandes shell, collections de données, accès aux fichiers et dossiers, debug...

#### Cmd
Exécution de commandes *shell* et traitement du retour.
````
$ls = Cmd::exec("cd $path && ls");
````

#### Environnement
Fournit des méthodes d'interrogation de l'environnement.
````
$e = new Environnement();
$e->getPhpVersion();
````

#### Html
Fournit des méthodes de génération de balises HTML
````
Html::makeLink('Google', 'http://google.fr', ['class' => 'btn btn-primary');
````

#### Collection
Facilite la manipulation des listes de données, tableaux multi-dimensionels...
````
$col = new Collection('ola,ole,oli', "Liste de valeurs dans une chaîne avec séparateur");
````

#### Items
Une autre classe de gestion des listes qui facilite la manipulation de tableaux multi-dimensionels...
````
$col = new Items('ola,ole,oli');
````

#### Colors
Gestion d'une palette de couleurs pré-définies ou définition d'une personnelle.
````
$colors = new Colors();
$colors->get('blue');
````

#### Common
Méthodes communes diverses.
````
$m = Common::getMemoryUse();
````

#### Composer
Facilite la lecture d'un fichier composer.json
````
$composer = new Composer('/path/to/file/composer.json');
$composer->get('name');
````

#### Folder
Facilite la lecture de fichiers et dossiers.
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

### Session
Ce package regroupe les classes de gestion des sessions et cookies.

#### Session
Facilite la manipulation des sessions.
````
$session = new PHPSession();
$ip = $session->get('ip');
$cookies = $session->getCookies();
````

#### Cookies
Facilite la lecture des cookies.
````
$cookies = new PHPCookies();
$cookies->set('ip', '192.168.1.1')
````

-------

## Apis
Package qui permet d'utiliser n'importe qu'elle API facilement et quelques APIs dédiées.
### APITrait
Comportement communs à toutes les APIs sur la base de `curl`

### CurlResponse
Représente une réponse de la commande <code>curl_exec()</code>

### OneAPI
Utiliser n'importe quelle API à partir de son URL.
````
$api = new OneAPI('https://randomuser.me/api');
$users = $api->r(['results' => 3])->toArray('results');
````

### AlloCiné
Obtenir des informations de l'API.
````
$api = new AlloCine();
$search = $api->search('Le Parrain');
````

### Synology
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

### SourcesManager
Gestion de multiples sources de données hétérogènes (MySQL, SQLite, SQL Server...).
````
$manager = new SourcesManager($sources);
$pdo = $manager->connect($sourceName);
````

### DbFactory
Fournit une instance `PDO`.
````
$pdoSqliteMemory = DbFactory::get('memory', 0, '', '', '', 'sqlite');
$pdoSqliteFile = DbFactory::get('dbApp', 0, '', '', '', 'sqlite', '/path/to/file');
$pdoMysql = DbFactory::get('192.168.1.1', 3306, 'user', 'secret', 'home', 'mysql');
$pdoSqlsrv = DbFactory::get('MYSERVER\SQLEXPRESS', 1433, 'user', 'secret', 'home', 'sqlsrv');
````

### TableFactory
Fournit l'instance d'une table dans une base de données.
````
$phinxTable = TableFactory::get('users');
$cakeTable = TableFactory::get('users', ['orm' => 'cake']);
````

### Query
Effectuer une requête sur une connexion `PDO`.
````
$query = (new Query($pdo))->from('departements', 'dep');
$count = $query->count();
$items = $query->all()->toArray();

$query = (new Query($pdo))
            ->from('posts')
            ->where('category_id = 3')
            ->order('title');
$result = $query->all()->toArray();
````

### QueryResult
Représente le résultat d'une requête. Permet de gérer l'hydratation des objets qui contiennent les données issues de la bases de données.
````
$result = (new DepartementsModel($pdo))->makeQuery();
````

-------

## PDF
Génération de documents PDF.
````
$pdf = new AbstractPDF();

// Sauvegarder le fichier sur le serveur
$pdf->toFile(/path/to/file/filename);

// Télécharger via le navigateur
$pdf->toDownload(ResponseInterface $response, 'filename');

// Visualiser dans le navigateur
$pdf->toView(ResponseInterface $response, 'filename');
````

Les comportements sont gérés via des traits :

- Signets
- Couleurs
- Données
- Codes à barres
- Cercles et ellipses
- Grille graduée
- Icônes
- Fichier joint
- Layouts HTML
- PSR7
- Rotation de texte et d'image
- Rectangle arrondis
- Colonnes et lignes

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
- Messages flash

-------

## Middlewares PSR7
- Boot
- PoweredBy
- Session
- Cookies
- TrailingSlashes

-------

## Home en mode debug
<img src="public/img/home-debug.png" align="center" />

## Todo <progress></progress>
- Synology
 - Utilisation des cookies
- Twig
 - ArrayExtension : Améliorer toHml pour pouvoir déterminer le sens du tableau

-------
