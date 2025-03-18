import { Montserrat } from "next/font/google";
import NavBar from "@/components/navbar/NavBar";
import Footer from "@/components/footer/Footer";
import "./globals.css";
import AlertBar from "@/components/navbar/AlertBar";

const montserrat = Montserrat({ subsets: ["latin"] });

export default function RootLayout({ children }) {
  return (
    <html lang="es" className="bg-hero-pattern bg-right-top bg-no-repeat">
      <body className={montserrat.className}>
        <AlertBar />
        <NavBar />
        {children}
        <Footer />
      </body>
    </html>
  );
}
