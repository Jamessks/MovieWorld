/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./views/**/*.php",
    "./public/**/*.html",
    "./public/src/js/**/*.js",
    "./public/src/js/components/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        gainsboro: "#DCDCDC",
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};
