<?php
header('Content-Type: application/json; charset=utf-8');
mb_internal_encoding('UTF-8');
include(dirname(__DIR__) . '/machine-learning/naive-bayes.php');
error_reporting(0);

if (isset($_FILES['file'])) {
    $tmp = $_FILES['file']['tmp_name'];
    $content = json_decode(file_get_contents($tmp), true);
    $string = $_POST['string'];
    $jsonResult = json_encode(['result' => naive_bayes($content, $string)], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($jsonResult === false)
        echo json_encode(['error' => json_last_error_msg()], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    else
        echo $jsonResult;
} else
    echo json_encode(['error' => 'Nenhum arquivo foi enviado'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

function naive_bayes($dataset, $phrase)
{
    $data = null;
    $classifier = new Naive_Bayes();
    if ($phrase !== false) {
        foreach ($dataset as $value)
            $classifier->train($value['title'], $value['array']);
        $data = $classifier->classify($phrase);
    }

    return $data;
}