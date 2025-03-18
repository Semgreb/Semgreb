import React from "react";
import Title from "@/components/Title";
import Button from "@/components/Button";
import Image from "next/image";

import { createMarkup } from "@/utils/createMarkup";

function SealCard({ title, description, image_url, url, buttonName }) {
  return (
    <div className="flex flex-col md:flex-row md:justify-between md:items-center border border-gray-100 p-4 md:p-16">
      <div className="flex flex-col md:flex-row md:items-center gap-4">
        <div>
          <Image src={image_url} width={150} height={150} alt={title} />
        </div>
        <div className="max-w-xl">
          <Title type="h4" color="primary">
            {title}
          </Title>

          <div
            dangerouslySetInnerHTML={createMarkup(description)}
            className="text-gray-700"
          />
        </div>
      </div>
      <div>
        <Button
          name={buttonName}
          type="primary"
          rightIcon
          target="_blank"
          url={url}
        />
      </div>
    </div>
  );
}

export default SealCard;
