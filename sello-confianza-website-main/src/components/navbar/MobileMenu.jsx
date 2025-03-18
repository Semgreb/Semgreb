"use client";

import React, { useEffect, useState } from "react";
import Image from "next/image";
import Button from "../Button";
import MenuItemList from "./MenuItemList";

import navData from "/public/assets/data/navbar-data.json";
import { usePathname } from "next/navigation";

function MobileMenu() {
  const [showMenu, setShowMenu] = useState(false);
  const handlerMenu = () => setShowMenu(!showMenu);

  const pathname = usePathname();

  useEffect(() => {
    setShowMenu(false);

    return () => {};
  }, [pathname]);

  return (
    <div>
      <div className="block lg:hidden" onClick={handlerMenu}>
        {showMenu ? (
          <Image
            width={24}
            height={24}
            alt="SELLO DE CONFIANZA"
            src="/assets/images/icons/ion_times.svg"
          />
        ) : (
          <Image
            width={24}
            height={24}
            alt="SELLO DE CONFIANZA"
            src="/assets/images/icons/ion_hamburger.svg"
          />
        )}
      </div>

      {showMenu && (
        <div className="block lg:hidden p-8 absolute inset-x-0 bg-white shadow-md m-4">
          <ul className="flex flex-col ">
            <MenuItemList />
          </ul>
          <div className="flex flex-col border-t">
            <Button
              name={navData.nav_auth_links[0].name}
              rightIcon
              url={navData.nav_auth_links[0].url}
              target="_blank"
            />
            <Button
              name={navData.nav_auth_links[1].name}
              type="primary"
              rightIcon
              url={navData.nav_auth_links[1].url}
            />
          </div>
        </div>
      )}
    </div>
  );
}

export default MobileMenu;
