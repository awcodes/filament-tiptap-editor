import { Editor } from "@tiptap/core";
import Document from "@tiptap/extension-document";
import Blockquote from "@tiptap/extension-blockquote";
import HardBreak from "@tiptap/extension-hard-break";
import Text from "@tiptap/extension-text";
import Paragraph from "@tiptap/extension-paragraph";
import BulletList from "@tiptap/extension-bullet-list";
import ListItem from "@tiptap/extension-list-item";
import Heading from "@tiptap/extension-heading";
import HorizontalRule from "@tiptap/extension-horizontal-rule";
import OrderedList from "@tiptap/extension-ordered-list";
import Link from "@tiptap/extension-link";
import Superscript from "@tiptap/extension-superscript";
import Subscript from "@tiptap/extension-subscript";
import Table from "@tiptap/extension-table";
import TableRow from "@tiptap/extension-table-row";
import TableCell from "@tiptap/extension-table-cell";
import TableHeader from "@tiptap/extension-table-header";
import Image from "@tiptap/extension-image";
import Italic from "@tiptap/extension-italic";
import Bold from "@tiptap/extension-bold";
import Strike from "@tiptap/extension-strike";
import Underline from "@tiptap/extension-underline";
import History from "@tiptap/extension-history";
import Dropcursor from "@tiptap/extension-dropcursor";
import Gapcursor from "@tiptap/extension-gapcursor";
import { CheckedList, Lead } from "./extensions";

document.addEventListener("alpine:init", () => {
  Alpine.data("tiptap", ({ state, buttons = "", blocks = [] }) => {
    let editor;

    return {
      buttons: buttons.split(","),
      blocks: blocks,
      json: [],
      html: "",
      state: state,
      fullScreenMode: false,
      updatedAt: Date.now(),
      getExtensions() {
        let exts = [Document, Text, Paragraph, Dropcursor, Gapcursor, HardBreak];

        if (this.buttons.includes("link")) exts.push(Link);
        if (this.buttons.includes("blockquote")) exts.push(Blockquote);
        if (this.buttons.includes("bold")) exts.push(Bold);
        if (this.buttons.includes("italic")) exts.push(Italic);
        if (this.buttons.includes("strike")) exts.push(Strike);
        if (this.buttons.includes("underline")) exts.push(Underline);
        if (this.buttons.includes("subscript")) exts.push(Subscript);
        if (this.buttons.includes("superscript")) exts.push(Superscript);
        if (this.buttons.includes("media")) exts.push(Image);
        if (this.buttons.includes("hr")) exts.push(HorizontalRule);
        if (this.buttons.includes("lead")) exts.push(Lead);

        if (this.buttons.includes("orderedList") || this.buttons.includes("bulletList") || this.buttons.includes("checkedList")) {
          if (this.buttons.includes("orderedList")) exts.push(OrderedList);
          if (this.buttons.includes("bulletList")) exts.push(BulletList);
          if (this.buttons.includes("checkedList")) exts.push(CheckedList);
          exts.push(ListItem);
        }

        if (this.buttons.includes("undo") || this.buttons.includes("redo")) {
          exts.push(History);
        }

        if (this.buttons.includes("table")) {
          exts.push(Table.configure({ resizable: true }), TableHeader, TableCell, TableRow);
        }

        if (this.buttons.includes("h1") || this.buttons.includes("h2") || this.buttons.includes("h3") || this.buttons.includes("h4") || this.buttons.includes("h5") || this.buttons.includes("h6")) {
          let levels = [];
          if (this.buttons.includes("h1")) levels.push(1);
          if (this.buttons.includes("h2")) levels.push(2);
          if (this.buttons.includes("h3")) levels.push(3);
          if (this.buttons.includes("h4")) levels.push(4);
          if (this.buttons.includes("h5")) levels.push(5);
          if (this.buttons.includes("h6")) levels.push(6);
          exts.push(Heading.configure({ levels }));
        }

        return exts;
      },
      isActive(type, opts = {}, updatedAt) {
        return editor.isActive(type, opts);
      },
      toggleBold() {
        editor.chain().focus().toggleBold().run();
      },
      toggleItalic() {
        editor.chain().focus().toggleItalic().run();
      },
      toggleStrike() {
        editor.chain().focus().toggleStrike().run();
      },
      toggleUnderline() {
        editor.chain().focus().toggleUnderline().run();
      },
      toggleHeading(level) {
        editor.chain().focus().toggleHeading({ level }).run();
      },
      toggleLead() {
        editor.chain().focus().toggleLead().run();
      },
      toggleList(type) {
        if (type == "ol") {
          editor.chain().focus().toggleOrderedList().run();
        } else {
          editor.chain().focus().toggleBulletList().run();
        }
      },
      toggleCheckedList() {
        editor.chain().focus().toggleCheckedList().run();
      },
      toggleBlockquote() {
        editor.chain().focus().toggleBlockquote().run();
      },
      setHorizontalRule() {
        editor.chain().focus().setHorizontalRule().run();
      },
      undo() {
        editor.chain().focus().undo().run();
      },
      redo() {
        editor.chain().focus().redo().run();
      },
      toggleSuperscript() {
        editor.chain().focus().toggleSuperscript().run();
      },
      toggleSubscript() {
        editor.chain().focus().toggleSubscript().run();
      },
      tables: {
        insertTable(config = { rows: 3, cols: 3, withHeaderRow: true }) {
          editor.chain().focus().insertTable(config).run();
        },
        addColumnBefore() {
          editor.chain().focus().addColumnBefore().run();
        },
        addColumnAfter() {
          editor.chain().focus().addColumnAfter().run();
        },
        deleteColumn() {
          editor.chain().focus().deleteColumn().run();
        },
        addRowBefore() {
          editor.chain().focus().addRowBefore().run();
        },
        addRowAfter() {
          editor.chain().focus().addRowAfter().run();
        },
        deleteRow() {
          editor.chain().focus().deleteRow().run();
        },
        deleteTable() {
          editor.chain().focus().deleteTable().run();
        },
        mergeCells() {
          editor.chain().focus().mergeCells().run();
        },
        splitCell() {
          editor.chain().focus().splitCell().run();
        },
        toggleHeaderColumn() {
          editor.chain().focus().toggleHeaderColumn().run();
        },
        toggleHeaderRow() {
          editor.chain().focus().toggleHeaderRow().run();
        },
        toggleHeaderCell() {
          editor.chain().focus().toggleHeaderCell().run();
        },
        mergeOrSplit() {
          editor.chain().focus().mergeOrSplit().run();
        },
        setCellAttribute(attribute = "colspan", value = 2) {
          editor.chain().focus().setCellAttribute(attribute, value).run();
        },
        fixTables() {
          editor.chain().focus().fixTables().run();
        },
        goToNextCell() {
          editor.chain().focus().goToNextCell().run();
        },
        goToPreviousCell() {
          editor.chain().focus().goToPreviousCell().run();
        },
      },
      insertLink(link) {
        if (link.url === null) {
          return;
        }

        if (link.url === "") {
          editor.chain().focus().extendMarkRange("link").unsetLink().run();
          return;
        }

        editor
          .chain()
          .focus()
          .extendMarkRange("link")
          .setLink({ href: link.url, target: link.target ?? null })
          .run();
      },
      insertMedia(media) {
        const src = media?.url || media.src;
        editor
          .chain()
          .focus()
          .setImage({
            src: src,
            alt: media?.alt,
            title: media?.title,
          })
          .run();
      },
      init() {
        const _this = this;
        editor = new Editor({
          element: this.$refs.element,
          extensions: this.getExtensions(),
          content: state.initialValue,
          onCreate({ editor }) {
            _this.updatedAt = Date.now();
            _this.html = editor.getHTML();
            _this.json = editor.getJSON()?.content;
          },
          onUpdate({ editor }) {
            _this.updatedAt = Date.now();
            _this.state = editor.getHTML();
            _this.html = editor.getHTML();
            _this.json = editor.getJSON()?.content;
          },
          onSelectionUpdate({ editor }) {
            _this.updatedAt = Date.now();
          },
        });
      },
    };
  });
});
