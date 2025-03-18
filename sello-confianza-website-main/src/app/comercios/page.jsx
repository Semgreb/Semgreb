import React, { Suspense } from "react";

import Title from "@/components/Title";
import Row from "@/components/Row";
import PageHeader from "@/components/PageHeader";

import FooterBanner from "@/components/footer/FooterBanner";
import NumberBoxList from "@/components/numberbox/NumberBoxList";
import commercePageData from "/public/assets/data/commerces-data.json";
import { APP_NAME, HOST_NAME } from "@/config";
import CommerceList from "./components/CommerceList";
import Search from "@/components/Search";
import Container from "@/components/Container";
import Section from "@/components/Section";
import SearchSkeleton from "@/components/skeleton/SearchSkeleton";
import PageSkeleton from "@/components/skeleton/PageSkeleton";

export const dynamic = "force-dynamic";

const { section_hero_banner, section_footer_banner, section_commerces } =
  commercePageData;
export const metadata = {
  title: `Comercios certificados - ${APP_NAME}`,
  description: "section_hero_banner.description",
  metadataBase: new URL(HOST_NAME),
  alternates: {
    canonical: "/",
    languages: {
      "en-US": "/es-DO",
    },
  },
  openGraph: {
    title: `Comercios certificados con el ${APP_NAME}`,
    images: ["/assets/images/open-graph/commerces-image.png"],
  },
};

async function CommercePage({ searchParams }) {
  return (
    <Suspense fallback={<PageSkeleton />}>
      <PageHeader
        title={section_hero_banner.title}
        subTitle={section_hero_banner.description}
        rightComponent={<NumberBoxList />}
      />

      <Section>
        <Container>
          <div className="pb-6">
            <Row>
              <div>
                <Title type="h4">{section_commerces.filters.title}</Title>
              </div>
              <div>
                <div className="flex gap-2">
                  <Suspense fallback={<SearchSkeleton />}>
                    <Search />
                  </Suspense>
                </div>
              </div>
            </Row>
          </div>

          <CommerceList searchParams={searchParams} showLoadMore />
        </Container>
      </Section>

      <FooterBanner
        title={section_footer_banner.title}
        subtitle={section_footer_banner.description}
        buttonText={section_footer_banner.button.name}
        buttonUrl={section_footer_banner.button.url || "/"}
        bullets={section_footer_banner.bullets || []}
        backgroundUrl={section_footer_banner.image_path}
      />
    </Suspense>
  );
}

export default CommercePage;
