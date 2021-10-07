<?php

namespace App\System\DebugBarTracking\Decorators;

use App\System\DebugBarTracking\Entities\DebugBarInformationHolderEntity;
use App\System\DebugBarTracking\Enums\OutputDecoratorRenderTypes;

class OutputDecorator extends OutputDecoratorRenderTypes
{
    private DebugBarInformationHolderEntity $holderEntities;

    /**
     * @param DebugBarInformationHolderEntity $holderEntities
     */
    public function __construct(DebugBarInformationHolderEntity $holderEntities)
    {
        $this->holderEntities = $holderEntities;
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
     * @param array $data
     * @param string $typeHandle
     * @return string
     */
    private static function prepareDataValueArrayForHtml(array $data, string $typeHandle = ''): string
    {
        $html = '';
        foreach ($data as $key => $value) {
            $prepareKey = $key;
            $dataValue  = $value;

            if(is_bool($value)) {
                $dataValue = 'No';

                if($value) {
                    $dataValue = 'Yes';
                }
            }

            if($typeHandle == 'SQL') {
                $prepareKey = $value[0];
                $dataValue  = round($value[1], 4) .'sec';

                if(!empty($value[2])) {
                    $dataValue  .= " | {$value[2]}";
                }
            }

            $html .= "<dd>
                <strong>{$prepareKey}:</strong> {$dataValue}
            </dd>";
        }

        return $html;
    }

    /**
     * @param string $handle
     * @param $value
     * @return string
     */
    private function prepareHtml(string $handle, $value): string
    {
        if(empty($value)) {
            return '';
        }

        $handleList = '';
        if(is_array($value)) {
            $handleList = $this->prepareDataValueArrayForHtml($value, $handle);

            $value = '';
        }

        $html = "<dt>
                <strong>{$handle}:</strong> {$value}
            </dt>";
        $html .= $handleList;

        return $html;
    }

    /**
     * @return string
     */
    private function decorateHtml(): string
    {
        $html = '<div><dl>';
        $html .= $this->prepareHtml('URL', $this->holderEntities->getUrl());
        $html .= $this->prepareHtml('IP', $this->holderEntities->getClientIP());
        $html .= $this->prepareHtml('Method', $this->holderEntities->getRequestMethod());
        $html .= $this->prepareHtml('POST', $this->holderEntities->getRequestPost());
        $html .= $this->prepareHtml('GET', $this->holderEntities->getRequestGet());
        $html .= $this->prepareHtml('SQL', $this->holderEntities->getSql());
        $html .= $this->prepareHtml('User', $this->holderEntities->getUser());
        $html .= $this->prepareHtml('Memory', $this->holderEntities->getMemory());
        $html .= $this->prepareHtml('Time', $this->holderEntities->getTime());
        $html .= '</dl></div>';

        return $html;
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