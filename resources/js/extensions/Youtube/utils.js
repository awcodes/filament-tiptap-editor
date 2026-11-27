// Only http(s) and scheme-less URLs may be used as an iframe source.
export const isSafeEmbedUrl = (url) => {
  if (typeof url !== "string" || url === "") {
    return false;
  }

  const match = url.replace(/[\u0000-\u0020\u007F]+/g, "").match(/^([a-z][a-z0-9+.\-]*):/i);

  return !match || ["http", "https"].includes(match[1].toLowerCase());
};

export const isValidYoutubeUrl = (url) => {
  return isSafeEmbedUrl(url) && url.match(/(youtube\.com|youtu\.be)(.+)?$/);
};

export const getYoutubeEmbedUrl = (nocookie = false) => {
  return nocookie ? "https://www.youtube-nocookie.com/embed/" : "https://www.youtube.com/embed/";
};

export const getEmbedURLFromYoutubeURL = (options) => {
  const { url, controls, nocookie, startAt } = options;

  if (!isSafeEmbedUrl(url)) {
    return null;
  }

  let id = null;

  // Already an embed URL
  if (url.includes("/embed/")) {
    return url;
  }

  // Extract the video ID for youtu.be URLs
  if (url.includes("youtu.be")) {
    id = url.split("/").pop();
  }

  // Extract the video ID for /shorts/ URLs
  if (url.includes("/shorts/")) {
    id = url.split("/shorts/").pop();
  }

  // Extract the video ID for standard YouTube URLs (?v=)
  if (!id) {
    const videoIdRegex = /v=([-\w]+)/gm;
    const matches = videoIdRegex.exec(url);
    if (matches && matches[1]) {
      id = matches[1];
    }
  }

  if (!id) {
    return null;
  }

  let outputUrl = `${getYoutubeEmbedUrl(nocookie)}${id}`;

  const params = [];

  if (!controls) {
    params.push("controls=0");
  } else {
    params.push("controls=1");
  }

  if (startAt) {
    params.push(`start=${startAt}`);
  }

  if (params.length) {
    outputUrl += `?${params.join("&")}`;
  }

  return outputUrl;
};