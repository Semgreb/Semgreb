import React from "react";
import Image from "next/image";
import Button from "../Button";
import Container from "../Container";
import SubTitle from "../SubTitle";
import Title from "../Title";

function FooterBanner({
  title,
  subtitle,
  buttonText,
  buttonUrl = "/",
  bullets,
  backgroundUrl,
}) {
  function renderBullets() {
    const renderBullet = (bullet) => (
      <SubTitle color="white" key={bullet}>
        <Image
          src="/assets/images/icons/ion_check.svg"
          alt="text alt"
          width={20}
          height={20}
          className="inline-flex mx-2"
        />
        {bullet}
      </SubTitle>
    );

    const result = bullets.map(renderBullet);
    return result;
  }

  const bulletList = renderBullets();

  return (
    <section className="-mb-24  relative">
      <Container>
        <div className="bg-white  pb-4 pl-8  lg:pb-8 -ml-8 mr-8">
          <div
            className="bg-no-repeat -mr-8 bg-center"
            style={{ backgroundImage: `url(${backgroundUrl})` }}
          >
            <div className="p-10 lg:py-24 lg:px-40 bg-gradient-to-r from-indotel-blue-900">
              <div className="max-w-xl">
                <Title type="h2" color="white">
                  {title}
                </Title>
                <SubTitle color="white">{subtitle}</SubTitle>
                <div className="py-8">{bulletList}</div>
                <Button
                  url={buttonUrl}
                  name={buttonText}
                  rightIcon
                  type="white"
                />
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>
  );
}

export default FooterBanner;
