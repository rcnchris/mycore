<img src="public/img/icon_readme.png" align="right" />

# My Core {.display-3}
> Mes librairies PHP.

-------

## Tools
Package qui regroupe les classes utilisées de manière autonomes un peu partout.

### Collection
Manipulation d'un tableau via un objet.
````
$col = new Collection('ola,ole,oli', "Liste de valeurs dans une chaîne avec séparateur");
````

-------

# Annexes

## Makefile
````
.PHONY: test help

help: ## Aide de ce fichier
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-15s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.DEFAULT_GOAL = help

COM_COLOR   = \033[0;34m
OBJ_COLOR   = \033[0;36m
OK_COLOR    = \033[0;32m
ERROR_COLOR = \033[0;31m
WARN_COLOR  = \033[0;33m
NO_COLOR    = \033[m

PROJECT_DIR = /home/dev/www/_lab/mycore
TEMPLATE_DOC = responsive

test: ## Lance les tests unitaires
	@echo -e '$(OK_COLOR)Tests unitaires$(NO_COLOR)'
	@./vendor/bin/phpunit --coverage-html public/coverage
````

-------