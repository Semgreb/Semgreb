import React from "react";
import CommerceCard from "./CommerceCard";
import { DEFAULT_IMAGE, ENDPOINTS } from "@/config";
import Title from "@/components/Title";
import Paragraph from "@/components/Paragraph";
import Section from "@/components/Section";
import Container from "@/components/Container";
import { getApiData } from "@/services/getApiData";

async function RelatedCommerceList({ typeID, commerceId, title, description }) {
  const endpoint = `${
    ENDPOINTS.commerceListRelated
  }?group=${typeID}&limit=${6}&page=${1}&exclude_commerce=${commerceId}`;

  const data = await getApiData(endpoint);
  // console.log("RelatedCommerceList.data:", data);

  const renderCommerce = (commerce) => {
    const commerceType = commerce.type && commerce.type[0]?.name;
    return (
      <CommerceCard
        key={commerce.userid}
        company={commerce.company}
        website={commerce.website}
        slug={commerce.slug}
        logo={commerce.logo || DEFAULT_IMAGE}
        type={commerceType}
      />
    );
  };
  const commerces = data?.data?.map(renderCommerce);

  if (data?.total === 0) return;
  return (
    <Section>
      <Container>
        <div>
          <Title type="h4">{title}</Title>
          <Paragraph>{description}</Paragraph>
        </div>
        <div className="flex overflow-x-auto snap-x snap-center sm:grid gap-4 gap-y-8 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6">
          {commerces}
        </div>
      </Container>
    </Section>
  );
}

export default RelatedCommerceList;
