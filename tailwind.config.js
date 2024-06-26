/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: { 
        'background-dark-mode': {
          '50': '#f6f6f6',
          '100': '#e7e7e7',
          '200': '#d1d1d1',
          '300': '#b0b0b0',
          '400': '#888888',
          '500': '#6d6d6d',
          '600': '#5d5d5d',
          '700': '#4f4f4f',
          '800': '#454545',
          '900': '#3d3d3d',
          '950': '#121212',
        },
        'element-dark-mode': {
          '50': '#f6f6f6',
          '100': '#e7e7e7',
          '200': '#d1d1d1',
          '300': '#b0b0b0',
          '400': '#888888',
          '500': '#6d6d6d',
          '600': '#5d5d5d',
          '700': '#4f4f4f',
          '800': '#454545',
          '900': '#3d3d3d',
          '950': '#1f1f1f',
        },
        'text-dark-mode': {
          '50': '#f7f7f7',
          '100': '#ededed',
          '200': '#e0e0e0',
          '300': '#c8c8c8',
          '400': '#adadad',
          '500': '#999999',
          '600': '#888888',
          '700': '#7b7b7b',
          '800': '#676767',
          '900': '#545454',
          '950': '#363636',
        },
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
        'bleu-theme': {
          '50': '#f0f6fe',
          '100': '#deeafb',
          '200': '#c5dcf8',
          '300': '#9dc7f3',
          '400': '#62a0ea',
          '500': '#4c87e5',
          '600': '#376bd9',
          '700': '#2e57c7',
          '800': '#2c48a1',
          '900': '#284080',
          '950': '#1d284e',
      },
        success: '#28A745',
        danger: '#DC3545',
        dangerDark: '#C82333',
        warning: '#FFC107',
        customColor: '#312319',
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
    function ({ addVariant, e }) {
      addVariant('bleu', ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => {
          return `.bleu .${e(`bleu${separator}${className}`)}`
        })
      })
    },
  ],
}