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
                stone: {
                    150: '#ebe9e7',
                },
                'gray-450': '#a0a29f',
                'gray-550': '#7c7f7c',
                'blue-950': '#090430',
                'accent': {
                    DEFAULT: 'var(--accent-500)',
                    50: 'var(--accent-50)',
                    100: 'var(--accent-100)',
                    200: 'var(--accent-200)',
                    300: 'var(--accent-300)',
                    400: 'var(--accent-400)',
                    500: 'var(--accent-500)',
                    600: 'var(--accent-600)',
                    700: 'var(--accent-700)',
                    800: 'var(--accent-800)',
                    900: 'var(--accent-900)',
                },
                'blue-850': '#021127',
                'blue-750': '#010E21',
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
    ],
};
