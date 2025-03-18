export const {
  API_URL,
  APP_NAME,
  API_USER_NAME,
  API_USER_PASSWORD,
  SEAL_REQUEST_URL,
  FETCH_REVALIDATION = 3000,
  COMMERCE_REPORT_URL,
  CLIENT_LOGIN_PAGE,
  HOST_NAME,
} = process.env;
export const FAKE_TEXT = "Lorem Ipsumn";

export const WAIT_FOR_FETCH = 300;
export const PAGINATION_LIMIT = 12;
export const DEFAULT_IMAGE = "/assets/images/default-image.png";
export const ENDPOINTS = {
  sealList: `${API_URL}/trust_seal/api/seals`,
  sealDetail: "",
  commerceList: `${API_URL}/trust_seal/api/clients`,
  commerceListRelated: `${API_URL}/trust_seal/api/clients`,
  commerceDetail: `${API_URL}/trust_seal/api/client_profile_slug?slug=`,
  articleList: `${API_URL}/trust_seal/api/articles`,
  articleDetail: `${API_URL}/trust_seal/api/find_article?slug=`,
  articleListByGroup: `${API_URL}/trust_seal/api/article_by_group`,
  articleGroupList: `${API_URL}/trust_seal/api/article_group_list`,
  articleGroupDetail: `${API_URL}/trust_seal/api/group_details?slug=`,
  commerceCounts: `${API_URL}/trust_seal/api/count_certificates_customers`,
};
