<?php

use Illuminate\Database\Seeder;
use App\Abilitytest;

class AbilitytestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        function abilitytest($filename='', $delimiter=',')
        {
            if(!file_exists($filename) || !is_readable($filename))
                return FALSE;

            $header = NULL;
            $data = array();
            if (($handle = fopen($filename, 'r')) !== FALSE)
            {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
                {
                    foreach($row as $key => $val)
                    {
                        $row[$key] = $row[$key];
                    }
                    if(!$header)
                        $header = $row;
                    else
                        $data[] = array_combine($header, $row);
                }
                fclose($handle);
            }
            return $data;
        }
        $csvFile = public_path().'/csv/abilitytests.csv';
        $areas   = abilitytest($csvFile);
        Abilitytest::insert($areas);
    }
}
