import React from "react";

export default function SearchSkeleton() {
  return (
    <div className="border border-gray-100 rounded-md max-w-sm w-full mx-auto">
      <div className="animate-pulse flex space-x-4">
        <div className="flex-1 p-2 py-4">
          <div className="h-4 bg-gray-200 rounded max-w-[80%]"></div>
        </div>
        <div className="bg-gray-200 w-20"></div>
      </div>
    </div>
  );
}
