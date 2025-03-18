"use client";
import { WAIT_FOR_FETCH } from "@/config";
import Image from "next/image";
import { useSearchParams, usePathname, useRouter } from "next/navigation";
import { useDebouncedCallback } from "use-debounce";
function Search() {
  const searchParams = useSearchParams();
  const pathname = usePathname();
  const { replace } = useRouter();

  const defaultSearchValue = searchParams.get("busqueda")?.toString();

  const handlerOnChange = useDebouncedCallback((event) => {
    const value = event.target.value;
    const params = new URLSearchParams(searchParams);

    if (value) {
      params.set("busqueda", value);
    } else {
      params.delete("busqueda");
    }

    replace(`${pathname}?${params.toString()}`);
  }, WAIT_FOR_FETCH);

  return (
    <div className="inline-flex justify-between border border-gray-100 w-full">
      <input
        type="search"
        name="commerce_search"
        className=" px-4 py-2 w-full focus:outline-none focus:border-indotel-blue-900 focus:border"
        onChange={handlerOnChange}
        defaultValue={defaultSearchValue}
      />
      <div className="grid place-content-center px-8 bg-indotel-blue-900">
        <Image
          src="/assets/images/icons/ion_search.svg"
          width={16}
          height={16}
          alt="Buscar comercios"
        />
      </div>
    </div>
  );
}

export default Search;
