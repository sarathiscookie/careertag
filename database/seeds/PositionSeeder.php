<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Tagcategory;
use App\Tag;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tagcategories = [
            ['title' => 'POSITION'],
        ];
        Tagcategory::insert($tagcategories);

        function importPosition($filename='', $delimiter=',')
        {
            if(!file_exists($filename) || !is_readable($filename))
                return FALSE;

            $header = NULL;
            $data = array();
            if (($handle = fopen($filename, 'r')) !== FALSE)
            {
                while (($row = fgetcsv($handle, 5000, $delimiter)) !== FALSE)
                {
                    foreach($row as $key => $val)
                    {
                        $row[$key] = iconv('ISO-8859-1', 'UTF-8', $row[$key]);
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
        $csvFile = public_path().'/csv/desiredposition.csv';
        $rows    = importPosition($csvFile);

        Tag::insert($rows);
    }
}
