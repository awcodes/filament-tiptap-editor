const colors = require("tailwindcss/colors");

module.exports = {
  content: ["./resources/views/**/*.blade.php", "./src/**/*.php"],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
          danger: colors.rose,
          primary: colors.yellow,
          success: colors.green,
          warning: colors.amber,
      },
    },
  },
  corePlugins: {
    preflight: false,
  },
  plugins: [require("@tailwindcss/forms")],
};
