import React from "react";
import Container from "../Container";

export default function FooterSkeleton() {
  return (
    <section className="animate-pulse bg-gray-100 py-20 mt-20">
      <Container>
        <div className="grid grid-cols-2 lg:grid-cols-4 mb-20">
          <div className="flex flex-col gap-2">
            <div className="h-6 bg-gray-200 rounded w-[50%] mb-6" />
            <div className="h-4 bg-gray-200 rounded w-[80%]" />
            <div className="h-4 bg-gray-200 rounded w-[50%]" />
            <div className="h-4 bg-gray-200 rounded w-[20%]" />
          </div>
          <div className="flex flex-col gap-2">
            <div className="h-6 bg-gray-200 rounded w-[50%] mb-6" />
            <div className="h-4 bg-gray-200 rounded w-[80%]" />
            <div className="h-4 bg-gray-200 rounded w-[50%]" />
            <div className="h-4 bg-gray-200 rounded w-[20%]" />
          </div>
          <div className="flex flex-col gap-2">
            <div className="h-6 bg-gray-200 rounded w-[50%] mb-6" />
            <div className="h-4 bg-gray-200 rounded w-[80%]" />
            <div className="h-4 bg-gray-200 rounded w-[50%]" />
            <div className="h-4 bg-gray-200 rounded w-[20%]" />
          </div>
          <div className="flex flex-col gap-2">
            <div className="h-6 bg-gray-200 rounded w-[50%] mb-6" />
            <div className="h-4 bg-gray-200 rounded w-[80%]" />
            <div className="h-4 bg-gray-200 rounded w-[50%]" />
            <div className="h-4 bg-gray-200 rounded w-[20%]" />
          </div>
        </div>

        <div className="flex justify-center items-center flex-col md:flex-row gap-12 py-10">
          <div className="bg-gray-200 w-20 h-20 rounded-full py-2" />
          <div className="bg-gray-200 w-20 h-20 rounded-full py-2" />
          <div className="bg-gray-200 w-20 h-20 rounded-full py-2" />
        </div>
        <div className="flex flex-col gap-2 items-center grow">
          <div className="h-4 bg-gray-200 rounded w-[50%]" />
          <div className="h-4 bg-gray-200 rounded w-[30%]" />
          <div className="h-4 bg-gray-200 rounded w-[10%]" />
        </div>
      </Container>
    </section>
  );
}
