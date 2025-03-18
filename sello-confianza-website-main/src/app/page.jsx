import { Fragment, Suspense } from "react";
import Link from "next/link";
import Button from "@/components/Button";
import Container from "@/components/Container";
import Row from "@/components/Row";
import Section from "@/components/Section";
import Title from "@/components/Title";
import SubTitle from "@/components/SubTitle";
import SectionCTA from "@/components/SectionCTA";
import Paragraph from "@/components/Paragraph";
import FooterBanner from "@/components/footer/FooterBanner";
import CommerceList from "./comercios/components/CommerceList";
import { APP_NAME, HOST_NAME } from "@/config";
import Image from "next/image";

import homePageData from "/public/assets/data/home-data.json";
import CommerceListSkeleton from "@/components/skeleton/CommerceListSkeleton";
const {
  section_hero_banner,
  section_footer_banner,
  section_commerces,
  section_blue,
  section_pos_hero_banner,
} = homePageData;
export const metadata = {
  title: `${APP_NAME} - Certifica tu comercio electrónico`,
  description: section_hero_banner.description,
  metadataBase: new URL(HOST_NAME),
  alternates: {
    canonical: "/",
    languages: {
      "en-US": "/es-DO",
    },
  },
  openGraph: {
    images: ["/assets/images/open-graph/home-image.png"],
  },
};

function HomePage() {
  function renderApplicationSteps() {
    const renderStep = ({ name, image_path }) => {
      return (
        <div
          className="py-20 flex flex-col justify-center items-center bg-indotel-sky-900/5"
          key={name}
        >
          <Image
            src={image_path}
            alt={name}
            width={64}
            height={64}
            className="pb-2"
          />
          <Title type="h4" color="secondary">
            {name}
          </Title>
        </div>
      );
    };
    const steps = section_blue?.application.steps;
    const result = steps?.map(renderStep);
    return result;
  }

  const renderWidget = ({ title, description, button }) => {
    return (
      <div key={title}>
        <Title type="h4">{title}</Title>
        <Paragraph>{description}</Paragraph>
        <Link
          href={button.url || "/"}
          className="text-indotel-sky-900 hover:text-indotel-blue-900"
        >
          {button.name}
        </Link>
      </div>
    );
  };

  const renderHeroWidgets = section_pos_hero_banner?.map(renderWidget);

  return (
    <Fragment>
      <header className="py-20">
        <Container>
          <Row>
            <div>
              <Image
                src={section_hero_banner?.image_path}
                alt={section_hero_banner?.title}
                width={500}
                height={500}
                className="m-auto"
              />
            </div>
            <div className="max-w-md">
              <div className="mb-4">
                <Paragraph>
                  <strong>✅ {section_hero_banner?.label}</strong>
                </Paragraph>

                <Title type="h1">{section_hero_banner?.title}</Title>
                <SubTitle>{section_hero_banner?.description}</SubTitle>
              </div>

              <div className="mb-4">
                <Button
                  name={section_hero_banner?.buttons.first.name}
                  type="primary"
                  url={section_hero_banner?.buttons.first.url || "/"}
                  rightIcon
                />
                <Button
                  name={section_hero_banner?.buttons.second.name}
                  type="transparent"
                  url={section_hero_banner?.buttons.second.url || "/"}
                />
              </div>
            </div>
          </Row>
        </Container>
      </header>

      <Section>
        <Container>
          <div className="mx-auto max-w-4xl">
            <Row>{renderHeroWidgets}</Row>
          </div>
        </Container>
      </Section>

      <section className="py-20 bg-indotel-blue-900">
        <Container>
          <div className="mb-16 max-w-xl">
            <Title type="h2" color="white">
              {section_blue?.title}
            </Title>
            <SubTitle color="secondary">{section_blue?.description}</SubTitle>
          </div>
          <div className="pb-40">
            <div>
              <div className="mb-8">
                <Title type="h3" color="white">
                  {section_blue?.application.title}
                </Title>
              </div>
              <div className="grid gap-4  lg:grid-cols-2">
                <div className="grid gap-4 lg:grid-cols-3">
                  {renderApplicationSteps()}
                </div>

                <div className="flex items-center">
                  <div className="hidden lg:block px-10">
                    <Image
                      src="/assets/images/icons/arrow.svg"
                      alt="Alt text"
                      width={300}
                      height={100}
                    />
                  </div>
                  <div className="p-8 bg-indotel-red-900">
                    <Title type="h3" color="white">
                      {section_blue?.application.banner.title}
                    </Title>
                    <Paragraph color="white">
                      {section_blue?.application.banner.description ||
                        FAKE_TEXT}
                    </Paragraph>

                    <div className="-mb-16">
                      <Button
                        url={section_blue?.application.banner.button.url || "/"}
                        name={
                          section_blue?.application.banner.button.name ||
                          FAKE_TEXT
                        }
                        rightIcon
                        type="white"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Container>
      </section>

      <Section>
        <Container>
          <div className="text-center py-20 -mt-60 bg-white">
            <Title type="h2" color="primary">
              {section_commerces?.title}
            </Title>
            <SubTitle>{section_commerces?.description}</SubTitle>
          </div>

          <Suspense fallback={<CommerceListSkeleton />}>
            <CommerceList limit={12} isMobile />
          </Suspense>

          <SectionCTA
            title={section_commerces?.cta.title}
            url={section_commerces?.cta.button.url || "/"}
            buttonText={section_commerces?.cta.button.name}
          />
        </Container>
      </Section>

      <FooterBanner
        title={section_footer_banner?.title}
        subtitle={section_footer_banner?.description}
        buttonText={section_footer_banner?.button.name}
        buttonUrl={section_footer_banner?.button.url || "/"}
        bullets={section_footer_banner?.bullets || []}
        backgroundUrl={section_footer_banner?.image_path}
      />
    </Fragment>
  );
}

export default HomePage;
