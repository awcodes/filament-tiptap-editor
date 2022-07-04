import { Editor } from "@tiptap/core";
import Blockquote from "@tiptap/extension-blockquote";
import Bold from "@tiptap/extension-bold";
import BulletList from "@tiptap/extension-bullet-list";
import Code from "@tiptap/extension-code";
import CodeBlockLowlight from "@tiptap/extension-code-block-lowlight";
import { Color } from "@tiptap/extension-color";
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
import TextAlign from "@tiptap/extension-text-align";
import TextStyle from "@tiptap/extension-text-style";
import Underline from "@tiptap/extension-underline";
import { CheckedList, Lead, CustomLink, CustomImage, CustomParagraph, Small, Grid, GridColumn, Youtube, Vimeo } from "./extensions";
import { lowlight } from "lowlight/lib/common";
import { randomString } from "./utils/randomString";

document.addEventListener("alpine:init", () => {
  let editors = window.filamentTiptapEditors || {};

  Alpine.data("tiptap", ({ state, tools = "" }) => ({
    id: null,
    tools: tools.split(","),
    state: state,
    fullScreenMode: false,
    updatedAt: Date.now(),
    focused: false,
    getExtensions() {
      let exts = [Document, Text, CustomParagraph, Dropcursor, Gapcursor, HardBreak, History];

      if (this.tools.includes("link"))
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
      if (this.tools.includes("blockquote")) exts.push(Blockquote);
      if (this.tools.includes("bold")) exts.push(Bold);
      if (this.tools.includes("italic")) exts.push(Italic);
      if (this.tools.includes("strike")) exts.push(Strike);
      if (this.tools.includes("underline")) exts.push(Underline);
      if (this.tools.includes("align")) exts.push(TextAlign.configure({ types: ["heading", "paragraph"] }));
      if (this.tools.includes("subscript")) exts.push(Subscript);
      if (this.tools.includes("superscript")) exts.push(Superscript);
      if (this.tools.includes("media")) exts.push(CustomImage.configure({ inline: true }));
      if (this.tools.includes("youtube")) exts.push(Youtube);
      if (this.tools.includes("vimeo")) exts.push(Vimeo);
      if (this.tools.includes("hr")) exts.push(HorizontalRule);
      if (this.tools.includes("lead")) exts.push(Lead);
      if (this.tools.includes("small")) exts.push(Small);

      if (this.tools.includes("grid")) {
        exts.push(Grid);
        exts.push(GridColumn);
      }

      if (this.tools.includes("code")) exts.push(Code);
      if (this.tools.includes("codeblock"))
        exts.push(
          CodeBlockLowlight.configure({
            lowlight,
            HTMLAttributes: {
              class: "hljs",
            },
          })
        );

      if (this.tools.includes("color")) {
        exts.push(Color);
        exts.push(TextStyle);
      }

      if (this.tools.includes("orderedList") || this.tools.includes("bulletList") || this.tools.includes("checkedList")) {
        if (this.tools.includes("orderedList")) exts.push(OrderedList);
        if (this.tools.includes("bulletList")) exts.push(BulletList);
        if (this.tools.includes("checkedList")) exts.push(CheckedList);
        exts.push(ListItem);
      }

      if (this.tools.includes("table")) {
        exts.push(Table.configure({ resizable: true }), TableHeader, TableCell, TableRow);
      }

      if (this.tools.includes("h1") || this.tools.includes("h2") || this.tools.includes("h3") || this.tools.includes("h4") || this.tools.includes("h5") || this.tools.includes("h6")) {
        let levels = [];
        if (this.tools.includes("h1")) levels.push(1);
        if (this.tools.includes("h2")) levels.push(2);
        if (this.tools.includes("h3")) levels.push(3);
        if (this.tools.includes("h4")) levels.push(4);
        if (this.tools.includes("h5")) levels.push(5);
        if (this.tools.includes("h6")) levels.push(6);
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
        content: state?.initialValue,
        onCreate({ editor }) {
          _this.state = editor.getHTML();
          _this.$refs.textarea.value = _this.state;
          _this.updatedAt = Date.now();
        },
        onUpdate({ editor }) {
          _this.state = editor.getHTML();
          _this.$refs.textarea.value = _this.state;
          _this.$refs.textarea.dispatchEvent(new Event("input"));
          _this.updatedAt = Date.now();
        },
        onSelectionUpdate({ editor }) {
          _this.updatedAt = Date.now();
        },
        onBlur({ event }) {
          _this.focused = false;
          _this.$refs.textarea.dispatchEvent(new Event("change"));
          _this.updatedAt = Date.now();
        },
        onFocus({event}) {
          _this.focused = true;
        }
      });

      window.filamentTiptapEditors = editors;

      document.addEventListener("dblclick", function (e) {
        if (e.target && (e.target.hasAttribute("data-youtube-video") || e.target.hasAttribute("data-vimeo-video"))) {
          e.target.firstChild.style.pointerEvents = "all";
        }
      });

      this.$watch("state", (newState) => {
        if (editors[this.id].getHTML() !== newState) {
          editors[this.id].commands.setContent(newState);
        }
      });
    },
    editor() {
      return editors[this.id];
    },
    isActive(type, opts = {}, updatedAt) {
      return this.editor().isActive(type, opts);
    },
  }));
});
