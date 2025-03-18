import React from "react";
import Title from "@/components/Title";
import Paragraph from "@/components/Paragraph";
import { dateFormatter } from "@/utils/formatters";
import Image from "next/image";
import { createMarkup } from "@/utils/createMarkup";

function CertificateCard({
  title,
  description,
  image_url,
  date_expiration,
  uid,
}) {
  return (
    <div className="flex justify-between items-center border border-gray-100 p-4 md:p-16">
      <div className="flex flex-col md:flex-row md:items-center gap-4">
        <div>
          <Image src={image_url} width={150} height={150} alt={title} />
        </div>
        <div className="max-w-xl">
          <Title type="h4" color="primary">
            {title}
          </Title>
          <div
            className="text-gray-700"
            dangerouslySetInnerHTML={createMarkup(description)}
          />
          <div className="flex md:items-center flex-col md:flex-row gap-4 mt-4">
            <Paragraph>No. {uid}</Paragraph>
            <Paragraph color="primary">
              Vence el {dateFormatter(date_expiration)}
            </Paragraph>
          </div>
        </div>
      </div>
    </div>
  );
}

export default CertificateCard;
