import { mergeAttributes, Node } from "@tiptap/core";
import { getEmbedURLFromVimeoURL, isValidVimeoUrl } from "./utils";

export const Vimeo = Node.create({
  name: "vimeo",

  selectable: true,

  draggable: true,

  atom: true,

  addOptions() {
    return {
      inline: false,
      HTMLAttributes: {},
      allowFullscreen: true,
      width: 640,
      height: 480,
    };
  },

  inline() {
    return this.options.inline;
  },

  group() {
    return this.options.inline ? "inline" : "block";
  },

  addAttributes() {
    return {
      style: {
        default: null,
        parseHTML: (element) => element.getAttribute("style"),
      },
      src: {
        default: null,
      },
      width: {
        default: this.options.width,
        parseHTML: (element) => element.getAttribute("width"),
      },
      height: {
        default: this.options.height,
        parseHTML: (element) => element.getAttribute("height"),
      },
      autoplay: {
        default: 0,
      },
      loop: {
        default: 0,
      },
      title: {
        default: 0,
      },
      byline: {
        default: 0,
      },
      portrait: {
        default: 0,
      },
      responsive: {
        default: true,
      },
      aspectWidth: {
        default: 16,
        parseHTML: (element) => element.getAttribute("aspect-width"),
      },
      aspectHeight: {
        default: 9,
        parseHTML: (element) => element.getAttribute("aspect-height"),
      },
    };
  },

  parseHTML() {
    return [
      {
        tag: "div[data-vimeo-video] iframe",
      },
    ];
  },

  addCommands() {
    return {
      setVimeoVideo:
        (options) =>
        ({ commands }) => {
          if (!isValidVimeoUrl(options.src)) {
            return false;
          }

          return commands.insertContent({
            type: this.name,
            attrs: options,
          });
        },
    };
  },

  renderHTML({ HTMLAttributes }) {
    const embedUrl = getEmbedURLFromVimeoURL({
      url: HTMLAttributes.src,
      autoplay: HTMLAttributes?.autoplay || 0,
      loop: HTMLAttributes?.loop || 0,
      title: HTMLAttributes?.title || 0,
      byline: HTMLAttributes?.byline || 0,
      portrait: HTMLAttributes?.portrait || 0,
    });

    return [
      "div",
      { "data-vimeo-video": "", class: HTMLAttributes.responsive ? "responsive" : null },
      [
        "iframe",
        mergeAttributes(this.options.HTMLAttributes, {
          src: embedUrl,
          width: this.options.width,
          height: this.options.height,
          allowfullscreen: "",
          frameborder: 0,
          allow: "autoplay; fullscreen; picture-in-picture",
          style: HTMLAttributes.responsive ? `aspect-ratio: ${HTMLAttributes.aspectWidth} / ${HTMLAttributes.aspectHeight}; width: 100%; height: auto;` : null,
          aspectWidth: HTMLAttributes.responsive ? HTMLAttributes.aspectWidth : HTMLAttributes.width,
          aspectHeight: HTMLAttributes.responsive ? HTMLAttributes.aspectHeight : HTMLAttributes.height,
        }),
      ],
    ];
  },
});
