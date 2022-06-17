import { mergeAttributes, Node } from "@tiptap/core";

import { getEmbedURLFromYoutubeURL, isValidYoutubeUrl } from "./utils";

export const Youtube = Node.create({
  name: "youtube",

  selectable: true,

  draggable: true,

  atom: true,

  addOptions() {
    return {
      inline: false,
      controls: true,
      HTMLAttributes: {},
      nocookie: false,
      allowFullscreen: false,
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
      start: {
        default: 0,
      },
      width: {
        default: this.options.width,
        parseHTML: (element) => element.getAttribute("width"),
      },
      height: {
        default: this.options.height,
        parseHTML: (element) => element.getAttribute("height"),
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
        tag: "div[data-youtube-video] iframe",
      },
    ];
  },

  addCommands() {
    return {
      setYoutubeVideo:
        (options) =>
        ({ commands }) => {
          if (!isValidYoutubeUrl(options.src)) {
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
    const embedUrl = getEmbedURLFromYoutubeURL({
      url: HTMLAttributes.src,
      controls: this.options.controls,
      nocookie: this.options.nocookie,
      startAt: HTMLAttributes.start || 0,
    });

    return [
      "div",
      { "data-youtube-video": "", class: HTMLAttributes.responsive ? "responsive" : null },
      [
        "iframe",
        mergeAttributes(this.options.HTMLAttributes, {
          src: embedUrl,
          width: this.options.width,
          height: this.options.height,
          allowfullscreen: this.options.allowFullscreen,
          style: HTMLAttributes.responsive ? `aspect-ratio: ${HTMLAttributes.aspectWidth} / ${HTMLAttributes.aspectHeight}; width: 100%; height: auto;` : null,
          aspectWidth: HTMLAttributes.responsive ? HTMLAttributes.aspectWidth : HTMLAttributes.width,
          aspectHeight: HTMLAttributes.responsive ? HTMLAttributes.aspectHeight : HTMLAttributes.height,
        }),
      ],
    ];
  },
});
