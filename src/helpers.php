<?php

if (! function_exists('tiptap_converter')) {
    function tiptap_converter(): FilamentTiptapEditor\TiptapConverter
    {
        return app('tiptap-converter');
    }
}
