import React, { Suspense } from "react";
import PageHeader from "@/components/PageHeader";
import Section from "@/components/Section";
import Container from "@/components/Container";
import Accordion from "@/components/Accordion";
import faqPageData from "/public/assets/data/faq-data.json";
import FooterBanner from "@/components/footer/FooterBanner";
import { APP_NAME, HOST_NAME } from "@/config";
import PageSkeleton from "@/components/skeleton/PageSkeleton";

export const metadata = {
  title: `Preguntas Frecuentes - ${APP_NAME}`,
  description: "Un producto de Indotel",
  metadataBase: new URL(HOST_NAME),
  alternates: {
    canonical: "/",
    languages: {
      "en-US": "/es-DO",
    },
  },
};

function FaqPage() {
  const { section_faq, section_hero_banner, section_footer_banner } =
    faqPageData;

  function renderFaq() {
    const renderFaqItem = ({ question, answer }) => (
      <Accordion key={question} title={question} description={answer} />
    );
    const result = section_faq?.questions.map(renderFaqItem);
    return result;
  }
  return (
    <Suspense fallback={<PageSkeleton />}>
      <PageHeader
        title={section_hero_banner.title}
        subTitle={section_hero_banner.description}
      />
      <Section>
        <Container>
          <div>{renderFaq()}</div>
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

export default FaqPage;
