import React from "react";

export default function CommerceListSkeleton() {
  const renderCard = (index) => (
    <div
      className="border border-gray-100 rounded-md max-w-sm w-full mx-auto"
      key={index}
    >
      <div className="animate-pulse flex-col space-x-4">
        <div className="bg-gray-200 h-48 w-full"></div>

        <div className="flex-1 space-y-2 py-4">
          <div className=" h-2 bg-gray-200 rounded max-w-[30%]"></div>
          <div className="h-4 bg-gray-200 rounded max-w-[80%]"></div>
          <div className="h-1 bg-gray-200 rounded max-w-[80%]"></div>
        </div>
      </div>
    </div>
  );
  const renderSkeletonCard = Array.from(Array(10).keys()).map(renderCard);

  return (
    <div className="flex overflow-x-auto snap-x snap-center sm:grid gap-4 gap-y-8 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-6">
      {renderSkeletonCard}
    </div>
  );
}
