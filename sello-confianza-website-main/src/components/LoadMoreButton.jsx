"use client";

import React from "react";
import { PAGINATION_LIMIT, WAIT_FOR_FETCH } from "@/config";
import { useDebouncedCallback } from "use-debounce";
import { usePathname, useSearchParams, useRouter } from "next/navigation";

const LIMIT_KEY = "limit";
function LoadMoreButton({ title = "Cargar más elementos" }) {
  const searchParams = useSearchParams();
  const pathname = usePathname();
  const { replace } = useRouter();

  const handlerOnClick = useDebouncedCallback(() => {
    const params = new URLSearchParams(searchParams);

    if (params.has(LIMIT_KEY)) {
      const queryLimit = params.get(LIMIT_KEY).toString();
      const newLimit = Number(queryLimit) + PAGINATION_LIMIT;
      params.set(LIMIT_KEY, newLimit);
    } else {
      params.set(LIMIT_KEY, PAGINATION_LIMIT);
    }

    replace(`${pathname}?${params.toString()}`);
  }, WAIT_FOR_FETCH);

  return (
    <div className="py-20 flex justify-center">
      <button
        className="inline-flex py-4 px-6  bg-indotel-blue-900 text-white  items-center"
        onClick={handlerOnClick}
      >
        {title}
      </button>
    </div>
  );
}

export default LoadMoreButton;
