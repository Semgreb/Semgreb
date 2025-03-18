import React from "react";
import Title from "@/components/Title";
import Link from "next/link";
import Image from "next/image";
import { formatUrl } from "@/utils/formatters";

function CommerceCard({ company, website, slug, logo, type }) {
  return (
    <article className=" border border-gray-100 rounded overflow-hidden relative min-w-[80%] md:min-w-full">
      <Link href={`/comercios/${slug} `} className="absolute inset-0" />
      <Image src={logo} alt={company} width={400} height={400} />
      <div className="p-4">
        <span className="text-indotel-sky-900 hover:text-indotel-blue-900">
          {type}
        </span>
        <Title type="h4" color="primary">
          {company}
        </Title>
        <p className="text-xs text-indotel-blue-900 truncate">
          {formatUrl(website)}
        </p>
      </div>
    </article>
  );
}

export default CommerceCard;
