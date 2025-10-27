Write-Host "====================================" -ForegroundColor Cyan
Write-Host "VetGestLink - Instalacao Automatica" -ForegroundColor Cyan
Write-Host "====================================" -ForegroundColor Cyan
Write-Host ""

# Verifica PHP
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "[ERRO] PHP nao encontrado!" -ForegroundColor Red
    exit 1
}

# Verifica Composer
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "[ERRO] Composer nao encontrado!" -ForegroundColor Red
    exit 1
}

Write-Host "[1/7] Verificando requisitos do PHP..." -ForegroundColor Green
php requirements.php

Write-Host "`n[2/7] Instalando dependencias do Composer..." -ForegroundColor Green
composer install

Write-Host "`n[3/7] Instalando dependencias do NPM..." -ForegroundColor Green
if (Get-Command npm -ErrorAction SilentlyContinue) {
    npm install
} else {
    Write-Host "[AVISO] NPM nao encontrado." -ForegroundColor Yellow
}

Write-Host "`n[4/7] Inicializando ambiente..." -ForegroundColor Green
.\init.bat

Write-Host "`n[5/7] Configurando nome da base de dados..." -ForegroundColor Green
$configFile = "common/config/main-local.php"
if (Test-Path $configFile) {
    $content = Get-Content $configFile -Raw
    $content = $content -replace "'dbname'\s*=>\s*'[^']*'", "'dbname' => 'vetgestdb'"
    $content = $content -replace '"dbname"\s*=>\s*"[^"]*"', '"dbname" => "vetgestdb"'
    $content | Set-Content $configFile -Encoding UTF8 -NoNewline
    Write-Host "Nome da base de dados definido para 'vetgestdb'" -ForegroundColor Green
} else {
    Write-Host "Aviso: ficheiro $configFile nao encontrado" -ForegroundColor Yellow
}

Write-Host "`n[6/7] Executando migracoes..." -ForegroundColor Green
php yii migrate --interactive=0

Write-Host "`n[7/7] Configuracao final..." -ForegroundColor Green
New-Item -ItemType Directory -Force -Path "backend\runtime\cache" | Out-Null
New-Item -ItemType Directory -Force -Path "backend\runtime\logs" | Out-Null
New-Item -ItemType Directory -Force -Path "frontend\runtime\cache" | Out-Null
New-Item -ItemType Directory -Force -Path "frontend\runtime\logs" | Out-Null

Write-Host "`n====================================" -ForegroundColor Green
Write-Host "Instalacao concluida com sucesso!" -ForegroundColor Green
Write-Host "====================================" -ForegroundColor Green
Write-Host "`nBase de dados configurada: vetgestdb" -ForegroundColor Cyan
