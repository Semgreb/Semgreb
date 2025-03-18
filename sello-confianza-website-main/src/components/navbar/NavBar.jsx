import Container from "@/components/Container";
import Button from "@/components/Button";
import MobileMenu from "@/components/navbar/MobileMenu";
import MenuItemList from "@/components/navbar/MenuItemList";
import Link from "next/link";
import { APP_NAME } from "@/config";
import Image from "next/image";
import navData from "/public/assets/data/navbar-data.json";
import NavBarSkeleton from "../skeleton/NavBarSkeleton";

function NavBar() {
  const { logo_path, nav_auth_links } = navData;

  if (!navData) {
    return <NavBarSkeleton />;
  }

  return (
    <nav className="py-2 mb-8">
      <Container>
        <div className="flex justify-between items-center">
          <div>
            <Link href="/">
              <Image
                src={logo_path}
                alt={APP_NAME}
                className="h-16"
                width={200}
                height={100}
              />
            </Link>
          </div>
          <div className="hidden lg:block">
            <ul className="flex">
              <MenuItemList />
            </ul>
          </div>
          <div>
            <div className="hidden lg:block">
              <Button
                name={nav_auth_links[0].name}
                url={nav_auth_links[0].url}
                target="_blank"
              />
              <Button
                name={nav_auth_links[1].name}
                type="primary"
                rightIcon
                url={nav_auth_links[1].url}
              />
            </div>
            <MobileMenu />
          </div>
        </div>
      </Container>
    </nav>
  );
}

export default NavBar;
