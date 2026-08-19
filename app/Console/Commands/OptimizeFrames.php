<?php

namespace App\Console\Commands;

use App\Support\Frames;
use Illuminate\Console\Command;

/**
 * Frame art is supplied as large SVG wrappers around embedded photographs
 * (some over 6MB). Serving those directly to the frame picker would push ~19MB
 * at the browser, so this command renders a web-sized and a thumbnail JPEG for
 * each one into public/frames/web and public/frames/thumbs.
 *
 * Re-run it whenever frame art is added or replaced.
 */
class OptimizeFrames extends Command
{
    protected $signature = 'frames:optimize {--force : Rebuild derivatives that already exist}';

    protected $description = 'Generate web-sized and thumbnail versions of the frame art in public/frames';

    const WEB_MAX = 1400;
    const THUMB_MAX = 240;

    // Fraction trimmed from each edge of the source photograph.
    const EDGE_CROP = 0.02;

    public function handle()
    {
        if (!function_exists('imagecreatefromstring')) {
            $this->error('The GD extension is required to generate frame derivatives.');
            return 1;
        }

        $files = Frames::files();

        if (empty($files)) {
            $this->warn('No frame art found in public/frames.');
            return 0;
        }

        foreach (['web', 'thumbs'] as $dir) {
            $path = public_path('frames/' . $dir);
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }

        foreach ($files as $file) {
            $slug = Frames::slug($file);
            $webPath = public_path('frames/web/' . $slug . '.jpg');
            $thumbPath = public_path('frames/thumbs/' . $slug . '.jpg');

            if (!$this->option('force') && is_file($webPath) && is_file($thumbPath)) {
                $this->line("  skip  {$file}");
                continue;
            }

            $raw = $this->rasterize(public_path('frames/' . $file));

            if ($raw === null) {
                $this->warn("  fail  {$file} (could not read image data)");
                continue;
            }

            $source = @imagecreatefromstring($raw);

            if ($source === false) {
                $this->warn("  fail  {$file} (unsupported image data)");
                continue;
            }

            $this->resizeTo($source, $webPath, self::WEB_MAX, 82);
            $this->resizeTo($source, $thumbPath, self::THUMB_MAX, 78);
            imagedestroy($source);

            $this->info(sprintf(
                '  ok    %-18s -> web %sKB, thumb %sKB',
                $file,
                round(filesize($webPath) / 1024),
                round(filesize($thumbPath) / 1024)
            ));
        }

        return 0;
    }

    /**
     * Return raw raster bytes for a frame file. The supplied SVGs are a single
     * <image> element wrapping a base64 payload, so the payload is pulled out
     * rather than rendered.
     */
    private function rasterize($path)
    {
        $contents = @file_get_contents($path);

        if ($contents === false) {
            return null;
        }

        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'svg') {
            return $contents;
        }

        // Scanned by hand rather than by regex: these payloads run to several
        // megabytes and blow PCRE's backtrack limit.
        $start = strpos($contents, 'base64,');

        if ($start === false) {
            return null;
        }

        $start += strlen('base64,');
        $end = strcspn($contents, "\"'>", $start);

        return base64_decode(substr($contents, $start, $end));
    }

    private function resizeTo($source, $destination, $max, $quality)
    {
        // The swatches are photographs, so a sliver of the surface behind the
        // moulding survives at each edge. Left in, it draws a pale seam along
        // the outside of every rail and mitre, so trim it before scaling.
        $inset = self::EDGE_CROP;
        $srcX = (int) round(imagesx($source) * $inset);
        $srcY = (int) round(imagesy($source) * $inset);
        $width = imagesx($source) - 2 * $srcX;
        $height = imagesy($source) - 2 * $srcY;
        $scale = min(1, $max / max($width, $height));

        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, imagecolorallocate($canvas, 255, 255, 255));
        imagecopyresampled($canvas, $source, 0, 0, $srcX, $srcY, $newWidth, $newHeight, $width, $height);
        imagejpeg($canvas, $destination, $quality);
        imagedestroy($canvas);
    }
}
