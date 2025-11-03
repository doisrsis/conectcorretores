@echo off
REM Script para commit das melhorias no cadastro de imoveis
REM Autor: Rafael Dias - doisr.com.br
REM Data: 03/11/2025

echo ========================================
echo Commit: Melhorias no Cadastro de Imoveis
echo ========================================
echo.

REM Adicionar arquivos modificados
git add application/views/imoveis/form.php
git add application/controllers/Imoveis.php
git add assets/js/imoveis-form.js

REM Adicionar migrations
git add database/migrations/migration_20251103_add_link_imovel.sql

REM Adicionar documentacao
git add docs/desenvolvimento/MELHORIAS-CADASTRO-IMOVEIS.md
git add docs/bugs/BUG-SELECT-UF-E-MASCARA-PRECO.md
git add docs/README.md

echo.
echo Arquivos adicionados ao stage!
echo.

REM Mostrar status
git status

echo.
echo ========================================
echo Deseja fazer o commit? (S/N)
echo ========================================
set /p confirma=

if /i "%confirma%"=="S" (
    git commit -m "feat: Melhorias no cadastro de imoveis

- Remover campos de contato do formulario (telefone, whatsapp, link)
- Adicionar campo 'Link do Imovel' (opcional)
- Remover campo 'Valor m2' calculado do formulario
- Corrigir bug: Select UF nao listava estados
- Corrigir bug: Mascaras de campos removidos quebravam JavaScript
- Otimizar mascara de preco com centavos no blur
- Criar migration para adicionar coluna link_imovel
- Documentacao completa das melhorias e bugs resolvidos

Arquivos modificados:
- application/views/imoveis/form.php
- application/controllers/Imoveis.php
- assets/js/imoveis-form.js

Arquivos criados:
- database/migrations/migration_20251103_add_link_imovel.sql
- docs/desenvolvimento/MELHORIAS-CADASTRO-IMOVEIS.md
- docs/bugs/BUG-SELECT-UF-E-MASCARA-PRECO.md

Autor: Rafael Dias - doisr.com.br
Data: 03/11/2025"

    echo.
    echo ========================================
    echo Commit realizado com sucesso!
    echo ========================================
    echo.
    
    echo Deseja fazer push para o repositorio remoto? (S/N)
    set /p push=
    
    if /i "%push%"=="S" (
        git push
        echo.
        echo ========================================
        echo Push realizado com sucesso!
        echo ========================================
    ) else (
        echo.
        echo Push cancelado. Execute 'git push' manualmente quando desejar.
    )
) else (
    echo.
    echo Commit cancelado.
    echo Execute este script novamente quando desejar fazer o commit.
)

echo.
pause
