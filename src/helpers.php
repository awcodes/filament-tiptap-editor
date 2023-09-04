<?php

if (! function_exists('tiptap_converter')) {
    function tiptap_converter(): FilamentTiptapEditor\TiptapConverter
    {
        return app(\FilamentTiptapEditor\TiptapConverter::class);
    }
}
