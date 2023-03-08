import {Editor} from "@tiptap/core";
import Blockquote from "@tiptap/extension-blockquote";
import Bold from "@tiptap/extension-bold";
import BulletList from "@tiptap/extension-bullet-list";
import Code from "@tiptap/extension-code";
import {Color} from "@tiptap/extension-color";
import Document from "@tiptap/extension-document";
import Dropcursor from "@tiptap/extension-dropcursor";
import Gapcursor from "@tiptap/extension-gapcursor";
import HardBreak from "@tiptap/extension-hard-break";
import Heading from "@tiptap/extension-heading";
import History from "@tiptap/extension-history";
import HorizontalRule from "@tiptap/extension-horizontal-rule";
import Italic from "@tiptap/extension-italic";
import ListItem from "@tiptap/extension-list-item";
import OrderedList from "@tiptap/extension-ordered-list";
import Strike from "@tiptap/extension-strike";
import Subscript from "@tiptap/extension-subscript";
import Superscript from "@tiptap/extension-superscript";
import Table from "@tiptap/extension-table";
import TableCell from "@tiptap/extension-table-cell";
import TableHeader from "@tiptap/extension-table-header";
import TableRow from "@tiptap/extension-table-row";
import Text from "@tiptap/extension-text";
import TextStyle from "@tiptap/extension-text-style";
import Underline from "@tiptap/extension-underline";
import Highlight from "@tiptap/extension-highlight";
import {
    CheckedList,
    Lead,
    CustomLink,
    CustomImage,
    CustomParagraph,
    CustomTextAlign,
    Small,
    Grid,
    GridColumn,
    Youtube,
    Vimeo,
    Details,
    DetailsSummary,
    DetailsContent,
    CustomCodeBlockLowlight,
    Hurdle
} from "./extensions";
import {lowlight} from "lowlight/lib/common";
import {randomString} from "./utils";

let editorExtensions = {
    blockquote: [Blockquote],
    bold: [Bold],
    'bullet-list': [BulletList],
    'checked-list': [CheckedList],
    code: [Code],
    'code-block': [CustomCodeBlockLowlight.configure({
        lowlight,
        HTMLAttributes: {
            class: "hljs",
        },
    })],
    color: [Color, TextStyle],
    details: [Details, DetailsSummary, DetailsContent],
    grid: [Grid, GridColumn],
    heading: [Heading.configure({levels: [1, 2, 3, 4, 5, 6]})],
    highlight: [Highlight],
    hr: [HorizontalRule],
    hurdle: [Hurdle],
    italic: [Italic],
    lead: [Lead],
    link: [CustomLink.configure({
        openOnClick: false,
        autolink: false,
        HTMLAttributes: {
            rel: null,
            hreflang: null,
            class: null,
        },
    })],
    media: [CustomImage.configure({inline: true})],
    oembed: [Youtube, Vimeo],
    'ordered-list': [OrderedList],
    small: [Small],
    strike: [Strike],
    subscript: [Subscript],
    superscript: [Superscript],
    table: [Table.configure({resizable: true}), TableHeader, TableCell, TableRow],
    underline: [Underline],
};

let localeChanged = false;

document.addEventListener("alpine:init", () => {
    let editors = window.filamentTiptapEditors || {};

    Alpine.data("tiptap", ({
        state,
        statePath,
        tools = [],
        output = 'html',
        disabled = false,
    }) => ({
        id: null,
        tools: tools,
        state: state,
        statePath: statePath,
        output: output,
        fullScreenMode: false,
        updatedAt: Date.now(),
        focused: false,
        currentLocale: 'en',
        getExtensions() {
            const tools = this.tools.map((tool) => {
                if (typeof tool === 'string') {
                    return tool;
                }

                return tool.id;
            })

            let exts = [Document, Text, CustomParagraph, Dropcursor, Gapcursor, HardBreak, History];

            if (tools.length) {

                const keys = Object.keys(editorExtensions);
                let alignments = [];
                let types = ['paragraph'];

                tools.forEach((tool) => {
                    if (keys.includes(tool)) {
                        editorExtensions[tool].forEach((e) => {
                            if (['ordered-list', 'bullet-list', 'checked-list'].includes(tool)) {
                                exts.push(e)
                                if (!exts.includes(ListItem)) exts.push(ListItem);
                            } else {
                                exts.push(e)
                            }
                        })
                    } else {
                        if (['align-left', 'align-right', 'align-center', 'align-justify'].includes(tool)) {
                            if (tool === "align-left") alignments.push('start');
                            if (tool === "align-center") alignments.push('center');
                            if (tool === "align-right") alignments.push('end');
                            if (tool === "align-justify") alignments.push('justify');
                            if (tools.includes("heading")) types.push('heading');
                            let hasTextAlign = exts.find((item) => item.name === 'textAlign');
                            if (typeof hasTextAlign === "undefined") exts.push(CustomTextAlign.configure({types, alignments}));
                        }
                    }
                })
            }

            return exts;
        },
        init() {
            this.initEditor(state.initialValue);

            window.filamentTiptapEditors = editors;

            document.addEventListener("dblclick", function (e) {
                if (e.target && (e.target.hasAttribute("data-youtube-video") || e.target.hasAttribute("data-vimeo-video"))) {
                    e.target.firstChild.style.pointerEvents = "all";
                }
            });

            let sortableEl = this.$el.parentElement.closest("[wire\\:sortable]");
            if (sortableEl) {
                window.Sortable.utils.on(sortableEl, "start", () => {
                    Object.values(editors).forEach(function (editor) {
                        editor.setEditable(false);
                    });
                });

                window.Sortable.utils.on(sortableEl, "end", () => {
                    Object.values(editors).forEach(function (editor) {
                        editor.setEditable(true);
                    });
                });
            }

            const localeSwitcher = document.getElementById('activeLocale');
            if (localeSwitcher) {
                localeSwitcher.addEventListener('change', () => {
                    localeChanged = true;
                });
            }

            this.$watch('state', (newState) => {
                if (this.state !== newState) {
                    this.editor().commands.setContent(newState);
                }

                if (localeChanged) {
                    editors[this.id].destroy();
                    this.initEditor(newState)
                    localeChanged = false;
                }
            });
        },
        editor() {
            return editors[this.id];
        },
        isActive(type, opts = {}) {
            return this.editor().isActive(type, opts);
        },
        getFormattedContent() {
            switch (this.output) {
                case 'json':
                    return this.editor().getJSON();
                case 'text':
                    return this.editor().getText();
                default:
                    return this.editor().getHTML();
            }
        },
        initEditor(content) {
            this.id = randomString(8);
            let _this = this;
            editors[this.id] = new Editor({
                element: this.$refs.element,
                extensions: this.getExtensions(),
                editable: ! disabled,
                content: content,
                onUpdate({editor}) {
                    setTimeout(() => {
                        editor.chain().focus()
                    }, 500);
                    _this.$refs.textarea.dispatchEvent(new Event("input"));
                },
                onSelectionUpdate() {
                    _this.$refs.textarea.dispatchEvent(new Event("input"));
                },
                onBlur() {
                    _this.focused = false;
                    _this.state = _this.getFormattedContent();
                    _this.$wire.set(_this.statePath, _this.getFormattedContent());
                    _this.updatedAt = Date.now();
                    _this.$refs.textarea.dispatchEvent(new Event("change"));
                },
                onFocus() {
                    _this.focused = true;
                    _this.$refs.textarea.dispatchEvent(new Event("input"));
                },
            });
        },
    }));
});
