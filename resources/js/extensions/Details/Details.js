import { findParentNode, findChildren, Node, mergeAttributes, defaultBlockAt, isActive } from "@tiptap/core";
import { Selection, Plugin, PluginKey, TextSelection } from "prosemirror-state";
import { isNodeVisible } from "../../utils";

export const Details = Node.create({
  name: "details",

  content: "detailsSummary detailsContent",

  group: "block",

  defining: true,

  isolating: true,

  allowGapCursor: false,

  addOptions() {
    return {
      HTMLAttributes: {},
    };
  },

  addAttributes() {
    return {};
  },

  parseHTML() {
    return [
      {
        tag: "details",
      },
    ];
  },

  renderHTML({ HTMLAttributes }) {
    return ["details", mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0];
  },

  addNodeView() {
    return ({ editor, getPos, node, HTMLAttributes }) => {
      const dom = document.createElement("div");
      const content = document.createElement("div");

      const attributes = mergeAttributes(this.options.HTMLAttributes, HTMLAttributes, {
        "data-type": this.name,
      });
      Object.entries(attributes).forEach(([key, value]) => dom.setAttribute(key, value));

      return {
        dom,
        contentDOM: dom,
        ignoreMutation(mutation) {
          if (mutation.type === "selection") {
            return false;
          }
          return !dom.contains(mutation.target) || dom === mutation.target;
        },
        update: (updatedNode) => {
          if (updatedNode.type !== this.type) {
            return false;
          }
          return true;
        },
      };
    };
  },

  addCommands() {
    return {
      setDetails:
        () =>
        ({ state, chain }) => {
          var _a;
          const { schema, selection } = state;
          const { $from, $to } = selection;
          const range = $from.blockRange($to);
          if (!range) {
            return false;
          }
          const slice = state.doc.slice(range.start, range.end);
          const match = schema.nodes.detailsContent.contentMatch.matchFragment(slice.content);
          if (!match) {
            return false;
          }

          const content = ((_a = slice.toJSON()) === null || _a === void 0 ? void 0 : _a.content) || [];

          return chain()
            .insertContentAt({ from: range.start, to: range.end }, { type: this.name, content: [{ type: "detailsSummary" }, { type: "detailsContent", content }] })
            .setTextSelection(range.start + 2)
            .run();
        },
      unsetDetails:
        () =>
        ({ state, chain }) => {
          const { selection, schema } = state;
          const details = findParentNode((node) => node.type === this.type)(selection);
          if (!details) {
            return false;
          }
          const detailsSummaries = findChildren(details.node, (node) => node.type === schema.nodes.detailsSummary);
          const detailsContents = findChildren(details.node, (node) => node.type === schema.nodes.detailsContent);
          if (!detailsSummaries.length || !detailsContents.length) {
            return false;
          }
          const detailsSummary = detailsSummaries[0];
          const detailsContent = detailsContents[0];
          const from = details.pos;
          const $from = state.doc.resolve(from);
          const to = from + details.node.nodeSize;
          const range = { from, to };
          const content = detailsContent.node.content.toJSON() || [];
          const defaultTypeForSummary = $from.parent.type.contentMatch.defaultType;
          // TODO: this may break for some custom schemas
          const summaryContent = defaultTypeForSummary === null || defaultTypeForSummary === void 0 ? void 0 : defaultTypeForSummary.create(null, detailsSummary.node.content).toJSON();
          const mergedContent = [summaryContent, ...content];
          return chain()
            .insertContentAt(range, mergedContent)
            .setTextSelection(from + 1)
            .run();
        },
    };
  },

  addKeyboardShortcuts() {
    return {
      Backspace: () => {
        const { schema, selection } = this.editor.state;
        const { empty, $anchor } = selection;
        if (!empty || $anchor.parent.type !== schema.nodes.detailsSummary) {
          return false;
        }
        // for some reason safari removes the whole text content within a `<summary>`tag on backspace
        // so we have to remove the text manually
        // see: https://discuss.prosemirror.net/t/safari-backspace-bug-with-details-tag/4223
        if ($anchor.parentOffset !== 0) {
          return this.editor.commands.command(({ tr }) => {
            const from = $anchor.pos - 1;
            const to = $anchor.pos;
            tr.delete(from, to);
            return true;
          });
        }
        return this.editor.commands.unsetDetails();
      },
    };
  },
});
