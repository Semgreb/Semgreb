import { Fragment } from "react";
import PageHeader from "@/components/PageHeader";

import Section from "@/components/Section";
import Container from "@/components/Container";
import SealList from "./components/SealList";
import FooterBanner from "@/components/footer/FooterBanner";
import NumberBoxList from "@/components/numberbox/NumberBoxList";
import sealsPageData from "/public/assets/data/seal-data.json";
import { APP_NAME, HOST_NAME } from "@/config";

const { section_hero_banner, section_footer_banner } = sealsPageData;
export const metadata = {
  title: `Sellos - Certifica tu negocio - ${APP_NAME}`,
  description: section_hero_banner.description,
  metadataBase: new URL(HOST_NAME),
  alternates: {
    canonical: "/",
    languages: {
      "en-US": "/es-DO",
    },
  },
  openGraph: {
    title: `Certifica tu negocio con el ${APP_NAME}`,
    images: ["/assets/images/open-graph/seals-image.png"],
  },
};

async function SealsPage() {
  return (
    <Fragment>
      <PageHeader
        title={section_hero_banner.title}
        subTitle={section_hero_banner.description}
        rightComponent={<NumberBoxList />}
      />

      <Section>
        <Container>
          <SealList />
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
    </Fragment>
  );
}

export default SealsPage;
