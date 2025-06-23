/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/**/*.{js,ts,jsx,tsx}",     // Untuk folder app Next.js 13+
    "./pages/**/*.{js,ts,jsx,tsx}",   // Jika pakai folder pages
    "./components/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
