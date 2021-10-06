<?php

namespace App\System\DebugBarTracking\Decorators;

use App\System\DebugBarTracking\Enums\OutputDecoratorRenderTypes;

class OutputDecorator extends OutputDecoratorRenderTypes
{
    private array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param OutputDecoratorRenderTypes $type
     * @return false|string
     */
    public function decorate(OutputDecoratorRenderTypes $type)
    {
        switch ($type->value) {
            case OutputDecoratorRenderTypes::DECORATE_HTML:
                $output = $this->decorateHtml();

                break;
            case OutputDecoratorRenderTypes::DECORATE_TABLE:
                $output = $this->decorateTable();

                break;
            case OutputDecoratorRenderTypes::DECORATE_JSON:
                $output = $this->decorateJson();

                break;
            default:
                $output = '';
        }

        return $output;
    }

    /**
     * @param $value
     * @param string $typeKey
     * @return array|mixed|string
     */
    private static function getDataValue($value, string $typeKey = '')
    {
        if(is_bool($value)) {
            $returnBoolText = 'No';
            if($value) {
                $returnBoolText = 'Yes';
            }

            return $returnBoolText;
        }

        if(is_array($value)) {
            if(empty($value)) {
                return 'Empty';
            }

            if($typeKey == 'params') {
                return implode(' | ', $value);
            }

            $value = '';
        }

        return $value;
    }

    /**
     * @param $data
     * @param string $typeKey
     */
    private static function prepareDataValueArrayForHtml($data, $typeKey = '')
    {
        foreach ($data as $key => $value) {
            $prepareKey = $key;
            $dataValue  = self::getDataValue($value, $key);

            if($typeKey == 'getSql') {
                $prepareKey = $value[0];
                $dataValue  = round($value[1], 4) .'sec';

                if(!empty($value[2])) {
                    $dataValue  .= " | {$value[2]}";
                }
            }

            echo "<dd>
                <strong>{$prepareKey}:</strong> {$dataValue}
            </dd>";
        }
    }

    /**
     * @return string
     */
    private function decorateHtml(): string
    {
        $html = '<div><dl>';

        foreach ($this->data as $key => $value) {
            $dataValue  = self::getDataValue($value);

            $html .= "<dt>
                <strong>{$key}:</strong> {$dataValue}
            </dt>";

            if(is_array($value)) {
                self::prepareDataValueArrayForHtml($value, $key);
            }
        }

        $html .= '</dl></div>';

        return $html;
    }

    /**
     * @param $data
     * @param string $typeKey
     */
    private static function prepareDataValueArrayForTable($data, $typeKey = '')
    {
        foreach ($data as $key => $value) {
            $prepareKey = $key;
            $dataValue  = self::getDataValue($value, $key);

            if($typeKey == 'getSql') {
                $prepareKey = $value[0];
                $dataValue  = round($value[1], 4) .'sec';

                if(!empty($value[2])) {
                    $dataValue  .= " | {$value[2]}";
                }
            }

            echo "<dd>
                <strong>{$prepareKey}:</strong> {$dataValue}
            </dd>";
        }
    }

    /**
     * @return string
     */
    private function decorateTable(): string
    {
        $table = '<table>
            <thead>';

        foreach ($this->data as $key => $value) {
            $table .= "<th>{$key}</th>";
        }

        $table .= '</thead>
            <tbody>';

        foreach ($this->data as $key => $value) {
            $dataValue  = self::getDataValue($value);

            $table .= "<td>{$dataValue}</td>";

            if(is_array($value)) {
                self::prepareDataValueArrayForTable($value, $key);
            }
        }

        $table .= '</tbody>
            </table>';

        return $table;
    }

    private function decorateJson()
    {
        return json_encode($this->data);
    }
}