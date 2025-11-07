# Caminhos relativos, a partir da raiz do projeto
$symlink = "backend\web\uploads"
$target  = "uploads"

# Apaga o symlink anterior se existir
if (Test-Path $symlink) { Remove-Item $symlink }

# Cria a junction (relativo à raiz)
New-Item -ItemType Junction -Path $symlink -Target $target
