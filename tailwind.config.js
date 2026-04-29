/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./**/*.php",
    "./views/**/*.{html,js}",
  ],
  theme: {
    extend: {
      colors: {
        // UST Color Palette
        'ust': {
          'gold': '#F4C300',
          'gold-dark': '#D4A400',
          'gold-light': '#FED766',
          'dark': '#1A1A1A',
          'gray': '#333333',
          'light-bg': '#F9F7F3',
          'cream': '#FBF8F3',
        },
      },
      fontFamily: {
        'heading': ['Outfit', 'sans-serif'],
        'body': ['Inter', 'sans-serif'],
      },
      boxShadow: {
        'ust': '0 2px 8px rgba(26, 26, 26, 0.08)',
        'ust-md': '0 4px 12px rgba(26, 26, 26, 0.12)',
      },
    },
  },
  plugins: [],
}

