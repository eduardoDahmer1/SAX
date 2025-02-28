<?php

namespace App\Models;
class Compare extends CachedModel
{
    public $items = [];

    public function __construct($oldCompare = null)
    {
        parent::__construct();
        if ($oldCompare) {
            $this->items = $oldCompare->items ?? [];
        }
    }
    public function add($item, $id)
    {
        $this->items[$id] = [
            'ck' => 1,
            'item' => $item,
        ];
    }
    public function removeItem($id)
    {
        unset($this->items[$id]);
    }
    public function isItemCompared($id): bool
    {
        return isset($this->items[$id]);
    }
}
