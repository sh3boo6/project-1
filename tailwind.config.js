/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./layouts/**/*.php",
    "./pages/**/*.php",
    "./assets/js/**/*.js",
    "./index.php"
  ],
  theme: {
    extend: {
      colors: {
        primary: "#000fff",
        secondary: "#ffed4a",
        danger: "#e3342f",
        success: "#38c172",
        info: "#3490dc",
        warning: "#f6993f",
        dark: "#343a40",
        light: "#f8f9fa"
      },
      fontFamily: {
        sans: ["Graphik", "sans-serif"],
        serif: ["Merriweather", "serif"],
        mono: ["Menlo", "Monaco", "Consolas", "monospace"]
      },
      spacing: {
        '72': '18rem',
        '84': '21rem',
        '96': '24rem',
      },
      borderRadius: {
        'xs': '0.125rem',
        'xl': '1rem',
        '2xl': '2rem',
      },
      fontSize: {
        'xxs': '0.625rem',
        '7xl': '4.5rem',
        '8xl': '6rem',
      },
      boxShadow: {
        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
        'hard': '0 10px 15px -3px rgba(0, 0, 0, 0.2), 0 4px 6px -2px rgba(0, 0, 0, 0.1)',
      }
    },
    screens: {
      'xs': '475px',
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
      '2xl': '1536px',
    }
  },
  plugins: [],
}