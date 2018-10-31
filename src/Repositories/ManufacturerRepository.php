<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 31.10.18
 * Time: 10:04
 */

namespace App\Repositories;

use App\Models\Manufacturer;

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
    }
}