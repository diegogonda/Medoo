<?php

namespace Medoo;

trait ConnectPriorTrait {

    /**
     * Recuperar datos de la base de datos con herencia.
     * Nos permite recuperar un array de elementos ordenados dentro de un array padre-hijos recursivo
     * 
     * @param string $table para hacer el select
     * @param type $join para hacer el select
     * @param type $columns para hacer el select
     * @param type $where para hacer el select
     * @param string $idTag codigo al que referencia el parent
     * @param string $parentTag nombre de la columna padre
     * @param string $childrenTag nombre que pondremos al registro hijo
     *                              por defecto 'children'
     *                              si el string está entre llaves, por ejemplo {nombre} busca ese key entre los de la fila y usa el valor como key
     * @return array
     */
    public function hierarchicalQuery(
            string $table,
            $join,
            $columns = null,
            $where = null,
            string $idTag = "id",
            string $parentTag = "parent",
            string $childrenTag = "children"
    ): Array {
        $data = $this->select($table, $join, $columns, $where);
        return $this->buildTree($data, 0, $idTag, $parentTag, $childrenTag);
    }

    /**
     *
     * Nos permite recuperar un array de elementos ordenados dentro de un array padre-hijos recursivo
     * @param array $elements
     * @param int $parentId id que vamos a analizar
     * @param string $idTag codigo al que referencia el parent
     * @param string $parentTag nombre de la columna padre
     * @param string $childrenTag
     * @param string $childrenTag nombre que pondremos al registro hijo
     *                              por defecto 'children'
     *                              si el string está entre llaves, por ejemplo {nombre} busca ese key entre los de la fila y usa el valor como key
     * @return array
     */
    public function buildTree(
            array $elements,
            int $parentId = 0,
            string $idTag = "id",
            string $parentTag = "parent",
            string $childrenTag = "children"
    ): Array {
        $branch = [];
        foreach ($elements as $element) {
            if ($element[$parentTag] === $parentId) {
                $currentChildrenTag = $this->retrieveTag($childrenTag, $element);
                $children = $this->buildTree($elements, $element[$idTag], $idTag, $parentTag, $childrenTag);
                if ($children) {
                    $element[$currentChildrenTag] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    private function retrieveTag(string $childrenTag, Array $row): string {
        $key = $childrenTag;
        $match = [];
        preg_match('#\{(.*?)\}#', $childrenTag, $match);
        if (isset($match[1]) && isset($row[$match[1]])) {
            $key = $row [$match[1]];
        }
        return $key;
    }

}
