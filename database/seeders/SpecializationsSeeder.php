<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Specialization;
use Throwable;

class SpecializationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try{

            DB::disableQueryLog();
            Specialization::truncate();

            $path = storage_path('app/public/spcialties.csv');
            $file = fopen($path,'r');

            $firstLine = true;

            while (($data = fgetcsv($file)) !== false) {


                if (!$firstLine) {

                    if(empty($data[3])){
                        continue;
                    }

                    Specialization::create([
                        "code" => $data[0],
                        "grouping" => $data[1],
                        'classification' => $data[2],
                        'specialization' => $data[3],
                        'definition' => $data[4]
                    ]);
                }
                else{
                    $firstLine = false;
                }
            }

            fclose($file);
        }
        catch(Throwable $error){
            echo $error->getMessage() . PHP_EOL;
        }
    }
}
