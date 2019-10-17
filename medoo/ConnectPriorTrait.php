<?php

namespace Medoo;

trait ConnectPriorTrait {

    public function getStructuredData(
            string $table,
            $join,
            $columns = null,
            $where = null
    ) {
        $data = $this->select($table, $join, $columns, $where);
        return $this->buildTree($data, 0, true);
    }

    public function buildTree(
            array $elements,
            int $parentId = 0
    ): Array {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

}
