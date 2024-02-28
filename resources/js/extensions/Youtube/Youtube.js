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
      HTMLAttributes: {},
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
      responsive: {
        default: true,
        parseHTML: (element) => element.classList.contains("responsive") ?? false,
      },
      start: {
        default: 0,
      },
      controls: {
        default: true,
      },
      nocookie: {
        default: false,
      },
      'data-aspect-width': {
        default: null,
        parseHTML: (element) => element.getAttribute("data-aspect-width"),
      },
      'data-aspect-height': {
        default: null,
        parseHTML: (element) => element.getAttribute("data-aspect-height"),
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

          const embedUrl = getEmbedURLFromYoutubeURL({
            url: options.src,
            controls: options.controls,
            nocookie: options.nocookie,
            startAt: options.start || 0,
          });

          return commands.insertContent({
            type: this.name,
            attrs: {
              ...options,
              src: embedUrl,
            },
          });
        },
    };
  },

  renderHTML({ HTMLAttributes }) {
    const embedUrl = getEmbedURLFromYoutubeURL({
      url: HTMLAttributes.src,
      controls: HTMLAttributes.controls,
      nocookie: HTMLAttributes.nocookie,
      startAt: HTMLAttributes.start || 0,
    });

    return [
      "div",
      {
        "data-youtube-video": "",
        class: HTMLAttributes.responsive ? "responsive" : null
      },
      [
        "iframe",
        {
          src: embedUrl,
          width: HTMLAttributes.width,
          height: HTMLAttributes.height,
          allowfullscreen: this.options.allowFullscreen,
          style: HTMLAttributes.responsive ? `aspect-ratio: ${HTMLAttributes['data-aspect-width']} / ${HTMLAttributes['data-aspect-height']}; width: 100%; height: auto;` : null,
          'data-aspect-width': HTMLAttributes.responsive ? HTMLAttributes['data-aspect-width'] : null,
          'data-aspect-height': HTMLAttributes.responsive ? HTMLAttributes['data-aspect-height'] : null,
        },
      ],
    ];
  },
});
