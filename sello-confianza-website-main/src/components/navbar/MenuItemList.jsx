import React from "react";
import MenuItem from "./MenuItem";
import navData from "/public/assets/data/navbar-data.json";

function MenuItemList() {
  const { nav_links } = navData;

  const renderLink = ({ name, url }) => (
    <MenuItem key={name} name={name} url={url} />
  );

  const result = nav_links?.map(renderLink);

  return result;
}

export default MenuItemList;
