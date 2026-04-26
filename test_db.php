<?php
// Script de teste do banco de dados
$dbDir = __DIR__ . '/data';
$dbFile = $dbDir . '/portal.db';

echo "<h2>Teste de Conexão SQLite</h2>";
echo "<p><strong>Caminho do diretório:</strong> " . $dbDir . "</p>";
echo "<p><strong>Caminho do arquivo:</strong> " . $dbFile . "</p>";

// Verifica se o diretório existe
if (!is_dir($dbDir)) {
    echo "<p style='color:red'>❌ ERRO: Diretório não existe!</p>";
    echo "<p>Tentando criar...</p>";
    if (mkdir($dbDir, 0777, true)) {
        echo "<p style='color:green'>✅ Diretório criado com sucesso!</p>";
    } else {
        echo "<p style='color:red'>❌ Falha ao criar diretório. Verifique as permissões.</p>";
        exit;
    }
} else {
    echo "<p style='color:green'>✅ Diretório existe.</p>";
}

// Verifica permissão de escrita no diretório
if (is_writable($dbDir)) {
    echo "<p style='color:green'>✅ Diretório tem permissão de escrita.</p>";
} else {
    echo "<p style='color:red'>❌ Diretório NÃO tem permissão de escrita!</p>";
}

// Verifica se o arquivo existe
if (!file_exists($dbFile)) {
    echo "<p>Arquivo não existe. Tentando criar...</p>";
    if (touch($dbFile)) {
        chmod($dbFile, 0666);
        echo "<p style='color:green'>✅ Arquivo criado com sucesso!</p>";
    } else {
        echo "<p style='color:red'>❌ Falha ao criar arquivo!</p>";
        exit;
    }
} else {
    echo "<p style='color:green'>✅ Arquivo existe.</p>";
}

// Verifica permissão de escrita no arquivo
if (is_writable($dbFile)) {
    echo "<p style='color:green'>✅ Arquivo tem permissão de escrita.</p>";
} else {
    echo "<p style='color:red'>❌ Arquivo NÃO tem permissão de escrita!</p>";
}

// Tenta conectar
echo "<hr><p>Tentando conectar ao banco de dados...</p>";
try {
    $pdo = new PDO("sqlite:" . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Testa criação de tabela
    $pdo->exec("CREATE TABLE IF NOT EXISTS test_table (id INTEGER PRIMARY KEY, name TEXT)");
    $pdo->exec("INSERT INTO test_table (name) VALUES ('teste')");
    $result = $pdo->query("SELECT * FROM test_table")->fetchAll();
    
    echo "<p style='color:green'><strong>✅ SUCESSO! Conexão estabelecida e testes passaram!</strong></p>";
    echo "<p>Dados de teste inseridos: " . count($result) . " registro(s)</p>";
    
    // Limpa tabela de teste
    $pdo->exec("DROP TABLE test_table");
    
} catch (PDOException $e) {
    echo "<p style='color:red'><strong>❌ ERRO NA CONEXÃO:</strong></p>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Código do erro:</strong> " . $e->getCode() . "</p>";
}

echo "<hr><p><em>Permissões atuais:</em></p>";
echo "<pre>" . shell_exec("ls -la " . escapeshellarg($dbDir)) . "</pre>";
?>
