.PHONY: help install ideperm webperm code gendoc doc pretest runtest posttest test runtestapi runtestfolder server proxy watch clear

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

folderTest?=tests

help: ## Aide de ce fichier
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-15s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

vendor: composer.json ## Génération du dossier vendor
	composer selfupdate
	composer install -o --no-suggest

composer.lock: composer.json
	composer update -o --no-suggest

install: vendor composer.lock ## Installation et/ou mise à jour des librairies

ideperm: ## Permissions de développement
	@echo -e '$(colorObj)Permissions de développement$(colorOff)'
	@sudo chown -R $(userConsole):$(userConsole) public
	@sudo chown -R $(userConsole):$(userConsole) tests

webperm: ## Permissions de production
	@echo -e '$(colorObj)Permissions de production$(colorOff)'
	@sudo chown -R $(userApache):$(userApache) public

code: ideperm ## Vérification et correction de la syntaxe
	@echo -e '$(colorObj)Vérification et correction de la syntaxe$(colorOff)'
	@echo -e '$(colorCom)Corrections syntaxiques$(colorOff)'
	@./vendor/bin/phpcbf
	@echo -e '$(colorCom)Tests syntaxiques$(colorOff)'
	@./vendor/bin/phpcs

gendoc: ideperm ## Génération de la documentation
	@echo -e '$(colorObj)Génération de la documentation$(colorOff)'
	@echo -e '$(colorCom)Corrections syntaxiques$(colorOff)'
	@./vendor/bin/phpcbf
	@../../devtools/phpdoc/vendor/bin/phpdoc -d src -t public/doc --template='responsive'

doc: gendoc webperm ## Génération de la documentation

pretest: ideperm install clear ## Préparation des tests
	@wkhtmltopdf --orientation Landscape public/coverage/index.html public/pdf/Coverage_$(shell date +%Y%m%d%H%M%S)_before_tests.pdf

runtest: ## Tests unitaires
	@echo -e '$(colorObj)Tests unitaires$(colorOff)'	
	@./vendor/bin/phpunit --stop-on-failure --coverage-html public/coverage

posttest: clear ## Finalisation des tests
	@wkhtmltopdf --orientation Landscape public/coverage/index.html public/pdf/Coverage_$(shell date +%Y%m%d%H%M%S)_after_tests.pdf

test: pretest runtest posttest webperm

runtestfolder: ideperm ## Tester un répertoire particulier
	@echo -e '$(colorObj)Package Apis$(colorOff)'	
	@./vendor/bin/phpunit $(folderTest) --stop-on-failure --coverage-text
	
server: install ## Lance un serveur de développement
	@echo -e '$(colorObj)Serveur $(serverName):$(serverPort) actif$(colorOff)'
	@php -S $(serverName):$(serverPort) -t $(serverFolder)/ -d display_errors=1

proxy: ## Permet de rafraîchir automatiquement la page du serveur de déveeloppement
	browser-sync start --port 3000 --proxy $(serverName):$(serverPort) --files 'src/**/*.php' --files 'app/**/*.php' --files 'app/**/*.phtml'

watch: server proxy

clear: ## Vider les fichiers temporaires
	@sudo rm -rf host*
	@sudo rm -rf tmp/*