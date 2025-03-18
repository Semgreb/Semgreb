import React from "react";

function SealListSkeleton() {
  const renderCard = (index) => (
    <div className="animate-puls items-center flex gap-4 mb-4" key={index}>
      <div className="bg-gray-200 w-20 h-20 rounded-full py-2" />
      <div className="flex flex-col gap-2 grow">
        <div className="h-4 bg-gray-200 rounded w-[50%]" />
        <div className="h-4 bg-gray-200 rounded w-[20%]" />
      </div>
    </div>
  );
  const renderSkeletonCard = Array.from(Array(5).keys()).map(renderCard);

  return renderSkeletonCard;
}

export default SealListSkeleton;
