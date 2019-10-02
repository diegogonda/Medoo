<?php

namespace Medoo;

trait CastTrait {
    /**
     * Convierte un bloque de datos en otro con tipos, alias, etc.
     * @param type $data
     * @param type $columns
     * @param type $root
     * @return array
     */
    public function dataCast($data, $columns) {
        $column_map = [];
        $map = $this->columnMap($columns, $column_map, false);
        foreach ($data as $key => $value) {
            $currentStack = [];
            $currentType = gettype($value);
            $this->dataMap((array) $value, $columns, $map, $currentStack, false, $value);
            settype($currentStack, $currentType);
            $data[$key] = $currentStack;
        }
        return $data;
    }

}
