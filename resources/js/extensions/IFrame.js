import { Node } from "@tiptap/core";

const IFrame = Node.create({
  name: "iframe",
  group: "block",
  atom: true,
  addOptions() {
    return {
      allowFullscreen: true,
      HTMLAttributes: {
        class: "iframe-wrapper",
      },
    };
  },
  addAttributes() {
    return {
      src: {
        default: null,
      },
      frameborder: {
        default: 0,
      },
      allowfullscreen: {
        default: this.options.allowFullscreen,
        parseHTML: () => this.options.allowFullscreen,
      },
    };
  },
  parseHTML() {
    return [
      {
        tag: "iframe",
      },
    ];
  },
  renderHTML({ HTMLAttributes }) {
    return ["div", this.options.HTMLAttributes, ["iframe", HTMLAttributes]];
  },
  addCommands() {
    return {
      setIframe:
        (options) =>
        ({ tr, dispatch }) => {
          console.log(options);
          const { selection } = tr;
          const node = this.type.create({ src: options.src });

          if (dispatch) {
            tr.replaceRangeWith(selection.from, selection.to, node);
          }

          return true;
        },
    };
  },
});

export default IFrame;
