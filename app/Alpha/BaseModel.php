<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Alpha;

use App\Traits\HasSlackNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use Notifiable;
    use HasSlackNotification;

    /**
     * @return string
     */
    public static function getModelTable(): string
    {
        return (new static)->getTable();
    }

    /**
     * Get tha title of model for page title
     * $this->name_title_page
     * @param $value
     * @return mixed
     */
    public function getNameTitlePageAttribute($value)
    {
        return $value ?: $this->{$this->getDisplayNameColumn()};
    }

    /**
     * Get name of the column will be used to display the model name
     * @return string
     */
    public function getDisplayNameColumn(): string
    {
        return 'name';
    }

    /**
     * name of attribute will display tne model name Like created_at
     *
     * @return string
     */
    public function getNameColumn(): string
    {
        $class = static::class;
        $class = class_basename($class);
        $class = Str::snake($class);
        $class = Str::singular($class);
        $class = strtolower($class);
        $fill = [
            'name',
            locale_attribute(),
            "{$class}_name",
        ];
        $name = 'name';
        foreach ($fill as $item) {
            if ($this->isFillable($item)) {
                $name = $item;
                break;
            }
        }
        return $name;
    }

    /**
     * @param $key
     *
     * @return mixed|string|null|array|void
     */
    public function __get($key)
    {
        if (!$key) {
            return $this->getAttribute($key);
        }

        // If the attribute exists in the attribute array or has a "get" mutator we will
        // get the attribute's value. Otherwise, we will proceed as if the developers
        // are asking for a relationship's value. This covers both types of values.
        //if (array_key_exists($key, $this->attributes) || array_key_exists($key, $this->casts) || $this->hasGetMutator($key) || $this->isClassCastable($key)) {
        //    return $this->getAttributeValue($key);
        //}

        /** get_{ATTRIBUTE}_from_{RELATION}_class */
        if (substr($key, 0, strlen(($get = "get_"))) == $get && substr($key, -strlen(($trait = "_class"))) == $trait) {
            $call = substr($key, strlen($get), (strlen($key) - strlen($get)) - strlen($trait));
            $callArray = explode("_from_", $call);
            krsort($callArray);
            $method = $this;
            $i = 0;
            foreach ($callArray as $item) {
                $i++;
                try {
                    $method = $method->{$item};
                } catch (\Exception $exception) {
                    $method = '';
                }

                if ($i == count($callArray)) {
                    return ($method instanceof $this ? "" : (is_null($method) ? "" : $method));
                }
            }
        }

        /** get_{RELATION}_name */
        if (Str::startsWith($key, ($f = "get_")) && Str::endsWith($key, ($l = "_name"))) {
            $method = Str::before($key, $l);
            $method = Str::after($method, $f);
            return (($method && ($a = $this->{$method})) ? $a->{$a->getNameColumn()} : '');
        }

        /** {ATTRIBUTE}_code */
        if (Str::endsWith($key, ($t = "_code")) && !$this->isFillable($key)) {
            $method = $this->modelHasMethod(Str::before($key, $t));

            if (!is_null(($a = $this->{$method}))) {
                return $a->code;
            }
        }

        /** {ATTRIBUTE}_id_to_string */
        if (Str::endsWith($key, ($t = "_id_to_string"))) {
            $method = Str::before($key, $t);

            if (!is_null(($a = $this->{$method}))) {
                return $a->{$a->getNameColumn()};
            }

            if (!is_null(($a = $this->{Str::camel($method)}))) {
                return $a->{$a->getNameColumn()};
            }

            return !is_null(($a = $this->{$method})) ? $a->{$a->getNameColumn()} : $a;
        }

        /** {ATTRIBUTE}_to_number_format */
        if (Str::endsWith($key, ($t = "_to_number_format"))) {
            $value = Str::before($key, $t);
            return !is_null(($_name = $this->{$value})) ? to_number_format((float) $_name, 2, __("global.sar")) : $_name;
        }

        /** {ATTRIBUTE}_to_en_yes */
        if (Str::endsWith($key, ($t = "_to_en_yes")) && !$this->isFillable($key)) {
            $method = Str::before($key, $t);
            return !is_null(($_name = $this->{$method})) ? ($_name ? "yes" : "no") : $_name;
        }

        /** {ATTRIBUTE}_to_yes */
        if (Str::endsWith($key, ($t = "_to_yes")) && !$this->isFillable($key)) {
            $method = Str::before($key, $t);
            return !is_null(($_name = $this->{$method})) ? __("global.".($_name ? "yes" : "no")) : $_name;
        }

        /** {DATE_ATTRIBUTE}_to_date_format */
        if (Str::endsWith($key, ($t = "_to_date_format")) && ($attribute = Str::before($key, $t))) {
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return $date->format(config('config.date_format.date'));
            }
        }

        /** {DATE_ATTRIBUTE}_to_time_format */
        if (Str::endsWith($key, ($t = "_to_time_format")) && ($attribute = Str::before($key, $t))) {
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return $date->format(config('config.date_format.time'));
            }
        }

        /** {DATE_ATTRIBUTE}_to_time_string_format */
        if (Str::endsWith($key, ($t = "_to_time_string_format")) && ($attribute = Str::before($key, $t))) {
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return date_by_locale($date->format(config('config.date_format.time_string')));
            }
        }

        /** {DATE_ATTRIBUTE}_to_datetime_format */
        if (Str::endsWith($key, ($t = "_to_datetime_format")) && ($attribute = Str::before($key, $t))) {
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return date_by_locale($date->format(config('config.date_format.datetime')));
            }
        }

        /** {DATE_ATTRIBUTE}_to_day_format */
        if (Str::endsWith($key, ($t = "_to_day_format"))) {
            $attribute = substr($key, 0, strlen($key) - strlen($t));
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return date_by_locale($date->format(config('config.date_format.day')));
            }
        }

        /** {DATE_ATTRIBUTE}_to_hijri */
        if (Str::endsWith($key, ($t = "_to_hijri"))) {
            $attribute = substr($key, 0, strlen($key) - strlen($t));
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return hijri($date);
            }
        }

        /** {DATE_ATTRIBUTE}_to_full_arabic_date */
        if (Str::endsWith($key, ($t = "_to_full_arabic_date"))) {
            $attribute = substr($key, 0, strlen($key) - strlen($t));
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                // dd($attribute,$date,hijri($date)->format( app_date_format('date') ) );

                return arabic_date(hijri($date)->format(config('config.date_format.hijri_human')));
            }
        }

        /** {DATE_ATTRIBUTE}_to_arabic_date */
        if (Str::endsWith($key, ($t = "_to_arabic_date"))) {
            $attribute = substr($key, 0, strlen($key) - strlen($t));
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return arabic_date(hijri($date)->format(config('config.date_format.date')));
            }
        }

        /** {RELATION}_to_ids */
        if (Str::endsWith($key, ($t = "_to_ids"))) {
            $relation = Str::beforeLast($key, $t);
            if (method_exists($this, $relation)) {
                $m = $this->{$relation}();
                if ($m instanceof HasMany) {
                    return $m->pluck('id')->toArray();
                }
                if ($m instanceof BelongsToMany) {
                    $name = Str::snake(Str::singular($relation));
                    return $m->pluck("{$name}_id")->toArray();
                }
            }
        }

        /** Original */
        return parent::__get($key);
    }

}
