import {Editor, isActive} from "@tiptap/core";
import Blockquote from "@tiptap/extension-blockquote";
import Bold from "@tiptap/extension-bold";
import BulletList from "@tiptap/extension-bullet-list";
import Code from "@tiptap/extension-code";
import Color from "@tiptap/extension-color";
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
    GridBuilder,
    GridBuilderColumn,
    Youtube,
    Vimeo,
    Details,
    DetailsSummary,
    DetailsContent,
    CustomCodeBlockLowlight,
    Hurdle,
    BubbleMenu,
    FloatingMenu,
    Video,
} from "./extensions";
import {lowlight} from "lowlight/lib/common";
import {randomString, dispatch} from "./utils";
import 'vanilla-colorful/hex-color-picker.js';

let coreExtensions = {
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
    color: [Color],
    details: [Details, DetailsSummary, DetailsContent],
    grid: [Grid, GridColumn],
    'grid-builder': [GridBuilder, GridBuilderColumn],
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
    oembed: [Youtube, Vimeo, Video],
    'ordered-list': [OrderedList],
    small: [Small],
    strike: [Strike],
    subscript: [Subscript],
    superscript: [Superscript],
    table: [Table.configure({resizable: true}), TableHeader, TableCell, TableRow],
    underline: [Underline],
};

let customExtensions = window.TiptapEditorExtensions || {};
let editorExtensions = {...coreExtensions, ...customExtensions};

const localeSwitcher = document.getElementById('activeLocale');
if (localeSwitcher) {
    localeSwitcher.addEventListener('change', () => {
        const localeChange = new CustomEvent('localeChange', { bubbles: true, detail: { locale: localeSwitcher.value } });
        localeSwitcher.dispatchEvent(localeChange);
    });
}

export default function tiptap({
   state,
   statePath,
   tools = [],
   disabled = false,
   locale = 'en',
   floatingMenuTools = [],
}) {
    let editors = window.filamentTiptapEditors || {};

    return {
        id: null,
        tools: tools,
        state: state,
        statePath: statePath,
        fullScreenMode: false,
        updatedAt: Date.now(),
        focused: false,
        locale: locale,
        floatingMenuTools: floatingMenuTools,
        getExtensions(id) {
            const tools = this.tools.map((tool) => {
                if (typeof tool === 'string') {
                    return tool;
                }

                return tool.id;
            })

            let exts = [Document, Text, CustomParagraph, Dropcursor, Gapcursor, HardBreak, History, TextStyle];

            if (tools.length) {

                const keys = Object.keys(editorExtensions);
                let alignments = [];
                let types = ['paragraph'];

                exts.push(BubbleMenu.configure({
                    pluginKey: `defaultBubbleMenu${id}`,
                    element: this.$refs.defaultBubbleMenu,
                    tippyOptions: {
                        duration: [500,0],
                    },
                    shouldShow: ({state, from, to}) => {
                        return ! (
                            from === to ||
                            isActive(state, 'link') ||
                            isActive(state, 'table') ||
                            isActive(state, 'image') ||
                            isActive(state, 'oembed') ||
                            isActive(state, 'vimeo') ||
                            isActive(state, 'youtube') ||
                            isActive(state, 'video')
                        );
                    },
                }))

                if (this.floatingMenuTools.length) {
                    exts.push(FloatingMenu.configure({
                        pluginKey: `defaultFloatingMenu${id}`,
                        element: this.$refs.defaultFloatingMenu,
                        tippyOptions: {
                            duration: [500,0],
                        }
                    }))

                    this.floatingMenuTools.forEach((tool) => {
                        if (! tools.includes(tool)) {
                            tools.push(tool);
                        }
                    });
                }

                tools.forEach((tool) => {
                    if (keys.includes(tool)) {
                        editorExtensions[tool].forEach((e) => {
                            if (['ordered-list', 'bullet-list', 'checked-list'].includes(tool)) {
                                exts.push(e)
                                if (!exts.includes(ListItem)) exts.push(ListItem);
                            } else {
                                if (tool === 'table') {
                                    exts.push(BubbleMenu.configure({
                                        pluginKey: `tableBubbleMenu${id}`,
                                        element: this.$refs.tableBubbleMenu,
                                        tippyOptions: {
                                            duration: [500,0],
                                        },
                                        shouldShow: ({state}) => {
                                            return isActive(state, 'table');
                                        }
                                    }))
                                }

                                if (tool === 'link') {
                                    exts.push(BubbleMenu.configure({
                                        pluginKey: `linkBubbleMenu${id}`,
                                        element: this.$refs.linkBubbleMenu,
                                        tippyOptions: {
                                            duration: [500,0],
                                        },
                                        shouldShow: ({state}) => {
                                            return isActive(state,'link');
                                        }
                                    }))
                                }

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
                if (
                    e.target && (e.target.hasAttribute("data-youtube-video") ||
                    e.target.hasAttribute("data-vimeo-video")) ||
                    e.target.hasAttribute("data-native-video")
                ) {
                    e.target.firstChild.style.pointerEvents = "all";
                }
            });

            window.addEventListener('localeChange', (event) => {
                this.locale = event.detail.locale;
            });

            let sortableEl = this.$el.parentElement.closest("[x-sortable]");
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

            this.$watch('state', (newState) => {
                if (newState === '<p></p>' && newState !== this.editor().getHTML()) {
                    editors[this.id].destroy();
                    this.initEditor(newState);
                }

                if (this.state !== newState) {
                    this.updateEditorContent(newState);
                }
            });

            this.$watch('locale', () => {
                Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                    succeed(({ snapshot, effect }) => {
                        queueMicrotask(() => {
                            editors[this.id].destroy();
                            this.initEditor(this.state);
                        })
                    })
                });
            });
        },
        editor() {
            return editors[this.id];
        },
        initEditor(content) {
            this.id = randomString(8);
            let _this = this;
            editors[this.id] = new Editor({
                element: this.$refs.element,
                extensions: this.getExtensions(this.id),
                editable: ! disabled,
                content: content,
                onUpdate({editor}) {
                    _this.updatedAt = Date.now();
                },
                onSelectionUpdate() {
                    _this.updatedAt = Date.now();
                },
                onBlur() {
                    _this.updatedAt = Date.now();
                    _this.focused = false;
                    _this.state = _this.editor().getHTML();
                },
                onFocus() {
                    _this.updatedAt = Date.now();
                    _this.focused = true;
                },
            });
        },
        updateEditorContent(content) {
            this.editor().commands.setContent(content);
        },
        refreshEditorContent() {
            // Using $nextTick to delay the UI update after the entangled state updates.
            // This matters when the method is triggered as part of a batched request.
            this.$nextTick(() => this.updateEditorContent(this.state));
        },
        insertMedia(media) {
            if (Array.isArray(media)) {
                media.forEach((item) => {
                    this.executeMediaInsert(item);
                });
            } else {
                this.executeMediaInsert(media);
            }
        },
        executeMediaInsert(media = null) {
            if (! media || media?.url === null) {
                return;
            }

            if (media) {
                const src = media?.url || media?.src;
                const imageTypes = ['jpg', 'jpeg', 'svg', 'png', 'webp'];

                const regex = /.*\.([a-zA-Z]*)\??/;
                const match = regex.exec(src);

                if (match !== null && imageTypes.includes(match[1])) {
                    this.editor()
                        .chain()
                        .focus()
                        .setImage({
                            src: src,
                            alt: media?.alt,
                            title: media?.title,
                            width: media?.width,
                            height: media?.height,
                        })
                        .run();
                } else {
                    this.editor().chain().focus().extendMarkRange('link').setLink({ href: src }).insertContent(media?.link_text).run();
                }
            }
        },
        insertVideo(video) {
            if (! video || video.url === null) {
                return;
            }

            let commonOptions = {
                src: video.url,
                width: video.responsive ? video.width * 100 : video.width,
                height: video.responsive ? video.height * 100 : video.height,
                responsive: video.responsive ?? true,
                'data-aspect-width': video.width,
                'data-aspect-height': video.height,
            }

            if (video.url.includes('youtube') || video.url.includes('youtu.be')) {
                this.editor().chain().focus().setYoutubeVideo({
                    ...commonOptions,
                    controls: video.youtube_options.includes('controls'),
                    nocookie: video.youtube_options.includes('nocookie'),
                    start: video.start_at ?? 0,
                }).run();
            } else if (video.url.includes('vimeo')) {
                this.editor().chain().focus().setVimeoVideo({
                    ...commonOptions,
                    autoplay: video.vimeo_options.includes('autoplay'),
                    loop: video.vimeo_options.includes('loop'),
                    title: video.vimeo_options.includes('show_title'),
                    byline: video.vimeo_options.includes('byline'),
                    portrait: video.vimeo_options.includes('portrait'),
                }).run();
            } else {
                this.editor().chain().focus().setVideo({
                    ...commonOptions,
                    autoplay: video.native_options.includes('autoplay'),
                    loop: video.native_options.includes('loop'),
                    controls: video.native_options.includes('controls'),
                }).run();
            }
        },
        insertLink(link) {
            if (link.href === null && link.id === null) {
                return;
            }

            if (link.href === '' && link.id === null) {
                this.unsetLink();
                return;
            }

            this.editor()
                .chain()
                .focus()
                .extendMarkRange('link')
                .setLink({
                    href: link.href,
                    id: link.id ?? null,
                    target: link.target ?? null,
                    hreflang: link.hreflang ?? null,
                    rel: link.rel ?? null,
                    referrerpolicy: link.referrerpolicy ?? null,
                    as_button: link.as_button ?? null,
                    button_theme: link.button_theme ?? null,
                })
                .selectTextblockEnd()
                .run();
        },
        insertSource(source) {
            this.editor().commands.setContent(source, {emitUpdate: true});
        },
        unsetLink() {
            this.editor().chain().focus().extendMarkRange('link').unsetLink().selectTextblockEnd().run();
        },
        insertGridBuilder(grid) {
            let type = 'responsive';
            const asymmetricLeft = parseInt(grid.asymmetric_left) ?? null;
            const asymmetricRight = parseInt(grid.asymmetric_right) ?? null;

            if (grid.fixed) {
                type = 'fixed';
            }

            if (grid.asymmetric) {
                type = 'asymmetric';
            }

            this.editor().chain().focus().insertGridBuilder({
                cols: grid.columns,
                type,
                stackAt: grid.stack_at,
                asymmetricLeft,
                asymmetricRight
            }).run();
        }
    };
}
