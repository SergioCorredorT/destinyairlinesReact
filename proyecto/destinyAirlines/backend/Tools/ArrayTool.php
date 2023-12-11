<?php
class ArrayTool
{
    public function getUniqueValues(array $data, string $field)
    {
        $values = [];
        foreach ($data as $item) {
            $values[$item[$field]] = true;
        }
        return array_keys($values);
    }

    public function groupByField(array $data, string $field)
    {
        $groupedData = [];
        foreach ($data as $item) {
            $fieldValue = $item[$field] ?? 0;
            $groupedData[$fieldValue][] = [
                'serviceName' => $item['serviceName'],
                'oldPrice' => $item['oldPrice']
            ];
        }
        return $groupedData;
    }    
}
