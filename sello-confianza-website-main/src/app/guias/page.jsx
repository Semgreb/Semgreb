import React, { Suspense } from "react";

import PageHeader from "@/components/PageHeader";

import Container from "@/components/Container";
import Section from "@/components/Section";
import FooterBanner from "@/components/footer/FooterBanner";
import { APP_NAME, ENDPOINTS, HOST_NAME } from "@/config";
import guidePageData from "/public/assets/data/guides-data.json";

import Search from "@/components/Search";
import { getApiData } from "@/services/getApiData";
import ArticleContentLayout from "./components/ArticleContentLayout";
import SearchSkeleton from "@/components/skeleton/SearchSkeleton";
import PageSkeleton from "@/components/skeleton/PageSkeleton";

import ArticleList from "./components/ArticleList";

export const dynamic = "force-dynamic";
const { section_hero_banner, section_footer_banner } = guidePageData;

export const metadata = {
  title: `Guías - ${APP_NAME}`,
  description: section_hero_banner.description,
  metadataBase: new URL(HOST_NAME),
  alternates: {
    canonical: "/",
    languages: {
      "en-US": "/es-DO",
    },
  },
  openGraph: {
    title: section_hero_banner.title,
    images: ["/assets/images/open-graph/guide-image.png"],
  },
};

async function GuidePage({ searchParams }) {
  const params = new URLSearchParams(searchParams);
  const response = await getApiData(ENDPOINTS.articleList, params);

  return (
    <Suspense fallback={<PageSkeleton />}>
      <PageHeader
        title={section_hero_banner.title}
        subTitle={section_hero_banner.description}
      >
        <div className="flex flex-col gap-4 max-w-2xl">
          <Suspense fallback={<SearchSkeleton />}>
            <Search />
          </Suspense>
        </div>
      </PageHeader>
      <Section>
        <Container>
          <ArticleContentLayout>
            <ArticleList
              data={response?.data}
              total={response?.total}
              hasError={response?.isError}
            />
          </ArticleContentLayout>
        </Container>
      </Section>

      <FooterBanner
        title={section_footer_banner.title}
        subtitle={section_footer_banner.description}
        buttonText={section_footer_banner.button?.name}
        buttonUrl={section_footer_banner.button?.url || "/"}
        bullets={section_footer_banner.bullets || []}
        backgroundUrl={section_footer_banner.image_path}
      />
    </Suspense>
  );
}

export default GuidePage;
