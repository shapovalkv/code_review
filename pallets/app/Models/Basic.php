<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Basic
{
    public function save($model, $data, $id = null): ?Model
    {
        if (is_null($id)) {
            $entity = $model::create($data);
        } else {
            $entity = $model::findOrFail($id);
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    if (in_array($key, $entity->getFillable())) {
                        $entity->$key = $value;
                    }
                }
            }
            $entity->save();
        }
        return $entity;
    }
}
