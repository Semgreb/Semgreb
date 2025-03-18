import React from "react";
import Container from "../Container";

export default function PageSkeleton() {
  return (
    <section className="animate-pulse py-20">
      <Container>
        <div className="flex flex-col gap-4 max-w-4xl">
          <div className="h-8 bg-gray-200 rounded max-w-[80%]" />
          <div className="h-4 bg-gray-200 rounded max-w-[50%]" />
          <div className="h-4 bg-gray-200 rounded max-w-[30%]" />
          <div className="h-4 bg-gray-200 rounded max-w-[10%]" />
        </div>
      </Container>
    </section>
  );
}
