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

PROJECT_DIR = $(shell pwd)
TEMPLATE_DOC = responsive

vendor: composer.json ## Mise à jour des librairies composer
	@composer selfupdate
	@echo -e '$(OK_COLOR)Mise à jour Composer$(NO_COLOR)'
	@composer update

install: ## Installation du projet
	@echo -e '$(OK_COLOR)Installation du projet$(NO_COLOR)'
	@rm -r vendor
	@rm -r composer.lock
	@composer install

code: ## Vérification et corection de la syntaxe
	@echo -e '$(OK_COLOR)Corrections syntaxiques$(NO_COLOR)'
	@./vendor/bin/phpcbf
	@echo -e '$(OK_COLOR)Tests syntaxiques$(NO_COLOR)'
	@./vendor/bin/phpcs

test: ## Lance les tests unitaires
	@echo -e '$(OK_COLOR)Tests unitaires$(NO_COLOR)'
	@./vendor/bin/phpunit --coverage-html public/coverage

doc: code ## Génération de la documentation
	@echo -e '$(OK_COLOR)Documentation des sources$(NO_COLOR)'
	@/home/dev/www/phpdoc/./vendor/bin/phpdoc -d $(PROJECT_DIR)/src -t $(PROJECT_DIR)/public/doc --template="responsive"

push: ## Commit et Push tous les changements
	@git status
	@git add .
	@git commit -a -m "MAJ Globale"
	@git push -u origin master
