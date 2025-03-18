import React from "react";
import Container from "./Container";
import Title from "./Title";
import Row from "./Row";
import SubTitle from "./SubTitle";

function PageHeader({
  title = "Titulo de la página",
  children,
  subTitle,
  rightComponent,
}) {
  return (
    <header className="bg-indotel-red-900/5 py-20">
      <Container>
        <Row>
          <div className="flex flex-col gap-4 max-w-4xl">
            <Title type="h1" color="primary">
              {title}
            </Title>
            <SubTitle>{subTitle}</SubTitle>
            {children ? <div>{children}</div> : null}
          </div>
          {rightComponent ? <div>{rightComponent}</div> : null}
        </Row>
      </Container>
    </header>
  );
}

export default React.memo(PageHeader);
