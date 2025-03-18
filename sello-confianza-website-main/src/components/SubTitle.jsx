import React from "react";
import { textColorSchema } from "@/utils/colors.schema";

function SubTitle({ children, color = "default" }) {
  const renderStyles = () => {
    return `${textColorSchema[color]} text-xl`;
  };

  const styles = renderStyles();

  return <p className={styles}>{children}</p>;
}

export default React.memo(SubTitle);
