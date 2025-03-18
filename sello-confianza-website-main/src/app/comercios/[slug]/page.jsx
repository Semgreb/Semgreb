import React, { Suspense } from "react";
import Container from "@/components/Container";
import Paragraph from "@/components/Paragraph";
import Section from "@/components/Section";
import SubTitle from "@/components/SubTitle";
import Title from "@/components/Title";
import RelatedCommerceList from "../components/RelatedCommerceList";
import Button from "@/components/Button";
import CertificateCard from "@/app/sellos/components/CertificateCard";
import Link from "next/link";
import FooterBanner from "@/components/footer/FooterBanner";
import {
  APP_NAME,
  COMMERCE_REPORT_URL,
  DEFAULT_IMAGE,
  ENDPOINTS,
  HOST_NAME,
} from "@/config";
import { dateFormatter, formatUrl } from "@/utils/formatters";
import { addressConcater } from "@/utils/addressConcater";
import Image from "next/image";
import commercePageData from "/public/assets/data/commerce-data.json";
import { getApiData } from "@/services/getApiData";
import { notFound } from "next/navigation";
import PageSkeleton from "@/components/skeleton/PageSkeleton";
import Error from "@/app/error";

const validateContentStatus = (data) => {
  if (data?.status === 404) {
    notFound();
  }
};
export function fetchData(slug) {
  return getApiData(ENDPOINTS.commerceDetail.concat(slug));
}

export async function generateMetadata({ params }) {
  const data = await fetchData(params.slug);
  validateContentStatus(data);
  const title = `${data.company} - Comercio certificado con ${APP_NAME}`;
  const openGraphImage = data.logo ? data.logo : DEFAULT_IMAGE;

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
      images: [openGraphImage],
      title,
    },
  };
}

async function CommercePage({ params }) {
  const data = await fetchData(params.slug);
  validateContentStatus(data);
  if (data.isError) return <Error />;
  const {
    company,
    phonenumber,
    vat,
    address,
    website,
    type,
    seals,
    datecreated,
    client_description,
    client_email,
    logo,
    city,
    state,
    country,
    client_id_encrypt,
    userid,
  } = data;

  const {
    section_about,
    section_report,
    section_certifications,
    section_related_commerces,
    section_footer_banner,
  } = commercePageData;

  const renderCertificate = (certificate) => (
    <CertificateCard
      key={certificate.title}
      title={certificate.title}
      description={certificate.description}
      image_url={certificate.logo || DEFAULT_IMAGE}
      uid={certificate.nui}
      date_expiration={certificate.date_expiration}
    />
  );

  const renderCertificateCards = seals?.map(renderCertificate);

  function renderTypeTags() {
    const renderType = ({ name, id }) => (
      <label
        key={id}
        className="bg-blue-100 p-2 rounded-md inline-flex text-xs font-bold text-indotel-blue-900"
      >
        {name}
      </label>
    );

    const result = type?.map(renderType);
    return result;
  }
  const relatedSeccionTitle = `${section_related_commerces.bedore_description} ${company}.`;

  const urlToReport = `${COMMERCE_REPORT_URL}/${client_id_encrypt}`;

  return (
    <Suspense fallback={<PageSkeleton />}>
      <section className="bg-indotel-red-900/5 py-20">
        <Container>
          <div className="flex flex-col md:flex-row justify-between items-center w-full max-w-5xl mx-auto gap-8 overflow-hidden">
            <div>
              <Image
                src={logo || DEFAULT_IMAGE}
                alt={company}
                width={300}
                height={300}
                className="rounded-lg border-4 border-white"
              />
            </div>
            <div className="w-full flex flex-col justify-center items-center md:items-start">
              <Title type="h1" color="primary">
                {company}
              </Title>

              <div className="flex flex-col md:flex-row items-center gap-4">
                {website && (
                  <Link
                    className="flex gap-4 text-blue-500 text-2xl"
                    href={website}
                    target="_blank"
                  >
                    <span className="truncate">{formatUrl(website)}</span>
                    <Image
                      src="/assets/images/icons/ion_open_outline.svg"
                      width={16}
                      height={16}
                      alt={APP_NAME}
                    />
                  </Link>
                )}
                <div className="flex gap-2">{renderTypeTags()}</div>
              </div>
            </div>
          </div>
        </Container>
      </section>
      <Section>
        <Container>
          <div className="flex max-w-5xl mx-auto">
            <div>
              <Title type="h3" color="primary">
                {section_about.before_title} {company}
              </Title>
              {client_description && <SubTitle>{client_description}</SubTitle>}
              <div className="grid grid-cols-1 md:grid-cols-3  gap-2 md:gap-8 py-8 border-b border-gray-100">
                {vat && (
                  <div>
                    <Title type="h5" color="primary">
                      {section_about.vat_label}
                    </Title>
                    <Paragraph>{vat}</Paragraph>
                  </div>
                )}
                {datecreated && (
                  <div>
                    <Title type="h5" color="primary">
                      {section_about.inscription_date_label}
                    </Title>
                    <Paragraph>{dateFormatter(datecreated)}</Paragraph>
                  </div>
                )}
              </div>
              <div className="grid grid-cols-1 md:grid-cols-3  gap-2 md:gap-8 py-8">
                {client_email && (
                  <div>
                    <Title type="h4" color="primary">
                      {section_about.email_label}
                    </Title>
                    <Paragraph>{client_email}</Paragraph>
                  </div>
                )}

                {phonenumber && (
                  <div>
                    <Title type="h4" color="primary">
                      {section_about.phone_label}
                    </Title>
                    <Paragraph>{phonenumber}</Paragraph>
                  </div>
                )}

                {address && (
                  <div>
                    <Title type="h4" color="primary">
                      {section_about.address_label}
                    </Title>
                    <Paragraph>
                      {addressConcater(
                        address,
                        city,
                        state,
                        country?.short_name
                      )}
                    </Paragraph>
                  </div>
                )}
              </div>
              <div className="py-4">
                <div className="flex justify-between items-center bg-red-50 p-4">
                  <Title type="h4" color="default">
                    {section_report.title}
                  </Title>
                  <Button
                    name={section_report.button.name}
                    type="white"
                    target="_blank"
                    url={urlToReport}
                  />
                </div>
              </div>
              {seals?.length > 0 ? (
                <div className="py-4">
                  <div>
                    <div>
                      <Title type="h4">{section_certifications.title}</Title>
                      <Paragraph>
                        {section_certifications.description}
                      </Paragraph>
                    </div>

                    <div className="flex flex-col gap-y-4">
                      {renderCertificateCards}
                    </div>
                  </div>
                </div>
              ) : (
                <div className="flex gap-4 p-20 bg-indotel-blue-900 rounded-lg">
                  <div>
                    <Image
                      src="/assets/images/icons/ion_warning-outline.svg"
                      alt={APP_NAME}
                      width={64}
                      height={64}
                    />
                  </div>
                  <div>
                    <Title type="h3" color="white">
                      {section_certifications.no_found.title}
                    </Title>
                    <Paragraph color="white">
                      {section_certifications.no_found.description}
                    </Paragraph>
                  </div>
                </div>
              )}
            </div>
          </div>
        </Container>
      </Section>

      <RelatedCommerceList
        typeID={type && type[0]?.id}
        commerceId={userid}
        title={section_related_commerces.title}
        description={relatedSeccionTitle}
      />

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
