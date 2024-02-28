import { mergeAttributes, Node } from "@tiptap/core";
import { getEmbedURLFromVimeoURL, isValidVimeoUrl } from "./utils";
import {getEmbedURLFromYoutubeURL} from "../Youtube/utils.js";

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
        parseHTML: (element) => element.classList.contains("responsive") ?? false,
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

          const embedUrl = getEmbedURLFromVimeoURL({
            url: options.src,
            autoplay: options?.autoplay || 0,
            loop: options?.loop || 0,
            title: options?.title || 0,
            byline: options?.byline || 0,
            portrait: options?.portrait || 0,
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
        {
          src: embedUrl,
          width: HTMLAttributes.width,
          height: HTMLAttributes.height,
          allowfullscreen: this.options.allowfullscreen,
          frameborder: 0,
          allow: "autoplay; fullscreen; picture-in-picture",
          style: HTMLAttributes.responsive ? `aspect-ratio: ${HTMLAttributes['data-aspect-width']} / ${HTMLAttributes['data-aspect-height']}; width: 100%; height: auto;` : null,
          'data-aspect-width': HTMLAttributes.responsive ? HTMLAttributes['data-aspect-width'] : null,
          'data-aspect-height': HTMLAttributes.responsive ? HTMLAttributes['data-aspect-height'] : null,
        },
      ],
    ];
  },
});
