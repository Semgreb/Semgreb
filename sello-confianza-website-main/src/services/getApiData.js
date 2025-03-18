import { cache } from "react";
import "server-only";
import { authorization } from "@/services/authorization";
import { FETCH_REVALIDATION } from "@/config";

export const preload = (endpoint, param) => {
  void getApiData(endpoint, param);
};

export const getApiData = cache(async (endpoint, params) => {
  const fetchOptions = {
    next: { revalidate: FETCH_REVALIDATION },
    headers: {
      Authorization: `Basic ${authorization}`,
    },
  };

  try {
    const endpointProcessed = params?.toString()
      ? `${endpoint}?${params.toString()}`
      : endpoint;

    const response = await fetch(endpointProcessed, fetchOptions);

    if (!response.ok) {
      return { isError: true };
    }
    const data = await response.json();

    return data;
  } catch (error) {
    // console.log("Response error:", error);
  }
});
