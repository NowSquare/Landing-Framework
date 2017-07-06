<?php

namespace Modules\Properties\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PropertiesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
/*
        $PROPERTY_TYPES = [
          'apartment',
          'room',
          'studio',
          'ground_floor',
          'upstairs',
          'bungalow',
          'recreation_house',
          'drive_in_house',
          'canal_house',
          'duplex',
          'penthouse',
          'split_level',
          'intermediate_house',
          'corner_house',
          'semi_detached',
          'detached_house',
          'villa',
          'caravan',
          'caravan_station',
          'houseboat',
          'garage_box',
          'parking_lot',
          'storage',
          'houseboat',
          'berth',
          'single_family_home',
          'condominium_apartment',
          'townhouse_townhome',
          'co_op_unit',
          'duplex',
          'multi_family_home',
          'patio_home',
          'mobile_manufactured_home',
          'mobile_home_position',
          'farmhouse',
          'commercial_building',
          'building_lot',
          'raw_land',
          'other'
        ];

        \DB::table('property_types')->delete();

        foreach($PROPERTY_TYPES as $property_type)
        {
          \Modules\Properties\Http\Models\PropertyType::create([
            'name' => $property_type
          ]);
        }

        $SALES_TYPES = [
          'sale',
          'rent',          
          'share'
        ];

        \DB::table('sales_types')->delete();

        foreach($SALES_TYPES as $sales_type)
        {
          \Modules\Properties\Http\Models\SalesType::create([
            'name' => $sales_type
          ]);
        }

        $PROPERTY_SURROUNDING = [
          'at_edge_of_wood',
          'by_water',
          'in_center',
          'in_green_area',
          'in_residential_district',
          'on_busy_road',
          'on_navigable_waterway',
          'on_quiet_road',
          'open_position',
          'outside_built_up_area', // outside built-up area
          'overlooking_park',
          'rural',
          'sheltered_position',
          'unobstructed_view'
        ];

        \DB::table('property_surroundings')->delete();

        foreach($PROPERTY_SURROUNDING as $property_surrounding)
        {
          \Modules\Properties\Http\Models\PropertySurrounding::create([
            'name' => $property_surrounding
          ]);
        }

        $PROPERTY_FEATURES = [
          'assisted_living',
          'waterfront_property',
          'basement',
          'golf_property',
          'dining_room',
          'central_air',
          'family_room',
          'swimming_pool',
          'jacuzzi',
          'wheelchair_access',
          'garage',
          'bathtub',
          'central_heating_boiler',
          'commercial_space',
          'elevator',
          'fixer_upper', // Fixer-upper
          'monumental_building',
          'open_fireplace',
          'renewable_energy',
          'sauna',
          'shed_storage', // Shed/storage
          'steam_cabin'
        ];

        \DB::table('property_features')->delete();

        foreach($PROPERTY_FEATURES as $property_feature)
        {
          \Modules\Properties\Http\Models\PropertyFeature::create([
            'name' => $property_feature
          ]);
        }

        $PROPERTY_GARAGES = [
          'garage',
          'lean_to', // Lean-to
          'lock_up_garage', // Lock-up garage
          'garage_carport', // Garage + Carport
          'built_in', // Built-in
          'underground_car_park',
          'basement',
          'detached'
        ];

        \DB::table('property_garages')->delete();

        foreach($PROPERTY_GARAGES as $property_garage)
        {
          \Modules\Properties\Http\Models\PropertyGarage::create([
            'name' => $property_garage
          ]);
        }
*/
    }
}
