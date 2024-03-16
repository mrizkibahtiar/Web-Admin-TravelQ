/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./src/**/*.{html,js,php,css}', './*.{html,js,php,css}', './**/*.{html,js,css,php}', 'node_modules/preline/dist/*.js'],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        'poppins': ['Poppins', 'sans serif']
      },
    },
  },
  plugins: [
    // require('@tailwindcss/forms'),      
    require('preline/plugin'),
  ],
}

