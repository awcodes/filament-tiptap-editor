import { Editor } from "@tiptap/core";
import Document from "@tiptap/extension-document";
import Blockquote from "@tiptap/extension-blockquote";
import HardBreak from "@tiptap/extension-hard-break";
import Text from "@tiptap/extension-text";
import BulletList from "@tiptap/extension-bullet-list";
import ListItem from "@tiptap/extension-list-item";
import Heading from "@tiptap/extension-heading";
import HorizontalRule from "@tiptap/extension-horizontal-rule";
import OrderedList from "@tiptap/extension-ordered-list";
import Superscript from "@tiptap/extension-superscript";
import Subscript from "@tiptap/extension-subscript";
import Table from "@tiptap/extension-table";
import TableRow from "@tiptap/extension-table-row";
import TableCell from "@tiptap/extension-table-cell";
import TableHeader from "@tiptap/extension-table-header";
import Italic from "@tiptap/extension-italic";
import Bold from "@tiptap/extension-bold";
import Strike from "@tiptap/extension-strike";
import Underline from "@tiptap/extension-underline";
import History from "@tiptap/extension-history";
import Dropcursor from "@tiptap/extension-dropcursor";
import Gapcursor from "@tiptap/extension-gapcursor";
import { Color } from "@tiptap/extension-color";
import TextStyle from "@tiptap/extension-text-style";
import Code from "@tiptap/extension-code";
import CodeBlockLowlight from "@tiptap/extension-code-block-lowlight";
import TextAlign from "@tiptap/extension-text-align";
import { CheckedList, Lead, CustomLink, CustomImage, CustomParagraph } from "./extensions";
import { lowlight } from "lowlight/lib/common";

function randomString(length) {
  var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz".split("");

  if (!length) {
    length = Math.floor(Math.random() * chars.length);
  }

  var str = "";
  for (var i = 0; i < length; i++) {
    str += chars[Math.floor(Math.random() * chars.length)];
  }
  return str;
}

document.addEventListener("alpine:init", () => {
  let editors = window.filamentTiptapEditors || {};

  Alpine.data("tiptap", ({ state, buttons = "", blocks = [] }) => {
    return {
      id: null,
      buttons: buttons.split(","),
      blocks: blocks,
      json: [],
      html: "",
      state: state,
      fullScreenMode: false,
      updatedAt: Date.now(),
      getExtensions() {
        let exts = [Document, Text, CustomParagraph, Dropcursor, Gapcursor, HardBreak];

        if (this.buttons.includes("link"))
          exts.push(
            CustomLink.configure({
              openOnClick: false,
              autolink: false,
              HTMLAttributes: {
                rel: null,
                hreflang: null,
              },
            })
          );
        if (this.buttons.includes("blockquote")) exts.push(Blockquote);
        if (this.buttons.includes("bold")) exts.push(Bold);
        if (this.buttons.includes("italic")) exts.push(Italic);
        if (this.buttons.includes("strike")) exts.push(Strike);
        if (this.buttons.includes("underline")) exts.push(Underline);
        if (this.buttons.includes("align")) exts.push(TextAlign.configure({ types: ["heading", "paragraph"] }));
        if (this.buttons.includes("subscript")) exts.push(Subscript);
        if (this.buttons.includes("superscript")) exts.push(Superscript);
        if (this.buttons.includes("media")) exts.push(CustomImage.configure({ inline: true }));
        if (this.buttons.includes("hr")) exts.push(HorizontalRule);
        if (this.buttons.includes("lead")) exts.push(Lead);

        if (this.buttons.includes("code")) exts.push(Code);
        if (this.buttons.includes("codeblock"))
          exts.push(
            CodeBlockLowlight.configure({
              lowlight,
              HTMLAttributes: {
                class: "hljs",
              },
            })
          );

        if (this.buttons.includes("color")) {
          exts.push(Color);
          exts.push(TextStyle);
        }

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
      init() {
        this.id = randomString(8);
        let _this = this;
        editors[this.id] = new Editor({
          element: this.$refs.element,
          extensions: this.getExtensions(),
          content: state?.initialValue === "<p></p>" ? "" : state?.initialValue,
          onCreate({ editor }) {
            _this.html = editor.getHTML();
            _this.json = editor.getJSON()?.content;
            _this.state = _this.html = editor.getHTML() === "<p></p>" ? "" : editor.getHTML();
            _this.$refs.textarea.value = _this.state;
            _this.updatedAt = Date.now();
          },
          onUpdate({ editor }) {
            _this.state = editor.getHTML();
            _this.html = editor.getHTML();
            _this.json = editor.getJSON()?.content;
            _this.$refs.textarea.value = _this.html === "<p></p>" ? "" : _this.html;
            _this.$refs.textarea.dispatchEvent(new Event("input"));
            _this.updatedAt = Date.now();
          },
          onSelectionUpdate({ editor }) {
            _this.updatedAt = Date.now();
          },
          onBlur({ editor }) {
            _this.$refs.textarea.dispatchEvent(new Event("change"));
            _this.updatedAt = Date.now();
          },
        });

        window.filamentTiptapEditors = editors;
      },
      editor() {
        return editors[this.id];
      },
      isActive(type, opts = {}, updatedAt) {
        return this.editor().isActive(type, opts);
      },
    };
  });
});
