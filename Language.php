<?php
namespace App\Libs\Language;

use DB;

class Language
{
    private static $config;
    private static $module = null;
    private static $language_id = 0;
    private static $change = null;
    private static $translate = null;
    public function __construct()
    {
        self::$config = config('app.language');
    }
    private function __clone()
    {
    }
    public function module($module)
    {
        self::$module = $module;
        return $this;
    }
    public function setLanguageId($language_id)
    {
        self::$language_id = $language_id;
        return $this;
    }
    public function change($change)
    {
        $str = self::$translate;

        // Change special words
        if (!is_array($change)) {

            if (!empty($change))
            return str_replace('%s', $change, $str);

            return $str;
        }

        if (!empty($change)) {

            $keys = [];
            $vals = [];

            foreach($change as $key => $value) {
                $keys[] = $key;
                $vals[] = $value;
            }

            return str_replace($keys, $vals, $str);
        }
        return $str;
    }
    public function translate($expression)
    {
        $re = '/([a-zA-Z0-9\-\_]+)/i';
        preg_match_all($re, $expression, $matches);
        DB::table(self::$config["DB"]["values"]);
        if (!is_null(self::$module)) {
            DB::where("module","=",self::$module);
        }
        DB::where("value","=",$matches[0][0]);
        $value = DB::getRow();
        $text = json_decode($value->text,true);
        self::$translate = $text[self::$language_id];

        // dd($text[$this->language_id]);
        var_dump(self::hasChange());
            return new self;

    }
    protected function hasChange()
    {
        return is_null(self::$change) ? false : true;
    }


}
