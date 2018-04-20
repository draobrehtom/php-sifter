
$data = [
    '_id' => '5ac73b4cde79c0014f42fe64',
    'box' => ['box1' => ['box2' => ['box3' => 'tadam']]],
    'anotherValue' => 'madat'
];

$grid = [
    'objectId' => 'objectId|_id|anotherValue',
    'somethingElse' => 'box.box1.box2.box3|box3.box2.box1|anotherValue'
];

$orSymbol = '|';

$siftedData = [];

$goOver = function ($array, $byIndexes) use (&$goOver)
{
    if (! is_array($array) and ! is_object($array)) {
        return $array;
    }

    $index = $byIndexes[0];

    if (count($byIndexes) > 1) {
        return $goOver($array[$index] ?? '', array_slice($byIndexes, 1));
    }

    return $array[$index] ?? '';
};

foreach ($grid as $newName => $possibleDataPath) {

    while (($orSymbolPosition = strpos($possibleDataPath, $orSymbol)) or $possibleDataPath) {

        if ($orSymbolPosition === false) {
            $path = $possibleDataPath;
        } else {
            $path = substr($possibleDataPath, 0, $orSymbolPosition);
        }

        $griddedValue = $goOver($data, explode('.', $path));
        $possibleDataPath = substr($possibleDataPath, $orSymbolPosition + 1);
        $siftedData[$newName] = $griddedValue;

        if (! $possibleDataPath or ! empty($griddedValue) or $orSymbolPosition === false) {
            break;
        }
    }
}

print_r($siftedData);
