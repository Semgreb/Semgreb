import Link from "next/link";
import React from "react";

function MenuItem({ name, url }) {
  return (
    <li key={name}>
      <Link className="block p-4 hover:text-indotel-blue-900" href={url}>
        {name}
      </Link>
    </li>
  );
}

export default MenuItem;
