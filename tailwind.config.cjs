const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors:{
                'gray-450': '#a0a29f',
                'gray-550': '#7c7f7c',
                'blue-950': '#090430',
                'accent': '#e085f9', //previous #FFD600
                'blue-850': '#021127',
                'blue-750': '#010E21',
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
    ],
};
