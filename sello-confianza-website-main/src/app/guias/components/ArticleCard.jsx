import Title from "@/components/Title";
import { createMarkup } from "@/utils/createMarkup";
import Link from "next/link";
import React from "react";

function ArticleCard({ subject, short_description, slug }) {
  return (
    <article>
      <Link href={slug}>
        <div className="border-b border-gray-100 py-6">
          <Title type="h4" color="primary">
            {subject}
          </Title>

          <div
            className="article-content text-gray-700 py-4"
            suppressHydrationWarning={true}
            dangerouslySetInnerHTML={createMarkup(
              short_description.concat("<span>...</span>")
            )}
          />
        </div>
      </Link>
    </article>
  );
}

export default ArticleCard;
