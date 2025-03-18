import React, { Suspense } from "react";
import CommerceCard from "./CommerceCard";
import { DEFAULT_IMAGE, ENDPOINTS } from "@/config";
import { getApiData } from "@/services/getApiData";
import LoadMoreButton from "@/components/LoadMoreButton";
import NoResults from "@/components/NoResults";
import CommerceListSkeleton from "@/components/skeleton/CommerceListSkeleton";
import Error from "@/app/error";

async function CommerceList({ showLoadMore, searchParams, isMobile = false }) {
  const params = new URLSearchParams(searchParams);
  const endpoint = ENDPOINTS.commerceList;
  const response = await getApiData(endpoint, params);

  if (response?.isError) return <Error />;

  const renderCommerce = (commerce) => {
    const commerceType = commerce.type && commerce.type[0]?.name;
    return (
      <CommerceCard
        key={commerce.userid}
        company={commerce.company}
        website={commerce.website}
        slug={commerce.slug}
        logo={commerce.logo || DEFAULT_IMAGE}
        type={commerceType}
      />
    );
  };
  const commerces = response?.data?.map(renderCommerce);

  return (
    <Suspense fallback={<CommerceListSkeleton />}>
      {response && response?.data?.length === 0 ? (
        <NoResults />
      ) : (
        <div
          className={`${
            isMobile
              ? "flex overflow-x-auto snap-x snap-center gap-2"
              : "grid gap-4 gap-y-8 grid-cols-2 "
          } md:grid md:grid-cols-4 xl:grid-cols-6`}
        >
          {commerces}
          {showLoadMore &&
            response &&
            response?.dat?.length < response?.total && (
              <LoadMoreButton title="Cargar más comercios" />
            )}
        </div>
      )}
    </Suspense>
  );
}

export default CommerceList;
