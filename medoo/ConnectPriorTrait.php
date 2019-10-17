<?php

namespace Medoo;

trait ConnectPriorTrait {

    public function getStructuredData(
            string $table,
            $join,
            $columns = null,
            $where = null,
            string $idKey = "id",
            string $parentKey = "parent"
    ) {
        $data = $this->select($table, $join, $columns, $where);
        return $this->buildTree($data, 0, $idKey, $parentKey);
    }

    public function buildTree(
            array $elements,
            int $parentId = 0,
            string $idKey = "id",
            string $parentKey = "parent"
    ): Array {
        $branch = [];
        foreach ($elements as $element) {
            if ($element[$parentKey] == $parentId) {
                $children = $this->buildTree($elements, $element[$idKey], $idKey, $parentKey);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

}
