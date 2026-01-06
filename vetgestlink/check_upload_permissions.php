#!/usr/bin/env php
<?php
/**
 * Script para verificar permissões de upload no servidor
 * Execute: php check_upload_permissions.php
 */

echo "=== Verificação de Permissões de Upload ===\n\n";

$uploadDir = __DIR__ . '/frontend/web/uploads';
$usersDir = $uploadDir . '/users';

echo "1. Verificando diretório base de uploads:\n";
echo "   Caminho: $uploadDir\n";
echo "   Existe: " . (is_dir($uploadDir) ? 'SIM' : 'NÃO') . "\n";
echo "   Legível: " . (is_readable($uploadDir) ? 'SIM' : 'NÃO') . "\n";
echo "   Gravável: " . (is_writable($uploadDir) ? 'SIM' : 'NÃO') . "\n";
if (is_dir($uploadDir)) {
    echo "   Permissões: " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "\n";
    $stat = stat($uploadDir);
    if ($stat && function_exists('posix_getpwuid')) {
        $owner = posix_getpwuid($stat['uid']);
        echo "   Proprietário: " . ($owner ? $owner['name'] : $stat['uid']) . "\n";
    }
}
echo "\n";

echo "2. Verificando subdiretório users:\n";
echo "   Caminho: $usersDir\n";
echo "   Existe: " . (is_dir($usersDir) ? 'SIM' : 'NÃO') . "\n";
echo "   Legível: " . (is_readable($usersDir) ? 'SIM' : 'NÃO') . "\n";
echo "   Gravável: " . (is_writable($usersDir) ? 'SIM' : 'NÃO') . "\n";
if (is_dir($usersDir)) {
    echo "   Permissões: " . substr(sprintf('%o', fileperms($usersDir)), -4) . "\n";
    $stat = stat($usersDir);
    if ($stat && function_exists('posix_getpwuid')) {
        $owner = posix_getpwuid($stat['uid']);
        echo "   Proprietário: " . ($owner ? $owner['name'] : $stat['uid']) . "\n";
    }
}
echo "\n";

echo "3. Testando criação de arquivo:\n";
$testFile = $usersDir . '/test_' . time() . '.txt';
$testContent = "Teste de permissão em " . date('Y-m-d H:i:s');

if (file_put_contents($testFile, $testContent)) {
    echo "   ✓ Arquivo de teste criado com sucesso: $testFile\n";
    if (unlink($testFile)) {
        echo "   ✓ Arquivo de teste removido com sucesso\n";
    } else {
        echo "   ✗ Falha ao remover arquivo de teste\n";
    }
} else {
    echo "   ✗ FALHA ao criar arquivo de teste\n";
    echo "   Isso indica um problema de permissões!\n";
}
echo "\n";

echo "4. Usuário do processo PHP:\n";
if (function_exists('posix_geteuid') && function_exists('posix_getpwuid')) {
    $processUser = posix_getpwuid(posix_geteuid());
    echo "   Usuário: " . $processUser['name'] . "\n";
    echo "   UID: " . $processUser['uid'] . "\n";
    echo "   GID: " . $processUser['gid'] . "\n";
} else {
    echo "   Funções POSIX não disponíveis\n";
}
echo "\n";

echo "5. Configuração PHP para upload:\n";
echo "   upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "   post_max_size: " . ini_get('post_max_size') . "\n";
echo "   file_uploads: " . (ini_get('file_uploads') ? 'Habilitado' : 'Desabilitado') . "\n";
echo "   upload_tmp_dir: " . (ini_get('upload_tmp_dir') ?: 'padrão do sistema') . "\n";
echo "\n";

echo "=== Verificação completa ===\n";
