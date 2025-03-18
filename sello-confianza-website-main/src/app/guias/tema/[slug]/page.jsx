import React, { Suspense } from "react";
import Container from "@/components/Container";
import Section from "@/components/Section";
import Title from "@/components/Title";
import { getApiData } from "@/services/getApiData";
import { ENDPOINTS, APP_NAME, HOST_NAME } from "@/config";
import { notFound } from "next/navigation";
import Paragraph from "@/components/Paragraph";
import ArticleContentLayout from "../../components/ArticleContentLayout";
import PageSkeleton from "@/components/skeleton/PageSkeleton";

import ArticleList from "../../components/ArticleList";

const validateContentStatus = (data) => {
  if (data?.status === 404) {
    notFound();
  }
};

export function fetchData(slug) {
  return getApiData(ENDPOINTS.articleGroupDetail.concat(slug));
}

export async function generateMetadata({ params }) {
  const data = await fetchData(params.slug);
  validateContentStatus(data);

  const title = `Temas sobre ${data.name} - Guías - ${APP_NAME}`;
  return {
    title,
    description: data.description,
    metadataBase: new URL(HOST_NAME),
    alternates: {
      canonical: "/",
      languages: {
        "en-US": "/es-DO",
      },
    },
    openGraph: {
      images: ["/assets/images/open-graph/guide-image.png"],
      title,
    },
  };
}

async function GuiteTemePage({ params }) {
  const groupDetail = await fetchData(params.slug);
  validateContentStatus(groupDetail);

  const response = await getApiData(
    ENDPOINTS.articleListByGroup.concat("/?slug=", params.slug)
  );

  return (
    <Suspense fallback={<PageSkeleton />}>
      <section className="bg-indotel-red-900/5 py-20">
        <Container>
          <Title type="h1" color="primary">
             {`Temas sobre ${groupDetail.name}`}
          </Title>
          <Paragraph>{groupDetail.description}</Paragraph>
        </Container>
      </section>
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
    </Suspense>
  );
}

export default GuiteTemePage;
