export const isValidVimeoUrl = (url) => {
  return url.match(/(vimeo\.com)(.+)?$/);
};

export const getEmbedURLFromVimeoURL = (options) => {
  const { url, autoplay, loop, title, byline, portrait } = options;

  // if is already an embed url, return it
  if (url.includes("/video/")) {
    return url;
  }

  const videoIdRegex = /\.com\/([0-9]+)/gm;
  const matches = videoIdRegex.exec(url);

  if (!matches || !matches[1]) {
    return null;
  }

  let outputUrl = `https://player.vimeo.com/video/${matches[1]}`;

  const params = [`autoplay=${autoplay}`, `loop=${loop}`, `title=${title}`, `byline=${byline}`, `portrait=${portrait}`];

  outputUrl += `?${params.join("&")}`;

  return outputUrl;
};
