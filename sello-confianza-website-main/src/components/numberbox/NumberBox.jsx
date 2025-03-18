import React from "react";
import Paragraph from "../Paragraph";

function NumberBox({ title, description }) {
  return (
    <div className="text-center">
      <span className="text-6xl font-bold text-indotel-red-900">{title}</span>
      <Paragraph>{description}</Paragraph>
    </div>
  );
}

export default NumberBox;
