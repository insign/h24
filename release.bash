#!/bin/bash

set -e  # Opcional: interrompe o script em caso de erro
# set -x  # Opcional: exibe cada comando antes de executá-lo (útil para debug)

echo "Incrementando versão..."

VERSION_FILE="style.scss"
THEME_NAME="h24"

# Extrai a versão atual do arquivo style.scss
current_version=$(grep -E '^Version:\s*[0-9]+\.[0-9]+\.[0-9]+' "$VERSION_FILE" | head -1 | awk '{print $2}')
echo "Versão atual: $current_version"

# Separa MAJOR, MINOR e PATCH
IFS='.' read -r major minor patch <<< "$current_version"

# Incrementa o MINOR e reseta o PATCH
minor=$((minor + 1))
patch=0

# Nova versão
new_version="$major.$minor.$patch"
echo "Nova versão: $new_version"

# Atualiza o arquivo com a nova versão
sed -i '' "s/^Version:.*/Version:        $new_version/" "$VERSION_FILE"

# Adiciona as mudanças ao stage
git add .

# Commit com a mensagem da nova versão
git commit -m "Versão $new_version"

# Envia para o repositório remoto
git push origin main

# Cria uma tag com a nova versão
git tag -a "$new_version" -m "Versão $new_version"

# Envia a tag para o repositório remoto
git push origin "$new_version"

# Cria uma release no GitHub usando o gh cli, sem anexar um arquivo
gh release create "$new_version" --title "Versão $new_version" --notes "Descrição da versão $new_version"

echo "Processo concluído com sucesso!"
