import React from "react";
import Link from "next/link";
import Image from "next/image";
import { getApiData } from "@/services/getApiData";
import { ENDPOINTS } from "@/config";
import Title from "@/components/Title";

async function ArticleSidebar() {
  const response = await getApiData(ENDPOINTS.articleGroupList);

  const renderArticleGroup = ({ groupid, name, group_slug }) => {
    const slug = `/guias/tema/${group_slug}`;
    return (
      <li key={groupid} className="mb-1">
        <Link
          href={slug}
          className="flex gap-4 py-2 px-6 bg-indotel-red-900/5 hover:bg-indotel-red-900/10 transition-all"
        >
          <Image
            src="/assets/images/icons/ion_book-outline.svg"
            width={16}
            height={16}
            alt={name}
            className="ml-4"
          />
          {name}
        </Link>
      </li>
    );
  };

  const renderTemes = response?.data?.map(renderArticleGroup);
  return (
    <aside className="min-w-[300px]">
      <Title type="h4">Explora nuestros temas</Title>
      <ul className="mt-4">{renderTemes}</ul>
    </aside>
  );
}

export default ArticleSidebar;
