VERSION_FILE=style.scss
THEME_NAME=h24

release:
	@echo "Incrementando versão..."

	@current_version=`grep -E '^Version:\s*[0-9]+\.[0-9]+\.[0-9]+' $(VERSION_FILE) | head -1 | awk '{print $$2}'` ; \
	echo "Versão atual: $$current_version" ; \
	major=`echo $$current_version | cut -d. -f1` ; \
	minor=`echo $$current_version | cut -d. -f2` ; \
	patch=`echo $$current_version | cut -d. -f3` ; \
	patch=$$(($$patch + 1)) ; \
	new_version="$$major.$$minor.$$patch" ; \
	echo "Nova versão: $$new_version" ; \
	sed -i '' "s/^Version:.*/Version:        $$new_version/" $(VERSION_FILE) ; \
	git add . ; \
	git commit -m "Versão $$new_version" ; \
	git push origin main ; \
	git tag -a $$new_version -m "Versão $$new_version" ; \
	git push origin $$new_version

	@echo "Processo concluído com sucesso!"
