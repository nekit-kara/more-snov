<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 31.10.18
 * Time: 10:04
 */

namespace App\Repositories;

use App\Models\Manufacturer;
use App\Models\ManufacturerToStore;

class ManufacturerRepository
{
    public function getManufacturerIdByName($name)
    {
        return Manufacturer::where('name', '=', $name)->first();
    }

    public function createManufacturer($name)
    {
        $manufacturer = new Manufacturer();
        $manufacturer->name = $name;
        $manufacturer->sort_order = 0;

        if ($manufacturer->save()) {
            $manufacturerToStore = new ManufacturerToStore();
            $manufacturerToStore->manufacturer_id = $manufacturer->manufacturer_id;
            $manufacturerToStore->store_id = 0;
            $manufacturerToStore->save();
        }
        return $manufacturer->manufacturer_id;
    }
}