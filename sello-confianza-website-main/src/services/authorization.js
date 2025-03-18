import { API_USER_PASSWORD, API_USER_NAME } from "@/config";

export const authorization = Buffer.from(
  `${API_USER_NAME}:${API_USER_PASSWORD}`
).toString("base64");
