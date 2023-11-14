<div>
    <script src="https://unpkg.com/shiki"></script>
    <script>
        const raw = document.getElementById('source_code_editor').innerHTML

        console.log(raw)

        shiki
            .getHighlighter({
                theme: 'nord',
                langs: ['html'],
            })
            .then(highlighter => {
                document.getElementById('source_code_editor').innerHTML = highlighter.codeToHtml(raw, {lang: 'js'})
            })
    </script>
</div>