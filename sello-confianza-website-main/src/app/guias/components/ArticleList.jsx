import React from "react";
import ArticleCard from "./ArticleCard";
import NoResults from "@/components/NoResults";
import LoadMoreButton from "@/components/LoadMoreButton";
import Error from "@/app/error";

export default function ArticleList({ data, total, hasError }) {
  console.log("data:", data);
  if (hasError) return <Error />;
  const renderArticleCard = ({
    articleid,
    subject,
    short_description,
    slug,
  }) => (
    <ArticleCard
      key={articleid}
      subject={subject}
      short_description={short_description}
      slug={`/guias/${slug}`}
    />
  );
  const renderArticles = data?.map(renderArticleCard);

  return data?.length === 0 ? (
    <NoResults />
  ) : (
    <div className="w-full">
      {renderArticles}
      {data?.length < total && <LoadMoreButton />}
    </div>
  );
}
