import React from "react";
import { textColorSchema } from "@/utils/colors.schema";

function Paragraph({ children, color = "default" }) {
  const renderStyles = () => {
    return `${textColorSchema[color]} mb-4`;
  };

  const styles = renderStyles();

  return <p className={styles}>{children}</p>;
}

export default React.memo(Paragraph);
