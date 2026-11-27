<?php

namespace FilamentTiptapEditor\Extensions\Concerns;

trait HasSafeEmbedSource
{
    /**
     * Only http(s) and scheme-less URLs may be used as an iframe source; anything else
     * (e.g. `javascript:` or `data:`) would execute in the context of the rendering page.
     */
    public static function sanitizeEmbedSource(mixed $src): ?string
    {
        if (! is_string($src) || $src === '') {
            return null;
        }

        // Browsers ignore ASCII whitespace and control characters when resolving a URL scheme.
        $normalized = preg_replace('/[\x00-\x20\x7F]+/', '', $src);

        if (preg_match('/^([a-z][a-z0-9+.\-]*):/i', $normalized, $matches) && ! in_array(strtolower($matches[1]), ['http', 'https'], true)) {
            return null;
        }

        return $src;
    }
}
