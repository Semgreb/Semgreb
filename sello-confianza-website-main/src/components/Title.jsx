import React, { createElement } from "react";
import { textColorSchema } from "@/utils/colors.schema";

function Title({ children, type = "h2", color = "primary" }) {
  const styleSchema = {
    h1: "text-4xl md:text-6xl font-bold mb-4",
    h2: "text-3xl md:text-5xl font-bold mb-4",
    h3: "text-2xl md:text-3xl font-bold mb-2",
    h4: "text-lg font-bold",
    h5: "text-md font-bold mb-2",
    h6: "text-sm font-bold mb-2",
  };

  const renderStyles = () => {
    return `${textColorSchema[color]} ${styleSchema[type]}`;
  };

  const styles = renderStyles();

  return createElement(type, { className: styles }, children);
}

export default React.memo(Title);
