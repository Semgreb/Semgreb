import Image from "next/image";
import React from "react";
import Title from "./Title";
import { APP_NAME } from "@/config";

export default function NoResults({ showImage }) {
  return (
    <div className="flex flex-col justify-center items-center gap-8 w-full p-10 lg:py-20">
      {showImage && (
        <Image
          src="/assets/images/undraw_no_data_re_kwbl.svg"
          alt={APP_NAME}
          width={120}
          height={120}
        />
      )}
      <Title type="h3">No se encontraron resultados</Title>
    </div>
  );
}
