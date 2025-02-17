/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      boxShadow: {
        'custom': '0 0px 6px 8px ',
      },
      fontFamily:{
        'manrope': ['manrope', 'sans-serif']
      },
      colors:{
        'light-green': '#E0F0EA',
        'light-grey': '#95ADBE',
        'light-purple': '#574F7D',
        'purple': '#503A65',
        'dark-purple' : '#3C2A4D',
        'black' : '#121212',
        'white' : '#F0F0F0'
      },
      keyframes:{
        custom_pulse:{
          '0%': { backgroundColor: 'rgba(220,38,38,0.75)' },
          '50%': { backgroundColor: 'rgba(200,38,38,0)' },
          '100%': { backgroundColor: 'rgba(220,38,38,0.25)' },
        }
      },
      animation:{
        custom_pulse: 'custom_pulse 2s 3'
      }
    },
  },
  plugins: [
    require('tailwindcss-animated')
  ],
}