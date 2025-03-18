import Button from "@/components/Button";
import SubTitle from "@/components/SubTitle";
import Title from "@/components/Title";
import React from "react";

function NoFound() {
  return (
    <div className="flex items-start justify-center flex-col py-40 max-w-3xl mx-auto">
      <Title type="h1" color="primary">
        ¡Ups! Parece que has llegado a un rincón inexplorado.
      </Title>
      <SubTitle>
        No te preocupes, incluso los navegantes más experimentados se desvían de
        vez en cuando. Estamos aquí para guiarte de vuelta al camino correcto.
      </SubTitle>
      <br />
      <Button name="Ir al inicio" rightIcon type="primary" url="/" />
    </div>
  );
}

export default NoFound;
