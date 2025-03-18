import React, { Suspense } from "react";
import Container from "@/components/Container";
import Section from "@/components/Section";
import Title from "@/components/Title";
import { getApiData } from "@/services/getApiData";
import { ENDPOINTS, APP_NAME, DEFAULT_IMAGE, HOST_NAME } from "@/config";
import { createMarkup } from "@/utils/createMarkup";
import { notFound } from "next/navigation";
import ArticleContentLayout from "../components/ArticleContentLayout";
import PageSkeleton from "@/components/skeleton/PageSkeleton";
import Error from "@/app/error";

const validateContentStatus = (data) => {
  if (data?.status === 404) {
    notFound();
  }
};
export function fetchData(slug) {
  return getApiData(ENDPOINTS.articleDetail.concat(slug));
}

export async function generateMetadata({ params }) {
  const data = await fetchData(params.slug);
  validateContentStatus(data);
  const title = `${data.subject} - ${APP_NAME}`;
  return {
    title,
    description: `Sigue leyendo en nuestra web ${APP_NAME}`,
    metadataBase: new URL(HOST_NAME),
    alternates: {
      canonical: "/",
      languages: {
        "en-US": "/es-DO",
      },
    },
    openGraph: {
      images: [DEFAULT_IMAGE],
      title,
    },
  };
}

async function GuidePage({ params }) {
  const data = await fetchData(params.slug);
  validateContentStatus(data);

  if (data?.isError) return <Error />;
  return (
    <Suspense fallback={<PageSkeleton />}>
      <section className="bg-indotel-red-900/5 py-20">
        <Container>
          <Title type="h1" color="primary">
            {data.subject}
          </Title>
        </Container>
      </section>
      <Section>
        <Container>
          <ArticleContentLayout>
            <div
              dangerouslySetInnerHTML={createMarkup(data.description)}
              className="article-content text-gray-700"
            />
          </ArticleContentLayout>
        </Container>
      </Section>
    </Suspense>
  );
}

export default GuidePage;
