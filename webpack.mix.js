const mix = require("laravel-mix");

mix.disableNotifications();

mix
  .setPublicPath("./resources/dist")
  .postCss("./resources/css/filament-tiptap-editor.css", "filament-tiptap-editor.css", [require("tailwindcss/nesting")(), require("tailwindcss")("./tailwind.config.js")])
  .js("./resources/js/filament-tiptap-editor.js", "filament-tiptap-editor.js")
  .options({
    processCssUrls: false,
  });
