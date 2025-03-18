import Link from "next/link";
import React from "react";
import Title from "../Title";

function FooterWidgetLink({ data }) {
  function renderWidget({ name, url }) {
    return (
      <li key={name}>
        <Link
          href={url}
          className="block text-indotel-sky-900 py-1"
          target="_blank"
        >
          {name}
        </Link>
      </li>
    );
  }
  const links = data?.links.map(renderWidget);
  return (
    <div>
      <Title type="h4" color="white">
        {data.title}
      </Title>

      <ul>{links}</ul>
    </div>
  );
}

export default React.memo(FooterWidgetLink);
