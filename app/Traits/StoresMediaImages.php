<?php

namespace App\Traits;
use App\Models\Game;

trait StoresMediaImages
{
    public function storeImages(array $values, Game $model): void
    {
        $values = array_map(function($value) use($model) {
            $value['model_id'] = $model->id;
            $value['model_type'] = get_class($model);
            return $value;
        }, $values);

        $model->images()->where('source', '!=', 'local')->delete();
        $model->images()->insert($values);
    }
}
