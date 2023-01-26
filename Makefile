MODULE := $(notdir $(CURDIR))
VERSION := $(shell php -r 'echo parse_ini_file("config/module.ini")["version"];')
ZIP := ${MODULE}-${VERSION}.zip

dist: ${ZIP}

${ZIP}:
	rm -f $@
	git archive -o $@ --prefix=${MODULE}/ HEAD
	composer -q install --no-dev
	npm install --silent --omit dev
	mkdir -p ${MODULE} && ln -sr vendor ${MODULE}/vendor && ln -sr node_modules ${MODULE}/node_modules && zip -q -r $@ ${MODULE} && rm ${MODULE}/vendor && rm ${MODULE}/node_modules && rmdir ${MODULE}

.PHONY: ${ZIP}
