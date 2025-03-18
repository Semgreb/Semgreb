import React from "react";
import { createMarkup } from "@/utils/createMarkup";

export default function MarkupContent({ content }) {
  return (
    <span
      className="article-content text-gray-700"
      dangerouslySetInnerHTML={createMarkup(content)}
    />
  );
}
