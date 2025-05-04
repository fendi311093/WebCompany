const colors = require('tailwindcss/colors')
const preset = require('./vendor/filament/filament/tailwind.config.preset')

/** @type {import('tailwindcss').Config} */
module.exports = {
  presets: [preset],
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Filament/**/*.php",
    "./resources/views/filament/**/*.blade.php",
    "./vendor/filament/**/*.blade.php",
    'node_modules/preline/dist/*.js',
  ],
  theme: {
    extend: {
      colors: {
        gray: colors.gray,
        blue: colors.blue,
        cyan: colors.cyan,
        slate: colors.slate,
      },
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('preline/plugin'),
  ],
  darkMode: 'class',
} 