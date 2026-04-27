<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/Database.php';
require_once __DIR__ . '/../src/Models/PollModel.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$pollModel = new PollModel();

if ($pollModel->vote($data['poll_id'], $data['option'])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Erro ao votar']);
}
