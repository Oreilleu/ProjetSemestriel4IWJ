/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: { 
        'primary': {
          '50': '#faf6f2',
          '100': '#f4ebe0',
          '200': '#e9d6bf',
          '300': '#dabb97',
          '400': '#c79364',
          '500': '#bf8050',
          '600': '#b16d45',
          '700': '#93563b',
          '800': '#774735',
          '900': '#613b2d',
          '950': '#341e16',
      },
        'secondary': {
          '50': '#fbfaeb',
          '100': '#f6f1cb',
          '200': '#efe199',
          '300': '#e5cc5f',
          '400': '#dcb533',
          '500': '#cc9f26',
          '600': '#b07d1e',
          '700': '#8d5b1b',
          '800': '#764a1e',
          '900': '#643e1f',
          '950': '#3a200e',
      },
        'color3': {
          '50': '#f7f7ef',
          '100': '#ecead5',
          '200': '#dbd4ad',
          '300': '#c5b97f',
          '400': '#b4a15b',
          '500': '#a58f4d',
          '600': '#8e7440',
          '700': '#725836',
          '800': '#614a32',
          '900': '#54402f',
          '950': '#312319',
      },
        'color4': {
          '50': '#f9f6f3',
          '100': '#e9e2d8',
          '200': '#e0d5c8',
          '300': '#ccbaa5',
          '400': '#b69a81',
          '500': '#a78368',
          '600': '#9a725c',
          '700': '#805d4e',
          '800': '#694e43',
          '900': '#564138',
          '950': '#2d201d',
      },
        'color5': {
          '50': '#fefbe8',
          '100': '#fff8c2',
          '200': '#ffec89',
          '300': '#ffde59',
          '400': '#fdc512',
          '500': '#ecab06',
          '600': '#cc8302',
          '700': '#a35c05',
          '800': '#86480d',
          '900': '#723b11',
          '950': '#431e05',
      },
        success: '#28A745',
        danger: '#DC3545',
        warning: '#FFC107',
        customColor: '#312319',
      },
      fontFamily: {
        'title': ['"Open Sans"', 'sans-serif'],
        'subtitle': ['"Montserrat"', 'sans-serif'],
        'body': ['"Istok Web"', 'sans-serif'],
      },
    },
  },
  variants: {
    extend: {
      borderColor: ['hover', 'active', 'slide_active'],  // Ajout des variantes
    },
  },
  plugins: [
    function ({ addVariant, e }) {
      addVariant('slide_active', ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => {
          return `.swiper-slide.swiper-slide-active .${e(`slide_active${separator}${className}`)}`
        })
      })
    },
  ],
}