<?php
// Simples teste sem framework
$password = 'password_0';
$hash = '$2y$13$LrrWUPsxSGgxxpoLefh4dOEDnuvo3LqseyylSZtudqZPOnj3xjZsq';

echo "Testing PHP password_verify:\n";
if (password_verify($password, $hash)) {
    echo "✓ Password VALID\n";
} else {
    echo "✗ Password INVALID\n";
}

// Generate new hash using Yii2 cost
$options = ['cost' => 13];
$newHash = password_hash($password, PASSWORD_DEFAULT, $options);
echo "\nNew hash: $newHash\n";

if (password_verify($password, $newHash)) {
    echo "✓ New hash VALID\n";
} else {
    echo "✗ New hash INVALID\n";
}
