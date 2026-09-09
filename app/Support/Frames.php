<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Frame art lives in public/frames. Each file is a photographed length of
 * frame moulding (a swatch), not a hollow frame -- the preview builds a mitred
 * frame from it at render time (see frame-preview.js).
 *
 * The files are named after the supplier's stock codes (C22, E1, J12...).
 * Customers and admins see a friendly display name instead, which defaults to
 * Op1, Op2, Op3... and can be renamed by the admin on the Pricing screen.
 */
class Frames
{
    const EXTENSIONS = ['svg', 'png', 'jpg', 'jpeg', 'webp'];

    private static $files;
    private static $labels;
    private static $codes;

    /**
     * Settings key holding the display name for a frame file.
     */
    public static function key($file)
    {
        return 'frame_label_' . str_replace(' ', '_', $file);
    }

    /**
     * Filesystem-safe stem used for generated derivatives.
     */
    public static function slug($file)
    {
        return preg_replace('/[^A-Za-z0-9]+/', '_', pathinfo($file, PATHINFO_FILENAME));
    }

    /**
     * Supplier stock code for a frame file: "C22SVG (2).svg" and the older
     * "C22 background removed.png" both yield "C22".
     */
    public static function code($file)
    {
        $stem = pathinfo((string) $file, PATHINFO_FILENAME);

        return preg_match('/^([A-Za-z]+\d+)/', $stem, $matches) ? strtoupper($matches[1]) : null;
    }

    /**
     * All frame art files in public/frames, alphabetically ordered.
     */
    public static function files()
    {
        if (self::$files !== null) {
            return self::$files;
        }

        $dir = public_path('frames');

        if (!is_dir($dir)) {
            return self::$files = [];
        }

        $files = array_filter(scandir($dir), function ($file) use ($dir) {
            if (in_array($file, ['.', '..']) || is_dir($dir . '/' . $file)) {
                return false;
            }

            return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), self::EXTENSIONS);
        });

        return self::$files = array_values($files);
    }

    /**
     * Map a stored map_frame value onto a frame file that still exists.
     *
     * Frame art has been re-supplied in different formats over time, so rows
     * saved against an older filename are matched back by stock code.
     */
    public static function resolve($stored)
    {
        if (empty($stored) || $stored === 'none') {
            return null;
        }

        $files = self::files();

        if (in_array($stored, $files, true)) {
            return $stored;
        }

        if (self::$codes === null) {
            self::$codes = [];
            foreach ($files as $file) {
                $code = self::code($file);
                if ($code !== null && !isset(self::$codes[$code])) {
                    self::$codes[$code] = $file;
                }
            }
        }

        $code = self::code($stored);

        return $code !== null && isset(self::$codes[$code]) ? self::$codes[$code] : null;
    }

    /**
     * Map of frame file => display name, in file order.
     */
    public static function labels()
    {
        if (self::$labels !== null) {
            return self::$labels;
        }

        $files = self::files();

        $stored = Setting::whereIn('key', array_map([self::class, 'key'], $files))
            ->pluck('value', 'key');

        $labels = [];

        foreach ($files as $index => $file) {
            $value = $stored[self::key($file)] ?? null;
            $labels[$file] = ($value === null || $value === '') ? 'Op' . ($index + 1) : $value;
        }

        return self::$labels = $labels;
    }

    /**
     * Display name for a single stored frame value.
     */
    public static function label($stored)
    {
        if (empty($stored) || $stored === 'none') {
            return 'No Frame';
        }

        $file = self::resolve($stored);

        return $file === null ? $stored : self::labels()[$file];
    }

    /**
     * Persist an admin-supplied display name. Blank falls back to the default.
     */
    public static function setLabel($file, $label)
    {
        Setting::set(self::key($file), trim((string) $label));

        self::$labels = null;
    }

    /**
     * Full-quality swatch used to build the frame around a map preview.
     * Falls back to the original art when `frames:optimize` has not been run.
     */
    public static function swatchUrl($stored)
    {
        return self::derivativeUrl($stored, 'web');
    }

    /**
     * Small swatch used for picker tiles and admin listings.
     */
    public static function thumbUrl($stored)
    {
        return self::derivativeUrl($stored, 'thumbs');
    }

    private static function derivativeUrl($stored, $variant)
    {
        $file = self::resolve($stored);

        if ($file === null) {
            return null;
        }

        $derivative = $variant . '/' . self::slug($file) . '.jpg';

        if (is_file(public_path('frames/' . $derivative))) {
            return asset('frames/' . $derivative);
        }

        return asset('frames/' . rawurlencode($file));
    }
}
