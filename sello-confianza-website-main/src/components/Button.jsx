import React from "react";
import Link from "next/link";
import { buttonColorSchema } from "@/utils/colors.schema";
import Image from "next/image";

function Button({ url = "#", name, type = "transparent", rightIcon, ...rest }) {
  const renderStyles = () => {
    return `${buttonColorSchema[type]} inline-flex py-4 px-6 justify-center`;
  };

  const styles = renderStyles();

  return (
    <Link href={url} className={styles} {...rest}>
      {name}
      {rightIcon && (
        <Image
          src="/assets/images/icons/ion_arrow-forward.svg"
          width={16}
          height={16}
          alt={name}
          className="ml-4"
        />
      )}
    </Link>
  );
}

export default React.memo(Button);
