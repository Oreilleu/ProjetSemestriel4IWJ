/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#C79364',
        secondary: '#764A1E', 
        color3: '#312319',
        color4: '#E9E2D8',
        color5: '#FFDE59',
        success: '#28A745',
        danger: '#DC3545',
        warning: '#FFC107',
      },
    },
    plugins: [],
  }
}