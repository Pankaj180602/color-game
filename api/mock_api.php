<?php
$colors = ['Red', 'Green', 'Violet'];
echo json_encode(['color' => $colors[array_rand($colors)]]);
?>
