import React from "react";
import Container from "../Container";

export default function NavBarSkeleton() {
  return (
    <Container>
      <div className="animate-pulse flex items-center justify-between py-2">
        <div className="flex gap-4">
          <div className="bg-gray-200 w-20 h-20 rounded-full py-2" />
          <div className="bg-gray-200 w-20 h-20 rounded-full py-2" />
        </div>
        <div className="flex-1 p-2 py-4">
          <div className="h-4 bg-gray-200 rounded max-w-[80%]"></div>
        </div>
        <div className="bg-gray-200 w-40 h-10 rounded-md"></div>
      </div>
    </Container>
  );
}
