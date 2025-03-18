import ArticleSidebar from "@/app/guias/components/ArticleSidebar";
import React from "react";

function ArticleContentLayout({ children }) {
  return (
    <div className="flex flex-col md:flex-row  justify-between lg:gap-48">
      {children}

      <ArticleSidebar />
    </div>
  );
}

export default ArticleContentLayout;
