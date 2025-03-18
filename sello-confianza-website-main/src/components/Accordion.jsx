"use client";

import React, { useState } from "react";

import Title from "./Title";
import { APP_NAME } from "@/config";
import Image from "next/image";
import MarkupContent from "./MarkupContent";

function Accordion({ title, description }) {
  const [showContent, setShowContent] = useState(false);

  const handlerOnClick = () => setShowContent(!showContent);
  return (
    <div className="border-b border-gray-100 py-6">
      <div
        className="flex justify-between items-center gap-4 cursor-pointer"
        onClick={handlerOnClick}
      >
        <Title type="h4" color="primary">
          {title}
        </Title>
        <div className="p-4 hover:bg-gray-50 rounded cursor-pointer">
          <Image
            src="/assets/images/icons/ion_add-outline.svg"
            alt={APP_NAME}
            width={16}
            height={16}
          />
        </div>
      </div>
      {showContent && (
        <div className="py-4">
          <MarkupContent content={description} />
        </div>
      )}
    </div>
  );
}

export default Accordion;
