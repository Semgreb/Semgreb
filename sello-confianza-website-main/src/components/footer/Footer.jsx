import React from "react";
import Link from "next/link";
import Image from "next/image";
import FooterWidgetLinks from "./FooterWidgetLinks";
import Title from "../Title";
import Paragraph from "../Paragraph";
import { APP_NAME } from "@/config";
import footerData from "/public/assets/data/footer-data.json";
import FooterSkeleton from "../skeleton/FooterSkeleton";

function Footer() {
  const { widgets, copyright_links, section_contact } = footerData;

  if (!footerData) {
    return <FooterSkeleton />;
  }

  const renderWidget = (widget) => (
    <div key={widget.title}>
      <FooterWidgetLinks data={widget} />
    </div>
  );
  const renderWidgets = widgets?.map(renderWidget);

  const renderLink = (link) => (
    <Link
      href={link.url}
      target="_blank"
      className="hover:text-indotel-sky-900/50 pr-4 last:pr-0 border-r border-indotel-sky-900/50 last:border-r-0"
      key={link.name}
    >
      {link.name}
    </Link>
  );
  const renderCopyrightLinks = copyright_links.map(renderLink);

  const contactText = `Tel: ${section_contact.phone} / ${section_contact.email}`;

  return (
    <footer className="bg-indotel-blue-900 pt-20 py-6">
      <div className="max-w-4xl mx-auto pt-20 px-4">
        <div className="grid gap-4 grid-cols-2 lg:grid-cols-4 justify-between pb-20">
          {renderWidgets}
        </div>
        <div className="flex justify-center items-center flex-col md:flex-row gap-12 py-10">
          <Image
            src={section_contact.app_logo_path}
            width={150}
            height={150}
            alt={APP_NAME}
          />
          <Image
            src={section_contact.presidence_logo_path}
            width={150}
            height={150}
            alt={APP_NAME}
          />
          <Image
            src={section_contact.indotel_logo_path}
            width={150}
            height={150}
            alt={APP_NAME}
          />
        </div>

        <div className="text-center py-4">
          <Title type="h5" color="white">
            {section_contact.title}
          </Title>
          <Paragraph color="white">
            {section_contact.address}
            <br />
            {contactText}
          </Paragraph>
        </div>

        <div className="text-center pb-10 text-indotel-sky-900 text-sm">
          <div className="flex justify-center gap-2 ">
            {renderCopyrightLinks}
          </div>
        </div>
      </div>
    </footer>
  );
}

export default Footer;
