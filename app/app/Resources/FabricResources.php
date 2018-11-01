<?php

namespace App\Resources;

class FabricResources
{
    public static function buildResources(array $resources, $class, \App\Resources\Html $page)
    {
        $newResources = array_map(function ($item) use ($class, $page) {
            $resource =  new $class ($item, $page);
            return $resource;
        }, $resources);

        return $newResources;
    }
}