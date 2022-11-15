<?php


namespace App\Traits;


trait HasTreeView
{
    public function treeView()
    {
        $parents = parent::query()->parents()->get();
        $response = [];
        foreach ($parents as $parent) {
            $item = [
                "id" => $parent->id,
                "text" => $parent->title,
                "children" => []
            ];

            $children = $parent->children()->get();
            foreach ($children as $child) {
                $item["children"][] = [
                    "id" => $child->id,
                    "text" => $child->title,
                ];
            }
            $response[] = $item;
        }
        return json_encode($response);
    }
}
