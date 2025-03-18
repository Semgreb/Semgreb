import React from "react";
import NumberBox from "./NumberBox";
import { getApiData } from "@/services/getApiData";
import { ENDPOINTS } from "@/config";

async function NumberBoxList() {
  const data = await getApiData(ENDPOINTS.commerceCounts);

  return (
    <div className="flex justify-center lg:justify-end gap-8">
      <NumberBox
        title={data?.total_certificate || 0}
        description={"Comercios certificados"}
      />
      <NumberBox
        title={data?.total_type_clients || 0}
        description={"Segmentos de mercado"}
      />
    </div>
  );
}

export default NumberBoxList;
