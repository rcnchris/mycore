[![Build Status](https://travis-ci.org/rcnchris/mycore.svg?branch=master)](https://travis-ci.org/rcnchris/mycore)
[![Coveralls github](https://img.shields.io/coveralls/github/rcnchris/mycore.svg)](https://github.com/rcnchris/mycore)
[![Packagist License](https://img.shields.io/packagist/l/rcnchris/core.svg)](https://img.shields.io/packagist/l/rcnchris/core.svg)
[![Packagist Version](https://img.shields.io/packagist/v/rcnchris/core.svg)](https://img.shields.io/packagist/v/rcnchris/core.svg)
[![Packagist Downloads](https://img.shields.io/packagist/dt/rcnchris/core.svg)](https://img.shields.io/packagist/dt/rcnchris/core.svg)

<img src="public/img/icon_readme.png" align="right" />

# My Core
> Mes librairies PHP.

-------

## Package Tools
Package qui regroupe les classes utilisées de manière autonomes un peu partout.

### Cmd
Classe statique qui permet d'exécuter des commandes *shell*.
````
$ls = Cmd::exec("cd $path && ls");
````

### Collection
Facilite la manipulation d'un tableau via un objet.
````
$col = new Collection('ola,ole,oli', "Liste de valeurs dans une chaîne avec séparateur");
````

### Common
Classe statique qui fournit des méthodes diverses.
````
$m = Common::getMemoryUse();
````

### Composer
Facilite la lecture d'un fichier composer.json
````
$composer = new Composer($path);
$libs = $composer->show();
````

-------
