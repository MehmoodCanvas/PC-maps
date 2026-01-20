<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type'];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        $value = $setting->value;

        switch ($setting->type) {
            case 'number':
                return (float) $value;
            case 'boolean':
                return $value === 'true' || $value === '1' || $value === 1;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public static function set($key, $value, $type = 'string')
    {
        if (in_array($type, ['number', 'boolean', 'json'])) {
            if ($type === 'json') {
                $value = json_encode($value);
            } else {
                $value = (string) $value;
            }
        }

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }
}
