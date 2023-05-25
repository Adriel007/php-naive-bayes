<?php
header('Content-Type: application/json; charset=utf-8');
mb_internal_encoding('UTF-8');
include(dirname(__DIR__) . '/machine-learning/naive-bayes.php');
error_reporting(0);

if (isset($_FILES['file'])) {
    $string = $_POST['string'];
    $tmp = $_FILES['file']['tmp_name'];
    $content = file_get_contents($tmp);
    $array = explode('@separatorphp@', $content);
    array_pop($array);

    $jsonResult = json_encode(['result' => naive_bayes($array, $string)['type']], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($jsonResult === false)
        echo json_encode(['error' => json_last_error_msg()], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    else
        echo $jsonResult;
} else
    echo json_encode(['error' => 'Nenhum arquivo foi enviado'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

function naive_bayes($dataset, $phrase)
{
    $data = [
        'all' => [],
        'type' => null
    ];
    $classifier = new Naive_Bayes();
    if ($phrase !== false) {
        $keys = array_keys($dataset);

        $len = count($keys);
        for ($c = 0; $c < $len; $c++) {
            foreach ($dataset[$keys[$c]] as $value)
                $classifier->train($keys[$c], $value);

            $groups = $classifier->classify($phrase);
            if ($groups[$keys[$c]] * 100 >= 1)
                array_push($data['all'], $phrase . ' -> ' . $groups[$keys[$c]] . ' -> ' . $keys[$c]);
        }

        $type = array_search(max($groups), $groups);
        $data['type'] = $type;
        return $data;
    }

    return $data;
}