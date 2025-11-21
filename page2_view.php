<?php
$x = 'блок X'; 
$y = 'блок Y';  

$W = 1040;
$H = 1120;
$cols = 4;
$rows = 4;

$data_file = 'collapse_data.json';
$collapse_items = [];

if (file_exists($data_file) && filesize($data_file) > 0) {
    $json_content = file_get_contents($data_file);

    $decoded_data = json_decode($json_content, true);    //декодуємо JSON у PHP-масив
    
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_data)) {
        $collapse_items = $decoded_data;
    }
}

//блоки у вигляді масиву, де r — стартовий ряд (1-based), c — стартовий стовпець (1-based)
//rows, cols — скільки рядків чи стовпців займає блок
$gridBlocks = [
    1 => ['r'=>1,'c'=>1,'rows'=>1,'cols'=>4,'label'=>1,'color'=>'#cfe8ff','align'=>'right'],
    2 => ['r'=>2,'c'=>1,'rows'=>3,'cols'=>1,'label'=>2,'color'=>'#e6f3e8','align'=>'right'],
    3 => ['r'=>2,'c'=>2,'rows'=>2,'cols'=>2,'label'=>3,'color'=>'#ffffff','align'=>'center'],
    4 => ['r'=>2,'c'=>4,'rows'=>1,'cols'=>1,'label'=>4,'color'=>'#e6f3e8','align'=>'left'],
    5 => ['r'=>3,'c'=>4,'rows'=>1,'cols'=>1,'label'=>5,'color'=>'#ffe8dc','align'=>'left'],
    6 => ['r'=>4,'c'=>2,'rows'=>1,'cols'=>3,'label'=>6,'color'=>'#cfe8ff','align'=>'right'],
];

//X і Y з координатами клітинки (r,c) і розміром
$tags = [
    ['label'=>$x, 'r'=>1, 'c'=>1, 'w'=>120, 'h'=>30, 'offset_x'=>10, 'offset_y'=>50],
    ['label'=>$y, 'r'=>4, 'c'=>2, 'w'=>120, 'h'=>30, 'offset_x'=>15, 'offset_y'=>0], 
];

$text2 = "Це текст для блоку 2";
$text3 = "Це текст для блоку 3";
$text4 = "Це текст для блоку 4";
$text5 = "Це текст для блоку 5";
$text6 = "Це текст для блоку 6";

$pages = [
    'Lab2.2.php' => '2 Блок',
    'page1_form.php' => '3. Create Collapse',
    'Lab2.1.php' => '4 Блок',
    'page2_view.php' => '5. View Collapse',
    'Lab2.6.php' => '6 Блок'
];

?>
<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Lab1 page1</title>
    <style>
        :root{
            --W:<?=$W?>px; 
            --H:<?=$H?>px; 
            --cols:<?=$cols?>; 
            --rows:<?=$rows?> 
        }
    </style>
</head>

<body>

<div class="frame">

    <?php foreach($gridBlocks as $id => $b):
    $colEnd = $b['c'] + $b['cols'];
    $rowEnd = $b['r'] + $b['rows'];


    if($id == 1){ //блок 1 - навігаційне меню
        $content = '<ul style="list-style:none; padding:0; margin:0; white-space: nowrap;">';
        foreach($pages as $url => $name){
            $content .= '<li><a href="'.$url.'" style="text-decoration:none;">'.$name.'</a></li>';
        }
        $content .= '</ul>';
    } elseif ($id == 3) { //блок 3 - відображення набору Collapse
        $content = '<div id="collapse-display" style="width: 100%; padding: 10px;">';
        
        if (empty($collapse_items)) {
            $content .= '<p style="text-align: center;">Дані Collapse ще не збережені.</p>';
        } else {
            foreach ($collapse_items as $index => $item) {
                $unique_id = 'collapse-' . ($index + 1); 

                $content .= '
                    <div class="collapse-header" data-target="#'.$unique_id.'">
                        <span class="collapse-title">'.$item['title'].'</span>
                        <span class="collapse-icon">+</span>
                    </div>
                ';
                $content .= '
                    <div class="collapse-body" id="'.$unique_id.'">
                        <div class="collapse-inner">
                            '.$item['content'].'
                        </div>
                    </div>
                ';
            }
        }

        $content .= '</div>';

    } elseif ($id == 5) { 
        $content = '<div id="update-status" style="padding: 10px; text-align: center; font-size: 14px;">Очікування оновлень...</div>';

    } else {
        $varName = 'text'.$id;
        $content = $$varName; 
    }
    ?>
    <div class="blk" style="grid-column: <?=$b['c']?> / <?=$colEnd?>; grid-row: <?=$b['r']?> / <?=$rowEnd?>; background: <?=$b['color']?>;">
        <div class="num <?=$b['align']?>"><?=$content?></div>
    </div>
    <?php endforeach; ?>


    <?php
        $cellW = $W / $cols;
        $cellH = $H / $rows;

        foreach($tags as $t){
            $left = ($t['c'] -1) * $cellW + $t['offset_x'];
            $top  = ($t['r'] -1) * $cellH + $t['offset_y'];
            echo '<div class="tag" style="left:'.$left.'px; top:'.$top.'px; width:'.$t['w'].'px; height:'.$t['h'].'px;">'.$t['label'].'</div>';
        }

    ?>

</div>

<script src="script_view.js"> </script>
</body>
</html>
