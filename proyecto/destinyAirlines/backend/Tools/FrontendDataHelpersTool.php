<?php
class FrontendDataHelpersTool
{
    //Esta función divide los names recibidos desde frontend de forma que quede la info. estructurada en array
    static function processNestedKeys(array $data): array
    {
        // Procesamos las claves para convertirlas en un array
        $processedData = [];
        foreach ($data as $key => $value) {
            $keyParts = explode('[', str_replace(']', '', $key));
            $currentLevel = &$processedData;
            foreach ($keyParts as $keyPart) {
                if (!isset($currentLevel[$keyPart])) {
                    $currentLevel[$keyPart] = [];
                }
                $currentLevel = &$currentLevel[$keyPart];
            }
            $currentLevel = $value;
        }

        // Ahora $processedData debería tener la estructura de array que esperas
        return $processedData;
    }
}
