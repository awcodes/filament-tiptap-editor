<?php

namespace FilamentTiptapEditor\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class InvalidOutputFormatException extends Exception
{
    public function __construct()
    {
        $message = 'Invalid tiptap output format. Only html, json or text format are available.';

        Log::error($message);
        $this->message = $message;
    }
}
