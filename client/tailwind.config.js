/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./src/**/*.{ts,tsx,scss}"
    ],
    darkMode: "class",
    theme: {
        extend: {},
    },
    plugins: [require("daisyui")]
};