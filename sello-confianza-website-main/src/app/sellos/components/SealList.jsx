import React, { Suspense } from "react";
import SealCard from "./SealCard";
import { getApiData } from "@/services/getApiData";
import { DEFAULT_IMAGE, ENDPOINTS, SEAL_REQUEST_URL } from "@/config";
import SealListSkeleton from "@/components/skeleton/SealListSkeleton";
import Error from "@/app/error";
import NoResults from "@/components/NoResults";

async function SealList() {
  const seals = await getApiData(ENDPOINTS.sealList);

  if (seals?.isError) return <Error />;
  const sealPath = SEAL_REQUEST_URL;

  function renderSeal(seal) {
    return (
      <SealCard
        key={seal.nui}
        title={seal.title}
        description={seal.description}
        image_url={seal.image_url || DEFAULT_IMAGE}
        buttonName="Solicitar"
        url={sealPath}
      />
    );
  }

  const renderSealCards = seals?.map(renderSeal);
  return (
    <Suspense fallback={<SealListSkeleton />}>
      {seals && seals?.length === 0 ? (
        <NoResults />
      ) : (
        <div className="">
          <div className="flex flex-col gap-y-4">{renderSealCards}</div>
        </div>
      )}
    </Suspense>
  );
}

export default SealList;
