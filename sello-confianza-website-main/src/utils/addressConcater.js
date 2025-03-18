export function addressConcater(city, state, address, country) {
  const data = [city, state, address, country];
  const concater = data.join(", ");

  return concater + ".";
}
