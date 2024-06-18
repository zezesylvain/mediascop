<?php

if (!function_exists("formatMailColumn")){
    function formatMailColumn(int $val,string $couleur='#ffffff')
    {
        if ($val !== 0):
            return $val;
        else:
            return '';
        endif;
    }
}

if (!function_exists("userIndications")){
    function userIndications()
    {

    }
}

if (!function_exists("userId")){
    function userId()
    {
        return \Illuminate\Foundation\Auth\User::id();
    }
}

if (!function_exists('tableForeignKey')){
    function tableForeignKey($dbtable,$tablename)
    {
        $query = "SELECT
                        TABLE_NAME,
                        COLUMN_NAME,
                        CONSTRAINT_NAME,
                        REFERENCED_TABLE_NAME,
                        REFERENCED_COLUMN_NAME
                    FROM
                        INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE
                        REFERENCED_TABLE_SCHEMA = '$dbtable'
                        AND REFERENCED_TABLE_NAME = '$tablename'";
        return \Illuminate\Support\Facades\DB::select($query);
    }
}

if (!function_exists("transformResult2Array")){
    function transformResult2Array($donnees): array
    {
        $data = [];
        foreach ($donnees AS $row):
            $t = [];
            foreach ($row AS $k => $v) :
                $t[$k] = $v;
            endforeach;
            $data[] = $t;
        endforeach;
        return $data;
    }

}