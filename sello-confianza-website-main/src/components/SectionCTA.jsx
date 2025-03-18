import React from "react";
import Button from "./Button";
import Title from "./Title";

function SectionCTA({ title, buttonText, url }) {
  return (
    <div className="py-20 text-center max-w-lg mx-auto">
      <Title type="h3" color="primary">
        {title}
      </Title>
      <Button name={buttonText} url={url} type="primary" rightIcon></Button>
    </div>
  );
}

export default React.memo(SectionCTA);
