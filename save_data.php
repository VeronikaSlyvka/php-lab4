<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Невірний метод запиту.']);
    exit;
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);  //декодування JSON-рядока в асоціативний масив PHP

if (json_last_error() !== JSON_ERROR_NONE || !isset($data['collapseItems'])) {
    echo json_encode(['success' => false, 'message' => 'Недійсний формат даних.']);
    exit;
}

$collapseItems = $data['collapseItems'];
$validatedItems = [];

//обробка та очищення кожного елемента
foreach ($collapseItems as $item) {
    if (empty($item['title']) || empty($item['content'])) {
        continue;
    }
    
    $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');
    
    $content = $item['content']; 
    
    $validatedItems[] = [
        'title' => $title,
        'content' => $content
    ];
}

if (empty($validatedItems)) {
    echo json_encode(['success' => false, 'message' => 'Немає коректних даних для збереження.']);
    exit;
}

$filename = 'collapse_data.json';
$json_to_save = json_encode($validatedItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

//запис у файл
if (file_put_contents($filename, $json_to_save) === false) {
    echo json_encode(['success' => false, 'message' => 'Помилка запису файлу на сервері. Перевірте права доступу.']);
} else {
    echo json_encode(['success' => true, 'message' => 'Дані успішно збережено у файл ' . $filename]);
}

?>