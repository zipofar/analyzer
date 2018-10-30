<?php

namespace App\Resources;

class FabricResources
{
    public static function buildResources(array $resources, $class)
    {
        $newResources = array_map(function ($item) use ($class) {
            return new $class ($item);
        }, $resources);

        return $newResources;
    }
}