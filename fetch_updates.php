<?php
//цей скрипт повертає HTML для всіх Collapse-об'єктів

header('Content-Type: text/html; charset=utf-8');

$data_file = 'collapse_data.json';
$collapse_items = [];
$html_content = '';

if (file_exists($data_file) && filesize($data_file) > 0) {
    $json_content = file_get_contents($data_file);
    $decoded_data = json_decode($json_content, true);
    
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_data)) {
        $collapse_items = $decoded_data;
    }
}

if (empty($collapse_items)) {
    $html_content = '<p style="text-align: center;">Дані Collapse ще не збережені.</p>';
} else {
    foreach ($collapse_items as $index => $item) {
        $unique_id = 'collapse-' . ($index + 1); 

        $html_content .= '
            <div class="collapse-header" data-target="#'.$unique_id.'">
                <span class="collapse-title">'.$item['title'].'</span>
                <span class="collapse-icon">+</span>
            </div>
        ';
        $html_content .= '
            <div class="collapse-body" id="'.$unique_id.'">
                <div class="collapse-inner">
                    '.$item['content'].'
                </div>
            </div>
        ';
    }
}

echo $html_content;
?>