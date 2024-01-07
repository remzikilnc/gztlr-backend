<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRelationableModel extends Model
{
    /**
     * @return array
     * Array key is relation name
     * Array value is permission name
     * Array value can be blank if no permission is required
     * @example 'relation_name' => 'permission_name'
     */
    abstract public static function getLoadableRelations(): array;

}
