<?php
function create($class, $attributes=[], $times=null)
{
    return factory($class, $times)->create($attributes);
}
function make($class, $attributes=[], $times=null)
{
    return factory($class, $times)->make($attributes);
}
// function create_data($class, $file_name, $table, $id = null)
// {
//     $dataloc = dirname(dirname(__DIR__)).'/database/sampledata/'.$file_name;
//
//     return sample_migrate($class, $table, $dataloc);
// }
// function sample_migrate($class, $table, $file_loc)
// {
//     DB::table($table)->delete();
//     $handle = fopen($file_loc, "r");
//     $attributes = array();
//     if ($handle) {
//         $headers = [];
//
//         while (($line = fgets($handle)) !== false) {
//             if (count($headers) === 0) {
//                 $headers = str_getcsv(strtolower($line), ",");
//                 dd($headers);
//             } else {
//                 $data = str_getcsv($line, ",");
//                 //dd($headers);
//                 $uniqueIdData = array_combine($headers, $data);
//                 array_push($attributes, $uniqueIdData);
//             }
//         }
//
//         fclose($handle);
//
//         DB::table($table)->insert($attributes);
//
//
//         return $attributes;
//     }
//     //return $uniqueIdModel;
// }
