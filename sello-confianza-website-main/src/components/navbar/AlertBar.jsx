"use client";
import React, { useState } from "react";
import Image from "next/image";

function AlertBar() {
  const [showBar, setShowBar] = useState(false);

  const handleOnClic = () => setShowBar(!showBar);
  return (
    <div className="bg-indotel-blue-900 py-2">
      <div className=" container mx-auto px-4">
        <div className="flex">
          <div className="inline-flex items-center gap-2">
            <Image
              src="/assets/images/dominican-flag.svg"
              width={16}
              height={16}
              alt="SELLO DE CONFIANZA"
              className="h-4"
            />
            <p className="text-white text-xs">
              Esta es una web oficial del Gobierno de la República Dominicana
            </p>

            <button
              type="button"
              className="inline-flex gap-2 p-0 text-indotel-sky-900 text-xs underline  underline-offset-1"
              onClick={handleOnClic}
            >
              <span className="hidden md:block">
                Así es como puedes saberlo
              </span>
              <Image
                src="/assets/images/icons/ion_chevron-down-outline.svg"
                width={16}
                height={16}
                alt="SELLO DE CONFIANZA"
                className="h-4"
              />
            </button>
          </div>
        </div>
        {showBar && (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-12 py-8 text-xs text-white">
            <div className="inline-flex gap-4 items-center">
              <div className="flex items-center justify-center bg-indotel-sky-900 rounded-full h-10 w-10">
                <Image
                  src="/assets/images/icons/ion_cupula.svg"
                  alt="SELLO DE CONFIANZA"
                  width={16}
                  height={16}
                />
              </div>
              <div className="flex-1">
                <p className="font-bold py-2">
                  Los sitios web oficiales utilizan .gob.do, .gov.do o .mil.do
                </p>
                <p className="text-justify">
                  Un sitio .gob.do, .gov.do o .mil.do significa que pertenece a
                  una organización oficial del Estado dominicano.
                </p>
              </div>
            </div>
            <div className="flex gap-4 items-center">
              <div className="flex items-center justify-center bg-indotel-sky-900 rounded-full h-10 w-10 ">
                <Image
                  src="/assets/images/icons/ion_lock.svg"
                  alt="SELLO DE CONFIANZA"
                  width={16}
                  height={16}
                />
              </div>
              <div className="flex-1">
                <p className="font-bold py-2">
                  Los sitios web oficiales .gob.do, .gov.do o .mil.do seguros
                  usan HTTPS
                </p>
                <p className="text-justify">
                  Un candado (🔒) o https:// significa que estás conectado a un
                  sitio seguro dentro de .gob.do o .gov.do. Comparte información
                  confidencial solo en los sitios seguros de .gob.do o gov.do.
                </p>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

export default AlertBar;
