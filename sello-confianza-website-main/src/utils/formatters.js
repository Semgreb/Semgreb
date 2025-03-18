export function dateFormatter(date) {
  const dateFormatted = new Date(date).toLocaleDateString("es-do", {
    weekday: "long",
    year: "numeric",
    month: "short",
    day: "numeric",
  });

  return dateFormatted;
}

export function formatToPlainText(str) {
  str = str.replace(/<br>/gi, "\n");
  str = str.replace(/<p.*>/gi, "\n");
  str = str.replace(/<a.*href="(.*?)".*>(.*?)<\/a>/gi, " $2 (Link->$1) ");
  str = str.replace(/<(?:.|\s)*?>/g, "");

  return str;
}

export function formatUrl(url) {
  const newUrl = url.toLowerCase().replace(/(^\w+:|^)\/\//, "");

  return newUrl;
}
