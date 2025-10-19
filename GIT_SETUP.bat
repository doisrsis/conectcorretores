@echo off
echo ========================================
echo ConectCorretores v1.0.0
echo Setup Git e Push para GitHub
echo Autor: Rafael Dias - doisr.com.br
echo ========================================
echo.

echo [1/6] Inicializando repositorio Git...
git init
echo.

echo [2/6] Adicionando arquivos...
git add .
echo.

echo [3/6] Criando commit inicial...
git commit -m "feat: Versao inicial v1.0.0 - Sistema SaaS completo para gestao de imoveis"
echo.

echo [4/6] Criando branch main...
git branch -M main
echo.

echo [5/6] Adicionando repositorio remoto...
git remote add origin https://github.com/doisrsis/conectcorretores.git
echo.

echo [6/6] Enviando para GitHub...
git push -u origin main
echo.

echo ========================================
echo Concluido!
echo ========================================
echo.
echo Seu projeto foi enviado para:
echo https://github.com/doisrsis/conectcorretores
echo.
echo Proximos passos:
echo 1. Acesse o repositorio no GitHub
echo 2. Adicione uma descricao ao projeto
echo 3. Configure as GitHub Pages (se necessario)
echo 4. Convide colaboradores (se necessario)
echo.
pause
