<?php
// Script de teste do banco de dados
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Teste de Conexão SQLite</h2>";

$base_dir = __DIR__;
$data_dir = $base_dir . '/data';
$db_file = $data_dir . '/portal.db';

echo "<p><strong>Diretório Base:</strong> $base_dir</p>";
echo "<p><strong>Diretório Data:</strong> $data_dir</p>";
echo "<p><strong>Arquivo DB:</strong> $db_file</p>";

// Verificar diretório
if (!file_exists($data_dir)) {
    echo "<p style='color:red'>❌ Diretório data não existe!</p>";
    if (mkdir($data_dir, 0777, true)) {
        echo "<p style='color:green'>✅ Diretório criado com sucesso!</p>";
    } else {
        echo "<p style='color:red'>❌ Falha ao criar diretório!</p>";
    }
} else {
    echo "<p style='color:green'>✅ Diretório existe</p>";
    echo "<p><strong>Permissões do diretório:</strong> " . substr(sprintf('%o', fileperms($data_dir)), -4) . "</p>";
}

// Verificar arquivo
if (!file_exists($db_file)) {
    echo "<p style='color:orange'>⚠️ Arquivo DB não existe, criando...</p>";
    if (touch($db_file)) {
        echo "<p style='color:green'>✅ Arquivo criado!</p>";
    } else {
        echo "<p style='color:red'>❌ Falha ao criar arquivo!</p>";
    }
} else {
    echo "<p style='color:green'>✅ Arquivo DB existe</p>";
    echo "<p><strong>Permissões do arquivo:</strong> " . substr(sprintf('%o', fileperms($db_file)), -4) . "</p>";
}

// Tentar definir permissões
@chmod($data_dir, 0777);
@chmod($db_file, 0666);

echo "<p><strong>Permissões após chmod:</strong></p>";
echo "<ul>";
echo "<li>Diretório: " . substr(sprintf('%o', fileperms($data_dir)), -4) . "</li>";
echo "<li>Arquivo: " . substr(sprintf('%o', fileperms($db_file)), -4) . "</li>";
echo "</ul>";

// Testar conexão
echo "<h3>Testando Conexão...</h3>";
try {
    $db = new PDO("sqlite:" . $db_file);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>✅ Conexão bem-sucedida!</p>";
    
    // Criar tabela de teste
    $db->exec("CREATE TABLE IF NOT EXISTS test (id INTEGER PRIMARY KEY, msg TEXT)");
    echo "<p style='color:green'>✅ Tabela criada!</p>";
    
    // Inserir dado de teste
    $db->exec("INSERT INTO test (msg) VALUES ('Teste OK')");
    echo "<p style='color:green'>✅ Dados inseridos!</p>";
    
    // Ler dado de teste
    $result = $db->query("SELECT * FROM test")->fetchAll();
    echo "<p style='color:green'>✅ Dados lidos: " . count($result) . " registro(s)</p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Erro na conexão: " . $e->getMessage() . "</p>";
    echo "<p><strong>Código do erro:</strong> " . $e->getCode() . "</p>";
}

echo "<hr>";
echo "<p><a href='index.php'>Voltar ao Portal</a></p>";
