# .SILENT:
.PHONY: help install ideperm webperm code test server proxy watch clear

.DEFAULT_GOAL = help

today=$(shell date +%Y-%m-%d)
todayAll=$(shell date +%Y%m%d%H%M%S)

colorCom=\033[0;34m
colorObj=\033[0;36m
colorOk=\033[0;32m
colorErr=\033[0;31m
colorWarn=\033[0;33m
colorOff=\033[m

userConsole=dev
userApache=www-data
userComposer=rcnchris
mail=rcn.chris@gmail.com
root=$(shell pwd)

templatePhpDoc=responsive

serverName=0.0.0.0
serverPort?=8000
serverFolder=public

help: ## Aide de ce fichier
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-15s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

vendor: composer.json ## Génération du dossier vendor
	composer install -o --no-suggest

composer.lock: composer.json
	composer update -o --no-suggest

install: vendor composer.lock ## Installation et/ou mise à jour des librairies

ideperm: ## Permissions de développement
	@echo -e '$(colorObj)Permissions de développement$(colorOff)'
	@sudo chown -R $(userConsole):$(userConsole) public

webperm: ## Permissions de production
	@echo -e '$(colorOk)Permissions de production$(colorOff)'
	@sudo chown -R $(userApache):$(userApache) public

code: ideperm ## Vérification et correction de la syntaxe
	@echo -e '$(colorObj)Vérification et correction de la syntaxe$(colorOff)'
	@echo -e '$(colorCom)Corrections syntaxiques$(colorOff)'
	@./vendor/bin/phpcbf
	@echo -e '$(colorCom)Tests syntaxiques$(colorOff)'
	@./vendor/bin/phpcs

test: ideperm install ## Tests unitaires
	@echo -e '$(colorObj)Tests unitaires$(colorOff)'
	@wkhtmltopdf --orientation Landscape public/coverage/index.html public/pdf/Coverage_$(shell date +%Y%m%d)_before_tests.pdf
	@./vendor/bin/phpunit --stop-on-failure --coverage-html public/coverage
	@wkhtmltopdf --orientation Landscape public/coverage/index.html public/pdf/Coverage_$(shell date +%Y%m%d)_after_tests.pdf
	@sudo rm -r host*
	
server: install ## Lance un serveur de développement
	@echo -e '$(colorObj)Lance un serveur sur le $(serverName):$(serverPort)$(colorOff)'
	@php -S $(serverName):$(serverPort) -t $(serverFolder)/ -d display_errors=1

proxy: ## Permet de rafraîchir automatiquement la page du serveur de déveeloppement
	browser-sync start --port 3000 --proxy $(serverName):$(serverPort) --files 'src/**/*.php' --files 'app/**/*.php' --files 'app/**/*.phtml'

watch: server proxy

clear: ## Vider les fichiers temporaires
	@sudo rm -r $(root)/host*