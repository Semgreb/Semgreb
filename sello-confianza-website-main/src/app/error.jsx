"use client"; // Error components must be Client Components

import Button from "@/components/Button";
import SubTitle from "@/components/SubTitle";
import Title from "@/components/Title";
import { useEffect } from "react";

export default function Error({ error, reset }) {
  useEffect(() => {
    // Log the error to an error reporting service
    console.error(error);
  }, [error]);

  return (
    <div className="flex items-start justify-center flex-col py-40 max-w-3xl mx-auto">
      <Title type="h1" color="primary">
        ¡Ups! Ha ocurrido un error
      </Title>
      <SubTitle>
        Lo sentimos, hubo un error inesperado. Por favor, intenta recargar la
        página o vuelve más tarde.
      </SubTitle>
      <br />
      <Button
        name="Intentar nuevamente"
        rightIcon
        type="primary"
        onClick={() => window.location.reload()}
      />
    </div>
  );
}
