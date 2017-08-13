BOWER_FLAGS=
COMPOSER_FLAGS=--no-interaction

ifeq ($(APP_ENVIRONMENT),prod)
	BOWER_FLAGS+=--production
	COMPOSER_FLAGS+=--prefer-dist --no-dev --classmap-authoritative
endif

TASKS=
ifneq ("$(wildcard composer.json)","")
	TASKS+=vendor
endif

ifneq ("$(wildcard bower.json)","")
	TASKS+=assets
endif

all: $(TASKS)

vendor: composer.lock

composer.lock: composer.json
	composer install $(COMPOSER_FLAGS)

assets: src/Resources/public/lib
	bin/console cache:clear
	bin/console assets:install --symlink --relative web
	bin/console assetic:dump

src/Resources/public/lib: bower.json
	bower install $(BOWER_FLAGS)

distclean:
	rm -rf vendor composer.lock src/Resources/public/lib

.PHONY: all assets distclean
