import { Fragment } from "react";
import Image from "next/image";
import Button from "@/components/Button";
import Container from "@/components/Container";
import Row from "@/components/Row";
import Section from "@/components/Section";
import Title from "@/components/Title";
import SubTitle from "@/components/SubTitle";
import Paragraph from "@/components/Paragraph";
import FooterBanner from "@/components/footer/FooterBanner";
import { APP_NAME, HOST_NAME } from "@/config";
import Accordion from "@/components/Accordion";
import sealLandingData from "/public/assets/data/seal-landing-data";
import MarkupContent from "@/components/MarkupContent";

const {
  section_hero_banner,
  section_footer_banner,
  section_how_work,
  section_why,
  section_faq,
} = sealLandingData;
export const metadata = {
  title: `¿Cómo funciona el Sello de Confianza? - ${APP_NAME}`,
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

function SealLandingPage() {
  function renderWidgets() {
    const renderWidget = ({ title, image_path, description }) => {
      return (
        <div
          className="p-20 flex flex-col justify-center items-center  text-center"
          key={title}
        >
          <Image
            src={image_path}
            alt={title}
            width={64}
            height={64}
            className="pb-6"
          />
          <Title type="h4" color="secondary">
            {title}
          </Title>
          <Paragraph color="white">{description}</Paragraph>
        </div>
      );
    };
    const widgets = section_why?.widgets;
    const result = widgets.map(renderWidget);
    return result;
  }

  function renderFaq() {
    const renderFaqItem = ({ question, answer }) => (
      <Accordion key={question} title={question} description={answer} />
    );
    const result = section_faq?.questions.map(renderFaqItem);
    return result;
  }
  return (
    <Fragment>
      <header className="py-20">
        <Container>
          <Row>
            <div>
              <Image
                src={section_hero_banner.image_path}
                alt={section_hero_banner.title}
                // fill="true"
                width={500}
                height={500}
                className="m-auto"
              />
            </div>
            <div className="max-w-md">
              <div className="mb-4">
                <Paragraph>
                  <strong>✅ {section_hero_banner.label}</strong>
                </Paragraph>

                <Title type="h1">{section_hero_banner.title}</Title>

                <SubTitle>{section_hero_banner.description}</SubTitle>
              </div>

              <div className="mb-4">
                <Button
                  name={section_hero_banner.buttons.first.name}
                  type="primary"
                  url={section_hero_banner.buttons.first.url || "/"}
                  rightIcon
                />
              </div>
            </div>
          </Row>
        </Container>
      </header>

      <section className="py-20 bg-indotel-blue-900">
        <Container>
          <div className="mb-16 max-w-xl mx-auto text-center">
            <Title type="h2" color="white">
              {section_why.title}
            </Title>
            <SubTitle color="secondary">{section_why.description}</SubTitle>
          </div>

          <div className="grid gap-4 lg:grid-cols-3">{renderWidgets()}</div>
        </Container>
      </section>

      <Section>
        <Container>
          <Row>
            <div>
              <div className="mb-4">
                <Paragraph>
                  <strong>✅ {section_how_work.label}</strong>
                </Paragraph>

                <Title>{section_how_work.title}</Title>
                <SubTitle>{section_how_work.description}</SubTitle>
              </div>

              <div className="mb-4">
                <Button
                  name={section_how_work.buttons.first.name}
                  type="primary"
                  url={section_how_work.buttons.first.url || "/"}
                  rightIcon
                />
              </div>
            </div>
            <div className="flex items-center justify-center">
              <Image
                src={section_how_work.image_path}
                alt={section_how_work.title}
                // fill="true"
                width={500}
                height={500}
                className="m-auto"
              />
            </div>
          </Row>
        </Container>
      </Section>
      <Section>
        <Container>
          <div className="text-center py-20 bg-white max-w-2xl mx-auto">
            <Title>{section_faq.title}</Title>

            <SubTitle color="secondary">
              <MarkupContent content={section_faq.description} />
            </SubTitle>
          </div>
          <div className="max-w-2xl mx-auto">{renderFaq()}</div>
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

export default SealLandingPage;
