/** @type {import('tailwindcss').Config} */
module.exports = {
    mode: 'jit',
    content: [
        "./resources/views/**/*.blade.php",
        "../../resources/themes/**/views/**/*.blade.php",
        "../../resources/views/**/*.blade.php",
        "../../plugins/**/resources/views/**/*.blade.php"
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
