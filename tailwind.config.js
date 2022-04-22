const colors = require("tailwindcss/colors");

module.exports = {
  content: ["./resources/views/**/*.blade.php", "./src/**/*.php"],
  darkMode: "class",
  theme: {
    extend: {},
  },
  plugins: [require("@tailwindcss/forms")],
};
