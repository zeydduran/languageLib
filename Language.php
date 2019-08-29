<?php
namespace App\Libs\Language;

use DB;

class Language
{
    protected $config;
    protected $module = null;
    protected $language_id = 0;
    protected $change = null;
    protected $translate = null;
    public function __construct()
    {
        $this->config = config('app.language');
    }
    public function module($module)
    {
        $this->module = $module;
        return $this;
    }
    public function setLanguageId($language_id)
    {
        $this->language_id = $language_id;
        return $this;
    }
    public function change($change)
    {
        echo "string";
        dd($this->translate,true);
        $str = $this->translate;

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
        dd($str,true);
        return $str;
    }
    public function translate($expression)
    {
        $re = '/([a-zA-Z0-9\-\_]+)/i';
        preg_match_all($re, $expression, $matches);
        DB::table($this->config["DB"]["values"]);
        if (!is_null($this->module)) {
            DB::where("module","=",$this->module);
        }
        DB::where("value","=",$matches[0][0]);
        $value = DB::getRow();
        $text = json_decode($value->text,true);
        $this->translate = $text[$this->language_id];
        // dd($text[$this->language_id]);
        var_dump($this->hasChange());
        dd($this->change);
            return $this;

    }
    protected function hasChange()
    {
        return is_null($this->change) ? false : true;
    }
    public function start()
    {
        dd($this, true);
    }
}
