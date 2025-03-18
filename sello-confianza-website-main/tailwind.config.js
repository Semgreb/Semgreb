/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  // purge: [
  //   "./src/components/**/*.{js,ts,jsx,tsx}",
  //   "./src/pages/**/*.{js,ts,jsx,tsx}",
  // ],
  theme: {
    extend: {
      backgroundImage: {
        "hero-pattern": "url('/assets/images/header_pattern.svg')",
      },
      gridTemplateColumns: {
        responsive: "repeat(auto-fit, minmax(250px,1fr))",
        // responsive: "1fr repeat(auto-fit)",
      },
      colors: {
        "indotel-blue-900": "#00205C",
        "indotel-sky-900": "#00AEEF",
        "indotel-red-900": "#B2292E",
      },
    },
  },
  plugins: [],
};
