import { findParentNode, findChildren, Node, mergeAttributes, defaultBlockAt, isActive } from "@tiptap/core";
import { Selection, Plugin, PluginKey, TextSelection } from "prosemirror-state";
import { GapCursor } from "prosemirror-gapcursor";

const isNodeVisible = (position, editor) => {
  const node = editor.view.domAtPos(position).node;
  const isOpen = node.offsetParent !== null;
  return isOpen;
};

const setGapCursor = (editor, direction) => {
  const { state, view, extensionManager } = editor;
  const { schema, selection } = state;
  const { empty, $anchor } = selection;
  const hasGapCursorExtension = !!extensionManager.extensions.find((extension) => extension.name === "gapCursor");

  if (!empty || $anchor.parent.type !== schema.nodes.detailsSummay || !hasGapCursorExtension) {
    return false;
  }

  if (direction === "right" && $anchor.parentOffset !== $anchor.parent.nodeSize - 2) {
    return false;
  }

  const details = findParentNode((node) => node.type === schema.nodes.details)(selection);

  if (!details) {
    return false;
  }

  const detailsContent = findChildren(details.node, (node) => node.type === schema.nodes.detailsContent);

  if (!detailsContent.length) {
    return false;
  }

  const isOpen = isNodeVisible(details.start + detailsContent[0].pos + 1, editor);

  if (isOpen) {
    return false;
  }

  const $position = state.doc.resolve(details.pos + details.node.nodeSize);
  const $validPosition = GapCursor.findFrom($position, 1, false);

  if (!$validPosition) {
    return false;
  }

  const { tr } = state;
  const gapCursorSelection = new GapCursor($validPosition, $validPosition);
  tr.setSelection(gapCursorSelection);
  tr.scrollIntoView();
  view.dispatch(tr);
  return true;
};

const findClosestVisibleNode = ($pos, predicate, editor) => {
  for (let i = $pos.depth; i > 0; i -= 1) {
    const node = $pos.node(i);
    const match = predicate(node);
    const isVisible = isNodeVisible($pos.start(i), editor);
    if (match && isVisible) {
      return {
        pos: i > 0 ? $pos.before(i) : 0,
        start: $pos.start(i),
        depth: i,
        node,
      };
    }
  }
};

export const Details = Node.create({
  name: "details",
  content: "detailsSummary detailsContent",
  group: "block",
  defining: true,
  isolating: true,
  allowGapCursor: false,
  addOptions() {
    return {
      persist: false,
      openClassName: "is-open",
      HTMLAttributes: {},
    };
  },
  addAttributes() {
    if (!this.options.persist) {
      return [];
    }

    return {
      open: {
        default: false,
        parseHTML: (element) => element.hasAttribute("open"),
        renderHTML: ({ open }) => {
          if (!open) {
            return {};
          }
          return { open: "" };
        },
      },
    };
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
      const attributes = mergeAttributes(this.options.HTMLAttributes, HTMLAttributes, {
        "data-type": this.name,
        class: this.options.openClassName,
      });
      Object.entries(attributes).forEach(([key, value]) => dom.setAttribute(key, value));
      const toggle = document.createElement("button");
      toggle.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--ic" width="32" height="32" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M17 7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h10c2.76 0 5-2.24 5-5s-2.24-5-5-5zM7 15c-1.66 0-3-1.34-3-3s1.34-3 3-3s3 1.34 3 3s-1.34 3-3 3z"></path></svg>`;
      toggle.setAttribute("type", "button");
      dom.append(toggle);
      const content = document.createElement("div");
      dom.append(content);
      const toggleDetailsContent = () => {
        dom.classList.toggle(this.options.openClassName);
        const event = new Event("toggleDetailsContent");
        const detailsContent = content.querySelector(':scope > div[data-type="detailsContent"]');
        detailsContent === null || detailsContent === void 0 ? void 0 : detailsContent.dispatchEvent(event);
      };
      if (node.attrs.open) {
        setTimeout(toggleDetailsContent);
      }
      toggle.addEventListener("click", () => {
        toggleDetailsContent();
        if (!this.options.persist) {
          editor.commands.focus();
          return;
        }
        if (editor.isEditable && typeof getPos === "function") {
          editor
            .chain()
            .focus()
            .command(({ tr }) => {
              const pos = getPos();
              const currentNode = tr.doc.nodeAt(pos);
              if ((currentNode === null || currentNode === void 0 ? void 0 : currentNode.type) !== this.type) {
                return false;
              }
              tr.setNodeMarkup(pos, undefined, {
                open: !currentNode.attrs.open,
              });
              return true;
            })
            .run();
        }
      });
      return {
        dom,
        contentDOM: content,
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
      insertDetails:
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
        unsetDetails: () => ({ state, chain }) => {
            const { selection, schema } = state;
            const details = findParentNode(node => node.type === this.type)(selection);
            if (!details) {
                return false;
            }
            const detailsSummaries = findChildren(details.node, node => node.type === schema.nodes.detailsSummary);
            const detailsContents = findChildren(details.node, node => node.type === schema.nodes.detailsContent);
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
            const mergedContent = [
                summaryContent,
                ...content,
            ];
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
      // Creates a new node below it if it is closed.
      // Otherwise inside `DetailsContent`.
      Enter: ({ editor }) => {
        const { state, view } = editor;
        const { schema, selection } = state;
        const { $head } = selection;
        if ($head.parent.type !== schema.nodes.detailsSummary) {
          return false;
        }
        const isVisible = isNodeVisible($head.after() + 1, editor);
        const above = isVisible ? state.doc.nodeAt($head.after()) : $head.node(-2);
        if (!above) {
          return false;
        }
        const after = isVisible ? 0 : $head.indexAfter(-1);
        const type = defaultBlockAt(above.contentMatchAt(after));
        if (!type || !above.canReplaceWith(after, after, type)) {
          return false;
        }
        const node = type.createAndFill();
        if (!node) {
          return false;
        }
        const pos = isVisible ? $head.after() + 1 : $head.after(-1);
        const tr = state.tr.replaceWith(pos, pos, node);
        const $pos = tr.doc.resolve(pos);
        const newSelection = Selection.near($pos, 1);
        tr.setSelection(newSelection);
        tr.scrollIntoView();
        view.dispatch(tr);
        return true;
      },
      // The default gapcursor implementation can’t handle hidden content, so we need to fix this.
      ArrowRight: ({ editor }) => {
        return setGapCursor(editor, "right");
      },
      // The default gapcursor implementation can’t handle hidden content, so we need to fix this.
      ArrowDown: ({ editor }) => {
        return setGapCursor(editor, "down");
      },
    };
  },
});
