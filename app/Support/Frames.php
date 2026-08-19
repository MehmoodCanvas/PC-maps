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
     * Display name for a single frame file.
     */
    public static function label($file)
    {
        if (empty($file) || $file === 'none') {
            return 'No Frame';
        }

        return self::labels()[$file] ?? $file;
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
    public static function swatchUrl($file)
    {
        return self::derivativeUrl($file, 'web');
    }

    /**
     * Small swatch used for picker tiles and admin listings.
     */
    public static function thumbUrl($file)
    {
        return self::derivativeUrl($file, 'thumbs');
    }

    private static function derivativeUrl($file, $variant)
    {
        if (empty($file) || $file === 'none' || !in_array($file, self::files(), true)) {
            return null;
        }

        $derivative = $variant . '/' . self::slug($file) . '.jpg';

        if (is_file(public_path('frames/' . $derivative))) {
            return url('frames/' . $derivative);
        }

        return url('frames/' . rawurlencode($file));
    }
}
