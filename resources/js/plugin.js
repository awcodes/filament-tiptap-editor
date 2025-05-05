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
import Paragraph from "@tiptap/extension-paragraph";
import Placeholder from "@tiptap/extension-placeholder";
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
import {BubbleMenu} from "@tiptap/extension-bubble-menu";
import {FloatingMenu} from "@tiptap/extension-floating-menu";
import {
    CheckedList,
    Lead,
    CustomLink,
    CustomImage,
    CustomTextAlign,
    Small,
    Grid,
    GridColumn,
    GridBuilder,
    GridBuilderColumn,
    MergeTag,
    Youtube,
    Vimeo,
    Details,
    DetailsSummary,
    DetailsContent,
    CustomCodeBlockLowlight,
    Hurdle,
    Video,
    TiptapBlock,
    DragAndDropExtension,
    ClassExtension,
    IdExtension,
    StyleExtension,
    CustomMention,
    StatePath,
} from "./extensions";
import { lowlight } from "lowlight/lib/common";
import { HexBase } from 'vanilla-colorful/lib/entrypoints/hex';
import { isEqual } from "lodash";

customElements.define('tiptap-hex-color-picker', HexBase);

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
        const localeChange = new CustomEvent('locale-change', { bubbles: true, detail: { locale: localeSwitcher.value } });
        localeSwitcher.dispatchEvent(localeChange);
    });
}

document.addEventListener('livewire:navigating', () => {
    window.filamentTiptapEditors = {};
})

document.addEventListener("dblclick", function (e) {
    if (
        e.target && (e.target.hasAttribute("data-youtube-video") ||
            e.target.hasAttribute("data-vimeo-video")) ||
        e.target.hasAttribute("data-native-video")
    ) {
        e.target.firstChild.style.pointerEvents = "all";
    }
});

Livewire.on('insertFromAction', (event) => {
    setTimeout(() => {
        const proxyEvent = new CustomEvent('insert-content', { bubble: true, detail: event})
        window.dispatchEvent(proxyEvent);
    }, 100)
})

Livewire.on('insertBlockFromAction', (event) => {
    setTimeout(() => {
        const proxyEvent = new CustomEvent('insert-block', { bubble: true, detail: event})
        window.dispatchEvent(proxyEvent);
    }, 100)
})

Livewire.on('updateBlockFromAction', (event) => {
    setTimeout(() => {
        const proxyEvent = new CustomEvent('update-block', { bubble: true, detail: event})
        window.dispatchEvent(proxyEvent);
    }, 100)
})

export default function tiptap({
   state,
   statePath,
   tools = [],
   disabled = false,
   locale = 'en',
   bubbleMenuTools = [],
   floatingMenuTools = [],
   placeholder = null,
   mergeTags = [],
   customDocument = null,
   nodePlaceholders = [],
   showOnlyCurrentPlaceholder = true,
   debounce = null,
   mentionItems = null,
   emptyMentionItemsMessage = '',
   mentionItemsPlaceholder = null,
   mentionItemsLoading = null,
   maxMentionItems = null,
   mentionTrigger = '@',
   livewireId,
   getMentionItemsUsingEnabled = false,
   getSearchResultsUsing,
   mentionDebounce,
   tippyPlacement = 'auto',
   mentionSearchStrategy,
    linkProtocols = [],
}) {
    let editor = null;

    return {
        id: null,
        modalId: null,
        tools: tools,
        state: state,
        statePath: statePath,
        fullScreenMode: false,
        updatedAt: Date.now(),
        disabled: disabled,
        locale: locale,
        bubbleMenuTools: bubbleMenuTools,
        floatingMenuTools: floatingMenuTools,
        getExtensions() {
            const tools = this.tools.map((tool) => {
                if (typeof tool === 'string') {
                    return tool;
                }

                return tool.id;
            })

            let extensions = [
                customDocument ? Document.extend({
                    content: customDocument
                }) : Document ,
                Text,
                Paragraph,
                Dropcursor,
                Gapcursor,
                HardBreak,
                History,
                TextStyle,
                DragAndDropExtension,
                ClassExtension,
                IdExtension,
                StyleExtension,
                StatePath.configure({
                    statePath: statePath
                }),
                TiptapBlock,
            ];

            if(mentionItems || getMentionItemsUsingEnabled) {
                extensions.push(CustomMention.configure({
                    mentionItems,
                    emptyMentionItemsMessage,
                    mentionItemsPlaceholder,
                    mentionItemsLoading,
                    maxMentionItems,
                    mentionTrigger,
                    livewireId,
                    getMentionItemsUsingEnabled,
                    getSearchResultsUsing,
                    mentionDebounce,
                    mentionSearchStrategy,
                }))
            }

            if ((placeholder || nodePlaceholders) && (!disabled)) {
                extensions.push(
                  Placeholder.configure({
                      showOnlyCurrent: showOnlyCurrentPlaceholder,
                      placeholder: ({ node }) => {
                          const nodeSpecificPlaceholder = nodePlaceholders?.[node.type.name];
                          return nodeSpecificPlaceholder || placeholder || '';
                      },
                  })
                );
            }

            if (tools.length) {
                const keys = Object.keys(editorExtensions);
                let alignments = [];
                let types = ['paragraph'];

                extensions.push(BubbleMenu.configure({
                    element: this.$refs.bubbleMenu,
                    tippyOptions: {
                        duration: [500, 0],
                        maxWidth: 'none',
                        placement: tippyPlacement,
                        theme: 'tiptap-editor-bubble',
                        interactive: true,
                        appendTo: this.$refs.element,
                        zIndex: 10,
                    },
                    shouldShow: ({state, from, to}) => {
                        if (
                            isActive(state, 'oembed') ||
                            isActive(state, 'vimeo') ||
                            isActive(state, 'youtube') ||
                            isActive(state, 'video') ||
                            isActive(state, 'tiptapBlock')
                        ) {
                            return false;
                        }

                        if (
                            isActive(state, 'link') ||
                            isActive(state, 'table')
                        ) {
                            return true;
                        }

                        if (from !== to) {
                            return true;
                        }
                    },
                }))

                if (this.floatingMenuTools.length) {
                    extensions.push(FloatingMenu.configure({
                        element: this.$refs.floatingMenu,
                        tippyOptions: {
                            duration: [500, 0],
                            maxWidth: 'none',
                            theme: 'tiptap-editor-bubble',
                            interactive: true,
                            appendTo: this.$refs.element,
                            zIndex: 10,
                        }
                    }))

                    this.floatingMenuTools.forEach((tool) => {
                        if (!tools.includes(tool)) {
                            tools.push(tool);
                        }
                    });
                }

                if (this.bubbleMenuTools.length) {
                    this.bubbleMenuTools.forEach((tool) => {
                        if (!tools.includes(tool)) {
                            tools.push(tool);
                        }
                    });
                }

                tools.forEach((tool) => {
                    if (keys.includes(tool)) {
                        editorExtensions[tool].forEach((e) => {
                            if (['ordered-list', 'bullet-list', 'checked-list'].includes(tool)) {
                                extensions.push(e)
                                if (!extensions.includes(ListItem)) extensions.push(ListItem);
                            } else if (tool === 'link') {
                                if (linkProtocols?.length) {
                                    extensions.push(e.configure({
                                        protocols: linkProtocols,
                                    }))
                                } else {
                                    extensions.push(e)
                                }
                            } else {
                                extensions.push(e)
                            }
                        })
                    } else {
                        if (['align-left', 'align-right', 'align-center', 'align-justify'].includes(tool)) {
                            if (tool === "align-left") alignments.push('start');
                            if (tool === "align-center") alignments.push('center');
                            if (tool === "align-right") alignments.push('end');
                            if (tool === "align-justify") alignments.push('justify');
                            if (tools.includes("heading")) types.push('heading');
                            let hasTextAlign = extensions.find((item) => item.name === 'textAlign');
                            if (typeof hasTextAlign === "undefined") extensions.push(CustomTextAlign.configure({
                                types,
                                alignments
                            }));
                        }
                    }
                })
            }

            if (mergeTags?.length) {
                extensions.push(MergeTag.configure({
                    mergeTags,
                }))
            }

            return extensions;
        },
        init: function() {
            let existing = this.$refs.element.querySelector('.tiptap');
            if (existing) {
                existing.remove();
                editor = null;
            }

            this.initEditor(this.state);

            let sortableEl = this.$el.parentElement.closest("[x-sortable]");
            if (sortableEl) {
                window.Sortable.utils.on(sortableEl, "start", () => {
                    sortableEl.classList.add('sorting')
                });

                window.Sortable.utils.on(sortableEl, "end", () => {
                    sortableEl.classList.remove('sorting')
                });
            }

            this.$watch('state', (newState, oldState) => {
                if (typeof newState !== "undefined") {
                    if (!isEqual(oldState, Alpine.raw(newState))) {
                        this.updateEditorContent(newState)
                    }
                }
            });
        },
        initEditor(content) {
            if (! this.$el.querySelector('.tiptap')) {
                const _this = this;
                editor = new Editor({
                    element: _this.$refs.element,
                    extensions: _this.getExtensions(),
                    editable: !_this.disabled,
                    content: content,
                    editorProps: {
                        handlePaste(view, event, slice) {
                            slice.content.descendants(node => {
                                if (node.type.name === 'tiptapBlock') {
                                    node.attrs.statePath = _this.statePath
                                    node.attrs.data = JSON.parse(node.attrs.data)
                                }
                            });
                        }
                    },
                    onUpdate({editor}) {
                        _this.updatedAt = Date.now();
                        clearTimeout(_this.timeOut);
                        _this.timeOut = setTimeout(function(){
                            _this.state = editor.isEmpty ? null : editor.getJSON();
                        },debounce ?? 0);
                    },
                    onSelectionUpdate() {
                        _this.updatedAt = Date.now();
                    },
                    onBlur() {
                        _this.updatedAt = Date.now();
                    },
                    onFocus() {
                        _this.updatedAt = Date.now();
                    },
                });
            }
        },
        isActive(type, opts = {}) {
            return editor.isActive(type, opts)
        },
        editor() {
            return editor;
        },
        blur() {
            const tippy = this.$el.querySelectorAll('[data-tippy-content]')
            if (tippy) {
                tippy.forEach((item) => item.destroy())
            }

            this.updatedAt = Date.now()
        },
        updateEditorContent(content) {
            if (editor.isEditable) {
                const {from, to} = editor.state.selection;
                editor.commands.setContent(content, true);
                editor.chain().focus().setTextSelection({from, to}).run();
            }
        },
        refreshEditorContent() {
            this.$nextTick(() => this.updateEditorContent(this.state));
        },
        updateLocale(event) {
            this.locale = event.detail.locale
        },
        insertContent(event) {
            if (event.detail.statePath !== this.statePath) return

            switch (event.detail.type) {
                case 'media':
                    this.insertMedia(event);
                    return;
                case 'video':
                    this.insertVideo(event);
                    return;
                case 'link':
                    this.insertLink(event);
                    return;
                case 'source':
                    this.insertSource(event);
                    return;
                case 'grid':
                    this.insertGridBuilder(event);
                    return;
                default:
                    return;
            }
        },
        insertMedia(event) {
            if (Array.isArray(event.detail.media)) {
                event.detail.media.forEach((item) => {
                    this.executeMediaInsert(item);
                });
            } else {
                this.executeMediaInsert(event.detail.media);
            }
        },
        executeMediaInsert(media = null) {
            if (!media || media?.url === null) {
                return;
            }

            if (media) {
                const src = media?.url || media?.src;
                const imageTypes = ['jpg', 'jpeg', 'svg', 'png', 'webp', 'gif', 'avif', 'jxl', 'heic'];

                const regex = /.*\.([a-zA-Z]*)\??/;
                const match = regex.exec(src.toLowerCase());

                if (match !== null && imageTypes.includes(match[1])) {
                    editor
                        .chain()
                        .focus()
                        .setImage({
                            src: src,
                            alt: media?.alt,
                            title: media?.title,
                            width: media?.width,
                            height: media?.height,
                            lazy: media?.lazy,
                            srcset: media?.srcset,
                            sizes: media?.sizes,
                            media: media?.media,
                        })
                        .run();
                } else {
                    editor.chain().focus().extendMarkRange('link').setLink({href: src}).insertContent(media?.link_text).run();
                }
            }
        },
        insertVideo(event) {
            let video = event.detail.video;

            if (!video || video.url === null) {
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
                editor.chain().focus().setYoutubeVideo({
                    ...commonOptions,
                    controls: video.youtube_options.includes('controls'),
                    nocookie: video.youtube_options.includes('nocookie'),
                    start: video.start_at ?? 0,
                }).run();
            } else if (video.url.includes('vimeo')) {
                editor.chain().focus().setVimeoVideo({
                    ...commonOptions,
                    autoplay: video.vimeo_options.includes('autoplay'),
                    loop: video.vimeo_options.includes('loop'),
                    title: video.vimeo_options.includes('show_title'),
                    byline: video.vimeo_options.includes('byline'),
                    portrait: video.vimeo_options.includes('portrait'),
                }).run();
            } else {
                editor.chain().focus().setVideo({
                    ...commonOptions,
                    autoplay: video.native_options.includes('autoplay'),
                    loop: video.native_options.includes('loop'),
                    controls: video.native_options.includes('controls'),
                }).run();
            }
        },
        insertLink(event) {
            let link = event.detail;

            if (link.href === null && link.id === null) {
                return;
            }

            if (link.href === '' && link.id === null) {
                this.unsetLink();

                return;
            }

            editor
                .chain()
                .focus()
                .setTextSelection({from: link.coordinates[0].$from.pos, to: link.coordinates[0].$to.pos})
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
        unsetLink() {
            editor.chain().focus().extendMarkRange('link').unsetLink().selectTextblockEnd().run();
        },
        insertSource(event) {
            this.updateEditorContent(event.detail.source);
        },
        insertGridBuilder(event) {
            let grid = event.detail.data;
            let type = 'responsive';
            const asymmetricLeft = parseInt(grid.asymmetric_left) ?? null;
            const asymmetricRight = parseInt(grid.asymmetric_right) ?? null;

            if (grid.fixed) {
                type = 'fixed';
            }

            if (grid.asymmetric) {
                type = 'asymmetric';
            }

            editor.chain().focus().insertGridBuilder({
                cols: grid.columns,
                type,
                stackAt: grid.stack_at,
                asymmetricLeft,
                asymmetricRight
            }).run();
        },
        insertBlock(event) {
            if (event.detail.statePath !== this.statePath) return

            editor.commands.insertBlock({
                type: event.detail.type,
                statePath: event.detail.statePath,
                data: event.detail.data,
                preview: event.detail.preview,
                label: event.detail.label,
                coordinates: event.detail.coordinates,
            });

            if (!editor.isFocused) {
                editor.commands.focus();
            }
        },
        insertMergeTag(event) {
            editor.commands.insertMergeTag({
                tag: event.detail.tag,
                coordinates: event.detail.coordinates,
            });

            if (!editor.isFocused) {
                editor.commands.focus();
            }
        },
        openBlockSettings(event) {
            if (event.detail.statePath !== this.statePath) return

            this.$wire.dispatchFormEvent("tiptap::updateBlock", this.statePath, event.detail);
        },
        updateBlock(event) {
            if (event.detail.statePath !== this.statePath) return

            editor.commands.updateBlock({
                type: event.detail.type,
                statePath: event.detail.statePath,
                data: event.detail.data,
                preview: event.detail.preview,
                label: event.detail.label,
                coordinates: event.detail.coordinates,
            });

            if (!editor.isFocused) {
                editor.commands.focus();
            }
        },
        deleteBlock(event) {
            if (event.detail.statePath !== this.statePath) return
            editor.commands.removeBlock();
        }
    }
}
