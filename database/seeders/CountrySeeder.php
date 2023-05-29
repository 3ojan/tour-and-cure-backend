<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->parseCsv();
    }

    /**
     * From https://github.com/lukes/ISO-3166-Countries-with-Regional-Codes/blob/master/all/all.csv
     */
    public function parseCsv()
    {
        $filepath = dirname(__FILE__) . '/iso_3166_country_table.csv';

        if (($handle = fopen($filepath, 'r')) !== false) {
            $head = fgetcsv($handle, 1000, ",");
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $row = array_map('trim', $row);
                Country::create([
                    'name' => $row[0],
                    'iso' => $row[2],
                    'alpha2' => $row[1],
                    'alpha3' => $row[2],
                    'code' => $row[3],
                    'iso3166_2' => $row[4],
                    'tld' => '',
                    'region' => $row[5],
                    'sub_region' => $row[6],
                    'intermediate_region' => $row[7],
                    'region_code' => $row[8],
                    'sub_region_code' => $row[9],
                    'intermediate_region_code' => $row[10],
                ]);
            }
            fclose($handle);
        }
    }
}
