const esbuild = require('esbuild');
const shouldWatch = process.argv.includes('--watch');

esbuild.build({
    define: {
        'process.env.NODE_ENV': shouldWatch
            ? `'production'`
            : `'development'`,
    },
    entryPoints: [`resources/js/plugin.js`],
    outfile: `resources/dist/filament-tiptap-editor.js`,
    bundle: true,
    platform: 'neutral',
    mainFields: ['module', 'main'],
    watch: shouldWatch,
    minifySyntax: true,
    minifyWhitespace: true,
}).catch(() => process.exit(1));